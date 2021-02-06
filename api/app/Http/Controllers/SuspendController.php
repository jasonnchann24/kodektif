<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SuspendController extends Controller
{
    public function __construct()
    {
        $this->middleware('only.admin');
    }

    public function suspendUser($id)
    {
        $targetUser = User::findOrFail($id);
        $targetUser->is_suspended = true;
        $targetUser->save();
        return Response::json(['message' => 'Suspended'], 200);
    }

    public function unSuspendUser($id)
    {
        $targetUser = User::findOrFail($id);
        $targetUser->is_suspended = false;
        $targetUser->save();
        return Response::json(['message' => 'Un-suspend'], 200);
    }
}
