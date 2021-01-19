<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $e) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
                'status' => true
            ]
        );

        $userCreated->provider()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId()
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );

        $token = $userCreated->createToken('token-name')->plainTextToken;
        $cookie = Cookie::make('access.token.kt', $token);
        return Redirect::away(config('app.client_url') . '/auth/success?provider=' . $provider . '&access_token=' . $token, 302);
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['github'])) {
            return response()->json(['error' => 'Please login using github.'], 422);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out.'], 200);
    }
}
