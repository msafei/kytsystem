@extends('templates.app')

@section('title', 'Edit Company')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white p-8 rounded-lg shadow-md w-2/3 mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-center">Edit Company</h2>

        <form method="POST" action="{{ route('companies.update', $company->id) }}">
            @csrf
            @method('POST')

            <!-- Company Name -->
            <div class="mb-4">
                <label class="block text-gray-700">Company Name</label>
                <input type="text" name="name" value="{{ $company->name }}" required class="w-full px-3 py-2 border rounded">
            </div>

            <!-- Company Type Dropdown -->
            <div class="mb-4">
                <label class="block text-gray-700">Company Type</label>
                <select name="companyType" required class="w-full px-3 py-2 border rounded">
                    <option value="1" {{ $company->companyType == 1 ? 'selected' : '' }}>Main Company</option>
                    <option value="2" {{ $company->companyType == 2 ? 'selected' : '' }}>Outsourcing</option>
                </select>
            </div>

            <!-- Status Dropdown -->
            <div class="mb-4">
                <label class="block text-gray-700">Status</label>
                <select name="status" required class="w-full px-3 py-2 border rounded">
                    <option value="1" {{ $company->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $company->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded">Update Company</button>
        </form>
    </div>
</div>
@endsection
