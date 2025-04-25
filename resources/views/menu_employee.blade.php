@extends('adminlte::page')

@section('title', 'Menú de Empleados')

@section('content_header')
    <h1 style="color: #0e0c5e;">Menú de Empleados</h1>
@stop

@section('content')
    <div class="row">
        <!-- Tarjeta de Mi Calendario -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm" style="background-color: #f0f7ff; border-color: #d1e3f6;">
                <div class="card-header" style="background-color: #d1e3f6; border-bottom-color: #a8c4e8;">
                    <h3 class="card-title" style="color: #0e0c5e;">
                        <i class="fas fa-calendar-alt mr-2"></i>Mi Calendario
                    </h3>
                </div>
                <div class="card-body">
                    <p style="color: #0e0c5e;">Consulta y administra tu calendario personal.</p>
                    <a href="{{ route('user.calendar') }}" class="btn"
                        style="background-color: #7c9ed1; color: white; border: none;">
                        <i class="fas fa-calendar-alt mr-2"></i> Ver Calendario
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Mi Perfil -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm" style="background-color: #f0f7ff; border-color: #d1e3f6;">
                <div class="card-header" style="background-color: #d1e3f6; border-bottom-color: #a8c4e8;">
                    <h3 class="card-title" style="color: #0e0c5e;">
                        <i class="fas fa-user mr-2"></i>Mi Perfil
                    </h3>
                </div>
                <div class="card-body">
                    <p style="color: #0e0c5e;">Consulta y actualiza tu información personal.</p>
                    <a href="{{ route('user.profile') }}" class="btn"
                        style="background-color: #7c9ed1; color: white; border: none;">
                        <i class="fas fa-user mr-2"></i>
                        Ver Perfil
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Crear Ausencia -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm" style="background-color: #f0f7ff; border-color: #d1e3f6;">
                <div class="card-header" style="background-color: #d1e3f6; border-bottom-color: #a8c4e8;">
                    <h3 class="card-title" style="color: #0e0c5e;">
                        <i class="fas fa-calendar-plus mr-2"></i>Solicitar Ausencia
                    </h3>
                </div>
                <div class="card-body">
                    <p style="color: #0e0c5e;">Solicitar ausencias laborales.</p>
                    <a href="#" class="btn solicitar-ausencia-btn" style="background-color: #7c9ed1; color: white; border: none;">
                        <i class="fas fa-calendar-check mr-2"></i> Solicita Ausencia
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card {
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            opacity: 0.9;
        }

        body {
            background-color: #f8fafc;
        }

        .content-header h1 {
            border-bottom: 2px solid #d1e3f6;
            padding-bottom: 10px;
            display: inline-block;
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
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Menú de empleados cargado correctamente.');

            // Evento para el botón de solicitar ausencia
            document.querySelector('.solicitar-ausencia-btn').addEventListener('click', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Solicitar Ausencia',
                    html: `
                        <form id="solicitud-ausencia-form">
                            <div class="form-group">
                                <label for="name">Nombre:</label>
                                <input type="text" id="name" class="form-control" value="{{ auth()->user()->name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="reason">Razón:</label>
                                <textarea id="reason" class="form-control" placeholder="Motivo de ausencia" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="start_date">Fecha de inicio:</label>
                                <input type="date" id="start_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="end_date">Fecha de fin:</label>
                                <input type="date" id="end_date" class="form-control" required>
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Enviar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: () => {
                        const reason = document.getElementById('reason').value;
                        const start_date = document.getElementById('start_date').value;
                        const end_date = document.getElementById('end_date').value;

                        if (!reason || !start_date || !end_date) {
                            Swal.showValidationMessage('Por favor, completa todos los campos.');
                            return false;
                        }

                        return { 
                            name: document.getElementById('name').value, 
                            reason, 
                            start_date, 
                            end_date 
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = result.value;

                        // Enviar la solicitud al backend
                        fetch(`{{ route('holiday_types.send_email', auth()->id()) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Éxito', data.message, 'success');
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Ocurrió un error al enviar la solicitud.', 'error');
                        });
                    }
                });
            });
        });
    </script>
@stop