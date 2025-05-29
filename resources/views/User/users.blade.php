@extends('adminlte::page')

@section('title', 'Employee Management')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 style="color: #3b2469;">Employee Management</h1>
    <div>
        @php
            $specialAccessEmployeeIds = ['10001', '10003'];
        @endphp
        @if (in_array(auth()->user()->employee_id, $specialAccessEmployeeIds))
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#uploadModal">
                <i class="fas fa-upload"></i> Update Users
            </button>
        @endif
        <a href="{{ route('menu.responsable') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Menu
        </a>
    </div>
</div>
@stop

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-light">
        <h3 class="card-title" style="font-weight: bold; color: #6a3cc9;">Assigned Employees</h3>
    </div>
    <div class="card-body bg-white">
        <div class="table-responsive">
            <table id="employeesTable" class="table table-bordered table-hover">
                <thead>
                    <tr style="background-color: #ebe4f6; color: #4b2e83;">
                        <th>Name</th>
                        <th>Delegation</th>
                        <th>Department</th>
                        @if (in_array(auth()->user()->employee_id, $specialAccessEmployeeIds))
                            <th>Responsible</th>
                        @endif
                        <th>Total Days</th>
                         @if (in_array(auth()->user()->employee_id, $specialAccessEmployeeIds))
                            <th>Status</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->delegation->name ?? 'No delegation' }}</td>
                            <td>
                                @if (in_array(auth()->user()->employee_id, $specialAccessEmployeeIds))
                                    <form action="{{ route('employees.updateDepartment', $employee->id) }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        @method('PUT')
                                        <select name="department_id" class="form-control form-control-sm mr-2" style="width: 150px;">
                                            <option value="">No Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->department_id }}"
                                                    {{ $employee->department_id == $department->department_id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-outline-primary btn-sm" title="Save">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </form>
                                @else
                                    {{ $employee->department->name ?? 'No Department' }}
                                @endif
                            </td>
                            @if (in_array(auth()->user()->employee_id, $specialAccessEmployeeIds))
                            <td>
                                <form action="{{ route('employees.updateResponsable', $employee->id) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    @method('PUT')
                                        <select name="responsable" class="form-control form-control-sm mr-2" style="width: 150px;">
                                           <option value="">No Responsible</option>
                                              @foreach ($responsables as $responsable)
                                                   <option value="{{ $responsable->employee_id }}" 
                                                      {{ $employee->responsable == $responsable->employee_id ? 'selected' : '' }}>
                                                        {{ $responsable->name }}
                                                  </option>
                                               @endforeach
                                        </select>
                                    <button type="submit" class="btn btn-outline-primary btn-sm" title="Save">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                            </td>
                            @endif
                            <td>
                                <form action="{{ route('employees.updateDays', $employee->id) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="days_in_total" value="{{ $employee->days_in_total }}" min="0"
                                        class="form-control form-control-sm mr-2" style="width: 80px; border-color: #c8b9e6;">
                                    <button type="submit" class="btn btn-outline-primary btn-sm" title="Save">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                            </td>
                            @if (in_array(auth()->user()->employee_id, $specialAccessEmployeeIds))
                                <td>
                                    <form action="{{ route('employees.toggleActive', $employee->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        @if ($employee->active)
                                            <button type="submit" class="btn btn-danger btn-sm" title="Desactivar">
                                                <i class="fas fa-user-slash"></i> Deactivate
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-success btn-sm" title="Activar">
                                                <i class="fas fa-user-check"></i> Activate
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No assigned employees.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold">Update Users</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('excel.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="excelFile">Select an Excel file:</label>
                        <input type="file" name="excelFile" id="excelFile" class="form-control" accept=".xlsx, .xls" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                    <div id="loadingSpinner" class="text-center d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p>Processing file, please wait...</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<style>
    body {
        background-color: #f9f9fb;
    }

    .btn-primary {
        background-color: #6a3cc9;
        border-color: #6a3cc9;
    }

    .btn-primary:hover {
        background-color: #5a31a8;
        border-color: #5a31a8;
    }

    .btn-warning {
        background-color: #f0ad4e;
        border-color: #f0ad4e;
    }

    .btn-outline-primary {
        color: #6a3cc9;
        border-color: #c8b9e6;
    }

    .btn-outline-primary:hover {
        background-color: #e9e0f9;
        color: #4b2e83;
    }

    table thead tr {
        background-color: #ebe4f6;
        color: #4b2e83;
    }

    table tbody tr:hover {
        background-color: #f5f2fa;
    }

    .form-control:focus {
        border-color: #b49ce0;
        box-shadow: 0 0 0 0.2rem rgba(106, 60, 201, 0.25);
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    #loadingSpinner {
        margin-top: 20px;
    }
</style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#employeesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/en-GB.json',
                searchPlaceholder: "Search..."
            },
            responsive: true,
            autoWidth: false
        });

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#6a3cc9'
            });
        @elseif (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#6a3cc9'
            });
        @endif
    });
</script>
@stop
