@extends('templates.app')

@section('title', 'Add Company')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white p-8 rounded-lg shadow-md w-2/3 mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-center">Add Company</h2>

        <form method="POST" action="{{ route('companies.store') }}">
            @csrf

            <!-- Company Name -->
            <div class="mb-4">
                <label class="block text-gray-700">Company Name</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border rounded">
            </div>

            <!-- Company Type Dropdown -->
            <div class="mb-4">
                <label class="block text-gray-700">Company Type</label>
                <select name="companyType" required class="w-full px-3 py-2 border rounded">
                    <option value="1">Main Company</option>
                    <option value="2">Outsourcing</option>
                </select>
            </div>

            <!-- Status Dropdown -->
            <div class="mb-4">
                <label class="block text-gray-700">Status</label>
                <select name="status" required class="w-full px-3 py-2 border rounded">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded">Save Company</button>
        </form>
    </div>
</div>
@endsection
