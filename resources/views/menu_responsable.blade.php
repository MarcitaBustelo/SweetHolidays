@extends('adminlte::page')
@section('adminlte_js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('js')
@endsection

@section('title', 'Responsible Menu')

@section('content_header')
<h1 style="color: #4b0082;">Responsible Menu</h1>
@stop

@section('content')
<div class="row">
    <!-- My Calendar Card -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm" style="background-color: #f5e8ff; border-color: #e0c3fc;">
            <div class="card-header" style="background-color: #e0c3fc; border-bottom-color: #c79bf2;">
                <h3 class="card-title" style="color: #4b0082;">
                    <i class="fas fa-calendar-alt mr-2"></i>My Calendar
                </h3>
            </div>
            <div class="card-body">
                <p style="color: #4b0082;">View and manage your personal calendar.</p>
                <a href="{{ route('user.respon_calendar') }}" class="btn"
                    style="background-color: #a066c9; color: white; border: none;">
                    <i class="fas fa-calendar-alt mr-2"></i> View Calendar
                </a>
            </div>
        </div>
    </div>

    <!-- My Profile Card -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm" style="background-color: #f5e8ff; border-color: #e0c3fc;">
            <div class="card-header" style="background-color: #e0c3fc; border-bottom-color: #c79bf2;">
                <h3 class="card-title" style="color: #4b0082;">
                    <i class="fas fa-user mr-2"></i>My Profile
                </h3>
            </div>
            <div class="card-body">
                <p style="color: #4b0082;">View and update your personal information.</p>
                <a href="{{ route('user.profile') }}" class="btn"
                    style="background-color: #a066c9; color: white; border: none;">
                    <i class="fas fa-user mr-2"></i> View Profile
                </a>
            </div>
        </div>
    </div>

        <div class="col-md-6 mb-4">
        <div class="card shadow-sm" style="background-color: #f5e8ff; border-color: #e0c3fc;">
            <div class="card-header" style="background-color: #e0c3fc; border-bottom-color: #c79bf2;">
                <h3 class="card-title" style="color: #4b0082;">
                    <i class="fas fa-user mr-2"></i>Manage Leave Types
                </h3>
            </div>
            <div class="card-body">
                <p style="color: #4b0082;">Manage types of work absences.</p>
                <a href="{{ route('holiday_types.index') }}" class="btn"
                    style="background-color: #a066c9; color: white; border: none;">
                    <i class="fas fa-user mr-2"></i> View Types
                </a>
            </div>
        </div>
    </div>

    <!-- Manage Users -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm" style="background-color: #f5e8ff; border-color: #e0c3fc;">
            <div class="card-header" style="background-color: #e0c3fc; border-bottom-color: #c79bf2;">
                <h3 class="card-title" style="color: #4b0082;">
                    <i class="fas fa-user mr-2"></i>Manage Users
                </h3>
            </div>
            <div class="card-body">
                <p style="color: #4b0082;">View your employees.</p>
                <a href="{{ route('user.users') }}" class="btn"
                    style="background-color: #a066c9; color: white; border: none;">
                    <i class="fas fa-user mr-2"></i> View Users
                </a>
            </div>
        </div>
    </div>

    <!-- Manage Holidays (conditional) -->
    @php
        $specialAccessEmployeeIds = ['10001', '10003'];
    @endphp
    @if (in_array(auth()->user()->employee_id, $specialAccessEmployeeIds))
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm" style="background-color: #f5e8ff; border-color: #e0c3fc;">
                <div class="card-header" style="background-color: #e0c3fc; border-bottom-color: #c79bf2;">
                    <h3 class="card-title" style="color: #4b0082;">
                        <i class="far fa-calendar-check mr-2"></i>Manage Festive Holidays
                    </h3>
                </div>
                <div class="card-body">
                    <p style="color: #4b0082;">View official holidays.</p>
                    <a href="{{ route('festives.festives') }}" class="btn"
                        style="background-color: #a066c9; color: white; border: none;">
                        <i class="far fa-calendar-check mr-2"></i> View Holidays
                    </a>
                </div>
            </div>
        </div>
    @endif

    @php
        $specialAccessEmployeeIds = ['10001', '10003'];
    @endphp
    @if (in_array(auth()->user()->employee_id, $specialAccessEmployeeIds))
        <!-- Manage Departments -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm" style="background-color: #f5e8ff; border-color: #e0c3fc;">
                <div class="card-header" style="background-color: #e0c3fc; border-bottom-color: #c79bf2;">
                    <h3 class="card-title" style="color: #4b0082;">
                        <i class="fas fa-bezier-curve mr-2"></i>Manage Departments
                    </h3>
                </div>
                <div class="card-body">
                    <p style="color: #4b0082;">View departments.</p>
                    <a href="{{ route('departments.departments') }}" class="btn"
                        style="background-color: #a066c9; color: white; border: none;">
                        <i class="fas fa-bezier-curve mr-2"></i> View Departments
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Manage Holidays (conditional) -->
    @php
        $specialAccessEmployeeIds = ['10001', '10003'];
    @endphp
    @if (in_array(auth()->user()->employee_id, $specialAccessEmployeeIds))
        <!-- Manage Branches -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm" style="background-color: #f5e8ff; border-color: #e0c3fc;">
                <div class="card-header" style="background-color: #e0c3fc; border-bottom-color: #c79bf2;">
                    <h3 class="card-title" style="color: #4b0082;">
                        <i class="fab fa-unity mr-2"></i>Manage Branches
                    </h3>
                </div>
                <div class="card-body">
                    <p style="color: #4b0082;">View branch offices.</p>
                    <a href="{{ route('delegations.delegations') }}" class="btn"
                        style="background-color: #a066c9; color: white; border: none;">
                        <i class="fab fa-unity mr-2"></i> View Branches
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm" style="background-color: #f5e8ff; border-color: #e0c3fc;">
            <div class="card-header" style="background-color: #e0c3fc; border-bottom-color: #c79bf2;">
                <h3 class="card-title" style="color: #4b0082;">
                    <i class="fas fa-plus mr-2"></i>Absences Management
                </h3>
            </div>
            <div class="card-body">
                <p style="color: #4b0082;">See how many people and absences per day</p>
                <a href="{{ route('holidays.management') }}" class="btn"
                    style="background-color: #a066c9; color: white; border: none;">
                    <i class="fas fa-plus mr-2"></i>
                    View Absences
                </a>
            </div>
        </div>
    </div>
</div>

@if (in_array(auth()->user()->employee_id, $specialAccessEmployeeIds))
    <!-- Manage Branches -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm" style="background-color: #f5e8ff; border-color: #e0c3fc;">
            <div class="card-header" style="background-color: #e0c3fc; border-bottom-color: #c79bf2;">
                <h3 class="card-title" style="color: #4b0082;">
                    <i class="fas fa-clock mr-2"></i>View Arrivals
                </h3>
            </div>
            <div class="card-body">
                <p style="color: #4b0082;">View employees arrivals</p>
                <a href="{{ route('arrivals.index') }}" class="btn"
                    style="background-color: #a066c9; color: white; border: none;">
                    <i class="fas fa-running mr-2"></i> View Arrivals
                </a>
            </div>
        </div>
    </div>
@endif
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">

<style>
    body {
        background-color: #f7f3fa;
    }

    .card {
        background-color: #f9f5fc;
        border: 1px solid #e2d4f4;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(76, 42, 133, 0.15) !important;
    }

    .card-header {
        background-color: #e8daf6;
        border-bottom-color: #d1b8f3;
        border-radius: 12px 12px 0 0 !important;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #4c2a85;
    }

    .card-body p {
        color: #4c2a85;
    }

    .btn {
        background-color: #a97cd1;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(76, 42, 133, 0.1);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(76, 42, 133, 0.2);
        opacity: 0.95;
    }

    .content-header h1 {
        color: #4c2a85;
        border-bottom: 2px solid #d9c4ee;
        padding-bottom: 10px;
        display: inline-block;
    }

    .swal2-title {
        color: #4c2a85 !important;
    }

    .swal2-popup {
        border-radius: 10px;
    }

    .swal2-styled.swal2-confirm {
        background-color: #a97cd1 !important;
    }

    .swal2-styled.swal2-cancel {
        background-color: #ccc !important;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    // ====== CAMBIOS: Asignación de eventos y funciones globales ======
    $(function () {
        $('#btn-create-type').on('click', openCreateHolidayType);
        $('#btn-view-types').on('click', viewHolidayTypes);
    });
    function openCreateHolidayType() {
        Swal.fire({
            title: 'Create new Type of Absence',
            html: `
                <form id="createHolidayTypeForm">
                    <div class="form-group">
                        <label for="type">Name of absences</label>
                        <input type="text" id="type" name="type" class="form-control" placeholder="Example: Vacations" required>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Create',
            preConfirm: () => {
                const form = document.getElementById('createHolidayTypeForm');
                const formData = new FormData(form);

                // Enviar datos al servidor
                return fetch('{{ route('holiday_types.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw new Error(err.message ||
                                    'Oops! Something went wrong.');
                            });
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Error: ${error.message}`
                        );
                    });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('¡Type of holiday saved!', '', 'success');
            }
        });
    }

    function viewHolidayTypes() {
        fetch('{{ route('holiday_types.index') }}')
            .then(response => response.json())
            .then(data => {
                let typesHtml = '<ul style="list-style: none; padding: 0;">';
                data.forEach(type => {
                    typesHtml += `
                <li style="margin-bottom: 10px;">
                    <span>${type.type}</span>
                    <button onclick="deleteHolidayType(${type.id})"
                            style="margin-left: 5px; padding: 5px 10px; color: white; background-color: #dc3545; border: none; border-radius: 5px;">
                        Delete
                    </button>
                </li>
            `;
                });
                typesHtml += '</ul>';

                Swal.fire({
                    title: 'Type of Absences',
                    icon: 'info',
                    html: typesHtml,
                    showCloseButton: true,
                    showConfirmButton: false
                });
            })
            .catch(error => {
                Swal.fire('Error', 'Absence types could not be loaded.', 'error');
            });
    }

    function deleteHolidayType(id) {
        Swal.fire({
            title: '¿Are you sure?',
            text: "This action cant be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route('holiday_types.delete') }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: id
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success');
                            viewHolidayTypes(); // Recargar la lista
                        } else {
                            Swal.fire('Error', 'Could not delete the type of absences', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Error', 'error');
                    });
            }
        });
    }
</script>
@stop