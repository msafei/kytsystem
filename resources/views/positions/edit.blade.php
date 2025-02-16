@extends('templates.app')

@section('title', 'Edit Position')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white p-8 rounded-lg shadow-md w-2/3 mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-center">Edit Position</h2>

        <form method="POST" action="{{ route('positions.update', $position->id) }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700">Position Name</label>
                <input type="text" name="name" value="{{ $position->name }}" required class="w-full px-3 py-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Company Type</label>
                <select name="companyType" required class="w-full px-3 py-2 border rounded">
                    <option value="1" {{ $position->companyType == 1 ? 'selected' : '' }}>Main Company</option>
                    <option value="2" {{ $position->companyType == 2 ? 'selected' : '' }}>Outsourcing</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Default Role</label>
                <input type="number" name="defaultRole" value="{{ $position->defaultRole }}" required class="w-full px-3 py-2 border rounded">
            </div>

            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded">Update Position</button>
        </form>
    </div>
</div>
@endsection
