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

    public function store(Request $request)
    {
        $id = $request->get('id');
        $this->handleSuspend($id, true);
        return Response::json(
            ['message' => 'Suspended'],
            200
        );
    }

    public function destroy($id)
    {
        $this->handleSuspend($id, false);
        return Response::json(
            ['message' => 'Un-suspend'],
            200
        );
    }

    protected function handleSuspend($id, $suspend)
    {
        $targetUser = User::findOrFail($id);
        $targetUser->is_suspended = $suspend;
        $targetUser->save();
    }
}
