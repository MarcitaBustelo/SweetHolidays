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
                <div class="card-header" style="background: linear-gradient(135deg, #d7bbf3 0%, #c0a6f3 100%);">
                    <h3 class="card-title" style="color: #7f4cdb;">
                        <i class="fas fa-id-card mr-2"></i>Personal Information
                    </h3>
                </div>
                <div class="card-body" style="background-color: #f3e9ff;">
                    <div class="row">
                        <!-- Left Column - Personal Data -->
                        <div class="col-md-6">
                            <h4 class="text-center" style="color: #6a3cc9;">{{ $user->name }}</h4>
                            <p class="text-center mb-4" style="color: #9b5df2;">
                                <i class="fas fa-building mr-1"></i> {{ $user->delegation->name ?? 'No delegation' }}<br>
                                <i class="fas fa-users mr-1"></i> {{ $user->department->name ?? 'No department' }}
                            </p>

                            <hr style="border-color: #d7bbf3;">
                        </div>
                        <div class="col-md-6">
                            <div class="card" style="border-color: #9a74f7; background-color: #e4daf9;">
                                <div class="card-header"
                                    style="background: linear-gradient(135deg, #d7bbf3 0%, #9a74f7 100%);">
                                    <h3 class="card-title" style="color: #7f4cdb;">
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
                                    <div class="stats" style="background-color: #e8d8f7;">
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
                                        <h5 style="color: #6a3cc9;"><i class="far fa-calendar mr-2"></i>Upcoming Absences</h5>
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
                <div class="card-footer text-right" style="background-color: #e4daf9; border-top-color: #c0a6f3;">
                    <a href="{{ route('menu.employee') }}" class="btn" style="background-color: #9b5df2; color: white;">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Menu
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .user-info p {
            margin-bottom: 0.8rem;
            font-size: 1rem;
            color: #9b5df2;
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
            color: #9b5df2;
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
    </style>
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

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Profile Loaded');
        });
    </script>
@stop
