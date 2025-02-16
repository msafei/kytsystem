@extends('templates.app')

@section('title', 'Companies')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Companies List</h2>
    
    <a href="{{ route('companies.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add Company</a>

    <table class="table-auto w-full mt-4 border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Company Type</th>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
            <tr>
                <td class="border px-4 py-2">{{ $company->id }}</td>
                <td class="border px-4 py-2">{{ $company->name }}</td>
                
                <!-- Menampilkan Company Type -->
                <td class="border px-4 py-2">
                    @if($company->companyType == 1)
                        <span class="bg-blue-200 text-blue-700 px-2 py-1 rounded">Main Company</span>
                    @elseif($company->companyType == 2)
                        <span class="bg-yellow-200 text-yellow-700 px-2 py-1 rounded">Outsourcing</span>
                    @endif
                </td>

                <!-- Menampilkan Status -->
                <td class="border px-4 py-2">
                    @if($company->status == 1)
                        <span class="bg-green-200 text-green-700 px-2 py-1 rounded">Active</span>
                    @else
                        <span class="bg-red-200 text-red-700 px-2 py-1 rounded">Inactive</span>
                    @endif
                </td>

                <td class="border px-4 py-2">
                    <a href="{{ route('companies.edit', $company->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                    <form action="{{ route('companies.delete', $company->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
