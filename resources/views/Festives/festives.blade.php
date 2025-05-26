@extends('adminlte::page')

@section('title', 'Festive Management')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 style="color: #6a3cc9;">Festive Management</h1>
    <a href="{{ route('menu.responsable') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Menu
    </a>
</div>
@stop
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-light">
        <h3 class="card-title" style="font-weight: bold; color: #6a3cc9;">Registered Festives</h3>
        <button class="btn btn-primary float-right" data-toggle="modal" data-target="#createFestiveModal">
            <i class="fas fa-plus"></i> Add Festive
        </button>
    </div>
    <div class="card-body bg-white">
        <table id="festivesTable" class="table table-bordered table-hover">
            <thead>
                <tr style="background-color: #ebe4f6; color: #4b2e83;">
                    <th>Name</th>
                    <th>Delegation</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($festives as $festive)
                    <tr>
                        <td>{{ $festive->name }}</td>
                        <td>
                            {{ $festive->national ? 'Nacional' : ($festive->delegation->name ?? 'No asignada') }}
                        </td>
                        <td>
                            <form action="{{ route('festives.editDate', $festive->id) }}" method="POST"
                                class="d-flex align-items-center">
                                @csrf
                                @method('PUT')
                                <input type="date" name="date" value="{{ $festive->date }}"
                                    class="form-control form-control-sm mr-2" style="width: 140px; border-color: #c8b9e6;">
                                <button type="submit" class="btn btn-outline-primary btn-sm" title="Save">
                                    <i class="fas fa-save"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <!-- Botón para editar -->
                            <button class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                data-target="#editFestiveModal{{ $festive->id }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Botón para eliminar -->
                            <button class="btn btn-sm btn-outline-danger" data-toggle="modal"
                                data-target="#deleteFestiveModal{{ $festive->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal para editar -->
                    <div class="modal fade" id="editFestiveModal{{ $festive->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editFestiveModalLabel{{ $festive->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('festives.update', $festive->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editFestiveModalLabel{{ $festive->id }}">Edit Festive
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name">Festive Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $festive->name }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="date">Date</label>
                                            <input type="date" name="date" class="form-control" value="{{ $festive->date }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="delegation_id">Delegation</label>
                                            <select name="delegation_id" class="form-control">
                                                <option value="">No Delegation</option>
                                                @foreach($delegations as $delegation)
                                                    <option value="{{ $delegation->id }}" {{ $festive->delegation_id == $delegation->id ? 'selected' : '' }}>
                                                        {{ $delegation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group form-check">
                                            <input type="hidden" name="national" value="0">
                                            <input type="checkbox" name="national" class="form-check-input"
                                                id="nationalCheck{{ $festive->id }}" value="1" {{ $festive->national ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="nationalCheck{{ $festive->id }}">National</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal para eliminar -->
                    <div class="modal fade" id="deleteFestiveModal{{ $festive->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="deleteFestiveModalLabel{{ $festive->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('festives.destroy', $festive->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteFestiveModalLabel{{ $festive->id }}">Delete
                                            Festive</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this festive?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No festives registered.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="createFestiveModal" tabindex="-1" role="dialog" aria-labelledby="createFestiveModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('festives.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFestiveModalLabel">Add New Festive</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Festive Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Festive Name" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="delegation_id">Delegation</label>
                        <select name="delegation_id" class="form-control">
                            <option value="">No Delegation</option>
                            @foreach($delegations as $delegation)
                                <option value="{{ $delegation->id }}">{{ $delegation->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group form-check">
                        <input type="hidden" name="national" value="0">
                        <input type="checkbox" name="national" class="form-check-input" id="nationalCheck" value="1">
                        <label class="form-check-label" for="nationalCheck">National</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Festive</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop
@section('css')
<!-- DataTables CSS -->
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#festivesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/en-GB.json'
            },
            responsive: true,
            autoWidth: false
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#6a3cc9'
            });
        @endif
        });
</script>
@stop