@extends('adminlte::page')

@section('title', __('Department Management'))

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 style="color: #3b2469;">@lang('Department Management')</h1>
    <div>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#departmentModal"
            data-action="add">
            <i class="fas fa-plus"></i> @lang('Add Department')
        </button>
        <a href="{{ route('menu.responsable') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> @lang('Back to Menu')
        </a>
    </div>
</div>
@stop

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-light">
        <h3 class="card-title" style="font-weight: bold; color: #6a3cc9;">@lang('Department List')</h3>
    </div>
    <div class="card-body bg-white">
        <table id="departmentsTable" class="table table-bordered table-hover">
            <thead>
                <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
                <tr style="background-color: #ebe4f6; color: #4b2e83;">
                    <th>@lang('Name')</th>
                    <th>@lang('Actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($departments as $department)
                    <tr>
                        <td>{{ $department->name }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                data-target="#departmentModal" data-action="edit" data-id="{{ $department->id }}"
                                data-name="{{ $department->name }}">
                                <i class="fas fa-edit"></i> @lang('Edit')
                            </button>
                            <form action="{{ route('departments.destroy', $department->id) }}" method="POST"
                                style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="@lang('Delete')"
                                    onclick="return confirm('@lang('Are you sure you want to delete this department?')')">
                                    <i class="fas fa-trash-alt"></i> @lang('Delete')
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted">@lang('No departments registered.')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Department Modal -->
<div class="modal fade" id="departmentModal" tabindex="-1" role="dialog" aria-labelledby="departmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f4f6f9;">
                <h5 class="modal-title" id="departmentModalLabel" style="font-weight: bold;">@lang('Add Department')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="@lang('Close')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="departmentForm" action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="departmentId">
                    <div class="form-group">
                        <label for="name">@lang('Department Name')</label>
                        <input type="text" class="form-control" name="name" id="departmentName" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">@lang('Save')</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Cancel')</button>
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
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#departmentsTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });

        $('#departmentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var action = button.data('action');
            var modal = $(this);
            var form = modal.find('#departmentForm');

            form.find('input[name=_method]').remove();

            if (action === 'edit') {
                var departmentId = button.data('id');
                var departmentName = button.data('name');

                modal.find('.modal-title').text('@lang('Edit Department')');
                modal.find('#departmentId').val(departmentId);
                modal.find('#departmentName').val(departmentName);
                form.attr('action', '/departments/' + departmentId);

                // Agrega input hidden para spoofing method PUT
                form.append('<input type="hidden" name="_method" value="PUT">');

                modal.find('#submitBtn').text('@lang('Update')');
            } else {
                modal.find('.modal-title').text('@lang('Add Department')');
                modal.find('#departmentId').val('');
                modal.find('#departmentName').val('');
                form.attr('action', '/departments');

                modal.find('#submitBtn').text('@lang('Save')');
            }
        });
    });
</script>
@stop