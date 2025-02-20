@extends('templates.app')

@section('title', 'Add Flow KYT Report')

@section('content')
<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Add Flow KYT Report</h1>

    @if ($errors->any())
    <div class="bg-red-500 text-white p-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('flow_kyt_reports.store') }}" method="POST">
        @csrf

        <!-- Flow Selection -->
        <div>
            <label>Flow</label>
            <select name="flowStatus" class="border w-full p-2 rounded">
                <option value="1" @if(old('flowStatus') == 1) selected @endif>CHECKED</option>
                <option value="2" @if(old('flowStatus') == 2) selected @endif>REVIEWED</option>
                <option value="3" @if(old('flowStatus') == 3) selected @endif>APPROVED1</option>
                <option value="4" @if(old('flowStatus') == 4) selected @endif>APPROVED2</option>
            </select>
        </div>

        <!-- Company Type Selection -->
        <div>
            <label>Company Type</label>
            <select name="companyType" id="companyType" class="border w-full p-2 rounded">
                <option value="" disabled selected>-- Select Company Type --</option>
                <option value="1">Main Company</option>
                <option value="2">Outsourcing</option>
            </select>
        </div>

        <!-- Position Selection -->
        <div>
            <label>Position</label>
            <select name="position_id" id="position_id" class="border w-full p-2 rounded">
                <option value="" disabled selected>-- Select Position --</option>
                @foreach($positions as $position)
                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Save</button>
    </form>
</div>

<script>
    document.getElementById('companyType').addEventListener('change', function () {
        let companyType = this.value;
        fetch(`/flow_kyt_reports/get-positions/${companyType}`)
            .then(response => response.json())
            .then(data => {
                let positionSelect = document.getElementById('position_id');
                positionSelect.innerHTML = '<option value="" disabled selected>-- Select Position --</option>';
                data.forEach(position => {
                    let option = document.createElement('option');
                    option.value = position.id;
                    option.textContent = position.name;
                    positionSelect.appendChild(option);
                });
            });
    });
</script>
@endsection
