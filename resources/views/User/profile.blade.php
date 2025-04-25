@extends('adminlte::page')

@section('title', 'Perfil de Usuario')

@section('content_header')
    <h1 style="color: #2c5a8a;"><i class="fas fa-user-circle mr-2"></i>Mi Perfil</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Tarjeta principal -->
            <div class="card shadow-lg" style="border-color: #a8c4e8;">
                <div class="card-header" style="background: linear-gradient(135deg, #d1e3f6 0%, #a8c4e8 100%);">
                    <h3 class="card-title" style="color: #2c5a8a;">
                        <i class="fas fa-id-card mr-2"></i>Información Personal
                    </h3>
                </div>
                <div class="card-body" style="background-color: #f0f7ff;">
                    <div class="row">
                        <!-- Columna izquierda - Datos personales -->
                        <div class="col-md-6">
                            <h4 class="text-center" style="color: #3a6ea5;">{{ $user->name }}</h4>
                            <p class="text-center mb-4" style="color: #4a6b9b;">
                                <i class="fas fa-building mr-1"></i> {{ $user->delegation->name ?? 'Sin delegación' }}<br>
                                <i class="fas fa-users mr-1"></i> {{ $user->department->name ?? 'Sin departamento' }}
                            </p>

                            <hr style="border-color: #d1e3f6;">
                        </div>
                        <div class="col-md-6">
                            <div class="card" style="border-color: #8ab4e0; background-color: #e8f2ff;">
                                <div class="card-header"
                                    style="background: linear-gradient(135deg, #bbdefb 0%, #8ab4e0 100%);">
                                    <h3 class="card-title" style="color: #2c5a8a;">
                                        <i class="fas fa-umbrella-beach mr-2"></i>Mis Vacaciones
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <!-- Barra de progreso -->
                                    <div class="progress mb-3" style="background-color: #e0f2f1;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ ($remainingDays / $user->days) * 100 }}%; background-color: #5a8fd1;"
                                            aria-valuenow="{{ $remainingDays }}" aria-valuemin="0"
                                            aria-valuemax="{{ $user->days }}">
                                            <span class="progress-text">{{ round(($remainingDays / $user->days) * 100) }}%
                                                Disponible</span>
                                        </div>
                                    </div>
                                    <div class="stats" style="background-color: #e1f0ff;">
                                        <div class="stat-item">
                                            <span class="stat-label">Días Totales:</span>
                                            <span class="stat-value badge"
                                                style="background-color: #4a89dc; color: white;">{{ $user->days_in_total }}</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">Días Restantes:</span>
                                            <span class="stat-value badge"
                                                style="background-color: #3a7bd5; color: white;">{{ $remainingDays }}</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">Ausencias:</span>
                                            <span class="stat-value badge"
                                                style="background-color: #6a9bd8; color: white;">{{ $absenceDaysUsed }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <h5 style="color: #3a6ea5;"><i class="far fa-calendar mr-2"></i>Próximas Ausencias
                                        </h5>
                                        @if (count($upcomingHolidays) > 0)
                                            <ul class="list-group">
                                                @foreach ($upcomingHolidays as $holiday)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center"
                                                        style="background-color: #f5f9ff; border-color: #d1e3f6;">
                                                        {{ $holiday->holiday_type }}
                                                        <span class="badge rounded-pill"
                                                            style="background-color: #a8c4e8; color: #2c5a8a;">
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
                                                style="background-color: #bbdefb; border-color: #8ab4e0; color: #2c5a8a;">
                                                No tienes ausencias programadas
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right" style="background-color: #e8f2ff; border-top-color: #a8c4e8;">
                    <a href="{{ route('menu.employee') }}" class="btn" style="background-color: #4a6b9b; color: white;">
                        <i class="fas fa-arrow-left mr-2"></i>Volver al Menú
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
            color: #4a6b9b;
        }

        .progress {
            height: 25px;
            border-radius: 12px;
            background-color: #e0f2f1;
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
            border-bottom: 1px solid #d1e3f6;
        }

        .stat-label {
            font-weight: 500;
            color: #4a6b9b;
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
            background-color: #e1f0ff;
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
            background-color: #f8fafc;
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
            console.log('Perfil cargado');
        });
    </script>
@stop
