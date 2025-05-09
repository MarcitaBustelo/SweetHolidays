@extends('adminlte::page')

@section('title', 'Gestión de Festivos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 style="color: #0e0c5e;">Gestión de Festivos</h1>
        <div>
            <a href="{{ route('menu.responsable') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Volver al Menú
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card-body">
        <div class="table-responsive">
            <table id="festivesTable" class="table table-bordered table-striped">
                <thead>
                    <tr style="background-color: #0e0c5e; color: white;">
                        <th>Nombre</th>
                        <th>Delegación</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($festives as $festive)
                        <tr>
                            <td>{{ $festive->name }}</td>
                            <td>{{ $festive->delegation->name ?? 'Nacional' }}</td>
                            <td>
                                <form action="{{ route('festives.editDate', $festive->id) }}" method="POST"
                                    style="display: flex; align-items: center;">
                                    @csrf
                                    @method('PUT')
                                    <input type="date" name="date" value="{{ $festive->date }}" class="form-control"
                                        style="width: 150px; margin-right: 5px;">
                                    <button type="submit" class="btn btn-success btn-sm" title="Guardar">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No hay festivos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '0.0.1') }}
    </div>

    <strong>
        Copyright &copy; 2025
        <a href="{{ config('app.company_url', 'https://bayport.eu/') }}">
            {{ config('app.company_name', 'BayportWebServices') }}
        </a>
        All rights reserved.
    </strong>
@stop


@section('css')
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
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            var table = $('#festivesTable').DataTable({
                "pagingType": "full_numbers",
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Buscar...",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "paginate": {
                        "first": "<<",
                        "last": ">>",
                        "next": ">",
                        "previous": "<"
                    }
                },
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#0e0c5e'
                });
            @endif
        });
    </script>
@stop