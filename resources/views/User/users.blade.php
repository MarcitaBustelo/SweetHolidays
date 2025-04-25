@extends('adminlte::page')

@section('title', 'Gestión de empleados')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 style="color: #0e0c5e;">Gestión de Empleados</h1>
        <a href="{{ route('menu.responsable') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Volver al Menú
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header" style="background-color: #f4f6f9;">
            <h3 class="card-title" style="font-weight: bold;">Lista de empleados asignados</h3>
        </div>
        <div class="card-body">
            <table id="employeesTable" class="table table-bordered table-striped">
                <thead>
                    <tr style="background-color: #0e0c5e; color: white;">
                        <th>Nombre</th>
                        <th>Delegación</th>
                        <th>Departamento</th>
                        <th>Días Totales</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->delegation->name ?? 'Sin delegación' }}</td>
                            <td>{{ $employee->department->name ?? 'Sin departamento' }}</td>
                            <td>
                                <form action="{{ route('employees.updateDays', $employee->id) }}" method="POST" style="display: flex; align-items: center;">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="days_in_total" value="{{ $employee->days_in_total }}" min="0" class="form-control" style="width: 80px; margin-right: 5px;">
                                    <button type="submit" class="btn btn-success btn-sm" title="Guardar">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay empleados asignados.</td>
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
        .btn-primary {
            background-color: #0e0c5e;
            border-color: #0e0c5e;
        }

        .btn-primary:hover {
            background-color: #0c0a56;
            border-color: #0c0a56;
        }

        .card-header {
            font-size: 1.2rem;
        }

        table thead tr {
            background-color: #0e0c5e;
            color: white;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
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
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                responsive: true,
                autoWidth: false
            });
        });
    </script>
@stop