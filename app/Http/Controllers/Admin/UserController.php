<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\UserDeletedMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = User::whereIn('role', [1, 2])->get();
        return view('admin.user', compact('users'));
    }

    public function deleteUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason'  => 'required|string|max:255'
        ]);

        $user = User::findOrFail($request->user_id);

        $user->delete_reason = $request->reason;
        $user->save();
        Mail::to($user->email)->send(new UserDeletedMail($user, $request->reason));
        $user->delete();

        return redirect()->back()->with('success', 'User deleted & email sent successfully');
    }

}
