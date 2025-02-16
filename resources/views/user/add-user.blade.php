@extends('templates.app')

@section('title', 'Add Users')

@section('content')
<h2 class="text-2xl">Tambah User</h2>
<form method="POST" action="{{ route('user.store') }}">
    @csrf
    <input type="text" name="username" placeholder="Username" class="w-full px-3 py-2 border rounded mb-4">
    <input type="password" name="password" placeholder="Password" class="w-full px-3 py-2 border rounded mb-4">
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Tambah</button>
</form>
@endsection
