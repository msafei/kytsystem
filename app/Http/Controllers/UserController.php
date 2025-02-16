<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function create()
    {
        return view('user.add-user');
    }

    public function store(Request $request)
    {
        User::create([
            'id' => Str::uuid(),
            'username' => $request->username,
            'password' => $request->password,
        ]);

        return redirect()->route('dashboard')->with('success', 'User berhasil ditambahkan');
    }
}
