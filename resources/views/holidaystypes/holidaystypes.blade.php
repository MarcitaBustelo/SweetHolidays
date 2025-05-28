@extends('adminlte::page')

@section('title', __('Absence Types Management'))

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 style="color: #3b2469;">@lang('Absence Types Management')</h1>
    <div>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#holidayTypeModal"
            data-action="add">
            <i class="fas fa-plus"></i> @lang('Add Type')
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
        <h3 class="card-title" style="font-weight: bold; color: #6a3cc9;">@lang('Types List')</h3>
    </div>
    <div class="card-body bg-white">
        <table id="holidayTypesTable" class="table table-bordered table-hover">
            <thead>
                <tr style="background-color: #ebe4f6; color: #4b2e83;">
                    <th>@lang('Type')</th>
                    <th>@lang('Color')</th>
                    <th>@lang('Actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($holiday_types as $type)
                    <tr>
                        <td>{{ $type->type }}</td>
                        <td>
                            <span
                                style="display: inline-block; width: 24px; height: 24px; border-radius: 50%; background-color: {{ $type->color }}; border: 1px solid #ccc;"
                                title="{{ $type->color }}"></span>
                            <span style="margin-left: 8px; color: #555;">{{ $type->color }}</span>
                        </td>
                        <!-- <td>
                            <span style="display: inline-block; width: 24px; height: 24px; border-radius: 50%; background-color: {{ $type->color }}; border: 1px solid #ccc;" title="{{ $type->color }}"></span>
                        </td> -->
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                data-target="#holidayTypeModal" data-action="edit" data-id="{{ $type->id }}"
                                data-type="{{ $type->type }}" data-color="{{ $type->color }}">
                                <i class="fas fa-edit"></i> @lang('Edit')
                            </button>
                            <form action="{{ route('holiday_types.delete') }}" method="POST" style="display: inline-block;"
                                onsubmit="return confirm('@lang('Are you sure you want to delete this absence type?')')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $type->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="@lang('Delete')">
                                    <i class="fas fa-trash-alt"></i> @lang('Delete')
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">@lang('No absence types registered.')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Holiday Type Modal -->
<div class="modal fade" id="holidayTypeModal" tabindex="-1" role="dialog" aria-labelledby="holidayTypeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f4f6f9;">
                <h5 class="modal-title" id="holidayTypeModalLabel" style="font-weight: bold;">@lang('Add Type')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="@lang('Close')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="holidayTypeForm" action="{{ route('holiday_types.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="holidayTypeId">
                    <div class="form-group">
                        <label for="type">@lang('Type')</label>
                        <input type="text" class="form-control" name="type" id="holidayTypeName" required>
                    </div>
                    <div class="form-group">
                        <label for="color">@lang('Color')</label>
                        <input type="color" class="form-control" name="color" id="holidayTypeColor">
                        <small class="form-text text-muted">@lang('Leave blank for random color')</small>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#holidayTypesTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });

        $('#holidayTypeModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var action = button.data('action');
            var modal = $(this);
            var form = modal.find('#holidayTypeForm');

            form.find('input[name=_method]').remove();

            if (action === 'edit') {
                var typeId = button.data('id');
                var typeName = button.data('type');
                var typeColor = button.data('color');

                modal.find('.modal-title').text('@lang('Edit Type')');
                modal.find('#holidayTypeId').val(typeId);
                modal.find('#holidayTypeName').val(typeName);
                modal.find('#holidayTypeColor').val(typeColor);
                form.attr('action', '/holiday_types/' + typeId);

                // Add hidden input for PUT method spoofing
                form.append('<input type="hidden" name="_method" value="PUT">');

                modal.find('#submitBtn').text('@lang('Update')');
            } else {
                modal.find('.modal-title').text('@lang('Add Type')');
                modal.find('#holidayTypeId').val('');
                modal.find('#holidayTypeName').val('');
                modal.find('#holidayTypeColor').val('');
                form.attr('action', '/holiday_types');

                modal.find('#submitBtn').text('@lang('Save')');
            }
        });

        // --- Success message with SweetAlert2 ---
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#6a3cc9'
            });
        @endif
            // --- Error message with SweetAlert2 for validation errors ---
            @if ($errors->any())
                let errorMsg = '';
                @foreach ($errors->all() as $error)
                    errorMsg += "{{ $error }}\n";
                @endforeach
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg,
                    confirmButtonColor: '#6a3cc9'
                });
            @endif
    });
</script>
@stop