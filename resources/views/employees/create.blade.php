@extends('templates.app')

@section('title', 'Add Employee')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white p-8 rounded-lg shadow-md w-2/3 mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-center">Add Employee</h2>

        <form method="POST" action="{{ route('employees.store') }}">
            @csrf

            <!-- NIK -->
            <div class="mb-4">
                <label class="block text-gray-700">NIK</label>
                <input type="text" name="nik" required class="w-full px-3 py-2 border rounded">
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-gray-700">Name</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border rounded">
            </div>

            <!-- Company Dropdown -->
            <div class="mb-4">
                <label class="block text-gray-700">Company</label>
                <select name="company_id" id="companySelect" required class="w-full px-3 py-2 border rounded">
                    <option value="">Silahkan Pilih Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" data-companyType="{{ $company->companyType }}">
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Department Dropdown (Hanya tampil jika companyType = 1) -->
            <div class="mb-4" id="departmentSection" style="display: none;">
                <label class="block text-gray-700">Department</label>
                <select name="department_id" class="w-full px-3 py-2 border rounded">
                    <option value="">Silahkan Pilih Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Position Dropdown (Tampil sesuai companyType) -->
            <div class="mb-4">
                <label class="block text-gray-700">Position</label>
                <select name="position_id" id="positionSelect" required class="w-full px-3 py-2 border rounded">
                    <option value="">Silahkan Pilih Position</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded">Save Employee</button>
        </form>
    </div>
</div>

<script>
    document.getElementById("companySelect").addEventListener("change", function () {
        let companyType = this.options[this.selectedIndex].getAttribute("data-companyType");
        
        // Show or hide department field based on companyType
        if (companyType == "1") {
            document.getElementById("departmentSection").style.display = "block";
        } else {
            document.getElementById("departmentSection").style.display = "none";
        }

        // Load positions dynamically based on selected company
        let companyId = this.value;
        if (companyId) {
            fetch("{{ route('employees.getPositions') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({ company_id: companyId }),
            })
            .then(response => response.json())
            .then(data => {
                let positionSelect = document.getElementById("positionSelect");
                positionSelect.innerHTML = "<option value=''>Silahkan Pilih Position</option>";
                data.forEach(position => {
                    positionSelect.innerHTML += `<option value="${position.id}">${position.name}</option>`;
                });
            });
        }
    });
</script>
@endsection
