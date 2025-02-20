<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    // API untuk DataTables
    public function getUsersData()
    {
        $users = User::select(['id', 'username', 'status_id', 'role', 'created_at']);

        return DataTables::of($users)
            ->addColumn('action', function ($user) {
                return '<a href="#" class="bg-blue-500 text-white px-3 py-1 rounded">Edit</a>
                        <a href="#" class="bg-red-500 text-white px-3 py-1 rounded ml-2">Delete</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('user.add-user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

        // Relasi dengan flow_kyt_report
        public function flow_kyt_report()
        {
            return $this->hasOne(FlowKytReport::class, 'position_id', 'employee_position_id');
        }
}
