@extends('adminlte::page')

@section('title', 'Employee Management')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 style="color: #6a3cc9;">Employee Management</h1>
        <a href="{{ route('menu.responsable') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Menu
        </a>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <h3 class="card-title" style="font-weight: bold; color: #6a3cc9;">Assigned Employees List</h3>
        </div>
        <div class="card-body bg-white">
            <table id="employeesTable" class="table table-bordered table-hover">
                <thead>
                    <tr style="background-color: #ebe4f6; color: #4b2e83;">
                        <th>Name</th>
                        <th>Delegation</th>
                        <th>Department</th>
                        <th>Total Days</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->delegation->name ?? 'No delegation' }}</td>
                            <td>{{ $employee->department->name ?? 'No department' }}</td>
                            <td>
                                <form action="{{ route('employees.updateDays', $employee->id) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="days_in_total" value="{{ $employee->days_in_total }}" min="0"
                                           class="form-control form-control-sm mr-2"
                                           style="width: 80px; border-color: #c8b9e6;">
                                    <button type="submit" class="btn btn-outline-primary btn-sm" title="Save">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No assigned employees.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
    </style>
@stop

@section('js')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#employeesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/en-GB.json'
                },
                responsive: true,
                autoWidth: false
            });
        });
    </script>
@stop
