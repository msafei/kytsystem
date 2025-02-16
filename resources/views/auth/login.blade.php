@extends('templates.app')

@section('title', 'Login')

@section('content')
<div class="flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96 flex flex-col items-center">

        <!-- Logo & Text "Kiken Yochi Training System Login" -->
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/logo.svg') }}" alt="KYT System Logo" class="h-12 w-auto"> <!-- Perkecil logo -->
        </div>

        <!-- Form Login -->
        <h2 class="text-2xl font-bold mb-4">KYTsystem Login</h2>
        @if(session('error'))
            <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('login.process') }}" class="w-full">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded">Login</button>
        </form>
    </div>
</div>
@endsection
