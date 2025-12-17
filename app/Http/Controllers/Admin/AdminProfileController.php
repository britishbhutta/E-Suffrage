<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminProfileController extends Controller
{
public function edit()
{
    $user = auth()->user();
    return view('admin.profile.edit', compact('user'));
}

public function update(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'password' => 'nullable|min:6',
    ]);

    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;

    if ($request->password) {
        $user->password = bcrypt($request->password);
    }

    $user->save();

    return back()->with('success', 'Profile updated successfully!');
}

}
