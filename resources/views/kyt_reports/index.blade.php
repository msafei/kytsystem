@extends('templates.app')

@section('title', 'KYT Reports')

@section('content')
<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">KYT Reports</h1>

    @if ($errors->any())
    <div class="bg-red-500 text-white p-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <table class="table-auto w-full border-collapse">
        <thead>
            <tr>
                <th class="border p-2">ID</th>
                <th class="border p-2">Project Title</th>
                <th class="border p-2">Company</th>
                <th class="border p-2">Department</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kytReports as $kytReport)
                <tr>
                    <td class="border p-2">{{ $kytReport->id }}</td>
                    <td class="border p-2">{{ $kytReport->projectTitle }}</td>
                    <td class="border p-2">{{ $kytReport->company->name }}</td>
                    <td class="border p-2">{{ $kytReport->department->name ?? '-' }}</td>
                    <td class="border p-2">
                        @if($kytReport->status == 0)
                            Pending
                        @elseif($kytReport->status == 1)
                            Checked
                        @elseif($kytReport->status == 2)
                            Reviewed
                        @elseif($kytReport->status == 3)
                            Approved 1
                        @elseif($kytReport->status == 4)
                            Approved 2
                        @elseif($kytReport->status == 5)
                            Rejected
                        @endif
                    </td>
                    <td class="border p-2">
                        <a href="{{ route('kyt_reports.view', $kytReport->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded">View</a>
                        <a href="{{ route('kyt_reports.edit', $kytReport->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Edit</a>
                        <form action="{{ route('kyt_reports.destroy', $kytReport->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                        </form>

                        @if($kytReport->status != 5)
                            <!-- Conditionally hide buttons based on KYT Report status -->
                            <form action="{{ route('kyt_reports.approve', $kytReport->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Approve</button>
                            </form>

                            <form action="{{ route('kyt_reports.reject', $kytReport->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Reject</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
