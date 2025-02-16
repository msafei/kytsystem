@extends('templates.app')

@section('title', 'Add User')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Tambah User</h2>

    <!-- Alert Jika User Ditambahkan -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700">Username</label>
            <input type="text" name="username" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border rounded">
        </div>
        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded">Tambah</button>
    </form>
</div>
@endsection
