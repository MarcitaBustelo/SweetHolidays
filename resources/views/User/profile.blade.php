@extends('adminlte::page')

@section('title', 'User Profile')

@section('content_header')
<h1 style="color: #7f4cdb;"><i class="fas fa-user-circle mr-2"></i>My Profile</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <!-- Main Card -->
        <div class="card shadow-lg" style="border-color: #c0a6f3;">
            <div class="card-header" style="background: linear-gradient(135deg, #3b2469 0%, #c0a6f3 100%);">
                <h3 class="card-title" style="color:rgb(239, 236, 243);">
                    <i class="fas fa-id-card mr-2"></i>Personal Information
                </h3>
            </div>
            <div class="card-body" style="background-color: #f3e9ff;">
                <div class="row">
                    <!-- Left Column - Personal Data -->
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <h4 style="color:rgb(59, 36, 105);">{{ $user->name }}</h4>
                            <p class="mb-4" style="color: #3b2469;">
                                <i class="fas fa-building mr-1"></i>
                                {{ $user->delegation->name ?? 'No delegation' }}<br>
                                <i class="fas fa-users mr-1"></i> {{ $user->department->name ?? 'No department' }}
                            </p>
                            <hr style="border-color: #d7bbf3;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card" style="border-color: #9a74f7; background-color: #e4daf9;">
                            <div class="card-header"
                                style="background: linear-gradient(135deg, #d7bbf3 0%, #3b2469 70%);">
                                <h3 class="card-title" style="color:rgb(236, 233, 241);">
                                    <i class="fas fa-umbrella-beach mr-2"></i>My Vacation
                                </h3>
                            </div>
                            <div class="card-body">
                                <!-- Progress Bar -->
                                <div class="progress mb-3" style="background-color: #f0e7ff;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ ($remainingDays / $user->days) * 100 }}%; background-color: #7f4cdb;"
                                        aria-valuenow="{{ $remainingDays }}" aria-valuemin="0"
                                        aria-valuemax="{{ $user->days }}">
                                        <span class="progress-text">{{ round(($remainingDays / $user->days) * 100) }}%
                                            Available</span>
                                    </div>
                                </div>
                                <div class="stats" style="background-color: #3b2469;">
                                    <div class="stat-item">
                                        <span class="stat-label">Total Days:</span>
                                        <span class="stat-value badge"
                                            style="background-color: #8e5bff; color: white;">{{ $user->days_in_total }}</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">Remaining Days:</span>
                                        <span class="stat-value badge"
                                            style="background-color: #6a3cc9; color: white;">{{ $remainingDays }}</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">Absences:</span>
                                        <span class="stat-value badge"
                                            style="background-color: #9b6ff4; color: white;">{{ $absenceDaysUsed }}</span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <h5 style="color: #6a3cc9;"><i class="far fa-calendar mr-2"></i>Upcoming Absences
                                    </h5>
                                    @if (count($upcomingHolidays) > 0)
                                        <ul class="list-group">
                                            @foreach ($upcomingHolidays as $holiday)
                                                <li class="list-group-item d-flex justify-content-between align-items-center"
                                                    style="background-color: #f7ebff; border-color: #d7bbf3;">
                                                    {{ $holiday->holiday_type }}
                                                    <span class="badge rounded-pill"
                                                        style="background-color: #c0a6f3; color: #7f4cdb;">
                                                        {{ \Carbon\Carbon::parse($holiday->start_date)->format('d/m') }}
                                                        @if ($holiday->end_date)
                                                            -
                                                            {{ \Carbon\Carbon::parse($holiday->end_date)->format('d/m') }}
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="alert"
                                            style="background-color: #d7bbf3; border-color: #9a74f7; color: #7f4cdb;">
                                            You have no scheduled absences
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right" style="background-color: #e8f2ff; border-top-color: #a8c4e8;">
                <button class="btn" style="background-color: #9b5df2; color: white;" data-toggle="modal"
                    data-target="#changePasswordModal">
                    <i class="fas fa-lock mr-2"></i>Change Password
                </button>
            </div>
            <div class="card-footer text-right" style="background-color: #e4daf9; border-top-color: #c0a6f3;">
                <a href="{{ route('menu.responsable') }}" class="btn" style="background-color: #9b5df2; color: white;">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Menu
                </a>
            </div>
        </div>
    </div>
</div>

<div id="changePasswordModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="changePasswordForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="form-errors" class="alert alert-danger d-none">
                        <!-- Aquí se mostrarán los errores -->
                    </div>
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" class="form-control" id="new_password_confirmation"
                            name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
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
    .user-info p {
        margin-bottom: 0.8rem;
        font-size: 1rem;
        color: rgb(244, 244, 245);
    }

    .progress {
        height: 25px;
        border-radius: 12px;
        background-color: #f0e7ff;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .progress-bar {
        position: relative;
        border-radius: 12px;
        font-weight: 500;
        transition: width 0.6s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .progress-text {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.2);
        font-size: 0.85rem;
    }

    .stats {
        margin-top: 1.5rem;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #d7bbf3;
    }

    .stat-label {
        font-weight: 500;
        color: rgb(247, 245, 250);
    }

    .stat-value {
        font-size: 1rem;
        min-width: 40px;
        text-align: center;
        padding: 5px 10px;
        border-radius: 12px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .list-group-item {
        padding: 0.75rem 1.25rem;
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #e8d8f7;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .alert {
        border-radius: 8px;
        padding: 10px 15px;
    }

    body {
        background-color: #f3e9ff;
    }

    /* Estilo para el toast */
    .custom-toast {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #4caf50;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0;
        transform: translateY(-20px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        z-index: 1050;
    }

    .custom-toast.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .custom-toast i {
        font-size: 1.5rem;
    }

    .custom-toast span {
        font-size: 1rem;
        font-weight: 500;
    }
</style>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('changePasswordForm');
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            const errorContainer = document.getElementById('form-errors');
            errorContainer.classList.add('d-none');
            errorContainer.innerHTML = '';

            fetch('{{ route('user.changePassword') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.errors) {
                        errorContainer.classList.remove('d-none');
                        Object.values(data.errors).forEach(error => {
                            const errorItem = document.createElement('p');
                            errorItem.textContent = error[0];
                            errorContainer.appendChild(errorItem);
                        });
                    } else if (data.status === 'success') {
                        showToast(data.message);
                        $('#changePasswordModal').modal('hide');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong. Please try again later.');
                });
        });

        function showToast(message) {
            const toast = document.createElement('div');
            toast.classList.add('custom-toast');
            toast.innerHTML = `
        <i class="fas fa-check-circle"></i>
        <span>${message}</span>
    `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('visible');
            }, 100);

            setTimeout(() => {
                toast.classList.remove('visible');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
            setTimeout(() => {
                location.reload();
            }, 3000);
        }
    });
</script>
@stop