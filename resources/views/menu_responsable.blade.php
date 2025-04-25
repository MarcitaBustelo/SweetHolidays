@extends('adminlte::page')

@section('title', 'Menú de Responsables')

@section('content_header')
    <h1 style="color: #0e0c5e;">Menú de Responsables</h1>
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
                    <a href="{{ route('user.respon_calendar') }}" class="btn"
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

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm" style="background-color: #f0f7ff; border-color: #d1e3f6;">
                <div class="card-header" style="background-color: #d1e3f6; border-bottom-color: #a8c4e8;">
                    <h3 class="card-title" style="color: #0e0c5e;">
                        <i class="fas fa-calendar-plus mr-2"></i>Gestionar Tipos de Ausencia
                    </h3>
                </div>
                <div class="card-body">
                    <p style="color: #0e0c5e;">Gestiona los tipos de ausencias laborales.</p>
                    <button onclick="openCreateHolidayType()" class="btn"
                        style="background-color: #7c9ed1; color: white; border: none; font-size: 0.875rem; padding: 6px 12px; border-radius: 6px;">
                        <i class="fas fa-calendar-plus mr-1"></i> Crear Nuevo Tipo
                    </button>
                    <button onclick="viewHolidayTypes()" class="btn"
                        style="background-color: #7c9ed1; color: white; border: none; font-size: 0.875rem; padding: 6px 12px; border-radius: 6px;">
                        <i class="fas fa-list mr-1"></i> Ver Tipos Existentes
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm" style="background-color: #f0f7ff; border-color: #d1e3f6;">
                <div class="card-header" style="background-color: #d1e3f6; border-bottom-color: #a8c4e8;">
                    <h3 class="card-title" style="color: #0e0c5e;">
                        <i class="fas fa-user mr-2"></i>Gestionar Usuarios
                    </h3>
                </div>
                <div class="card-body">
                    <p style="color: #0e0c5e;">Consulta tus empleados</p>
                    <a href="{{ route('user.users') }}" class="btn"
                        style="background-color: #7c9ed1; color: white; border: none;">
                        <i class="fas fa-user mr-2"></i>
                        Ver Usuarios
                    </a>
                </div>
            </div>
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

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Función para abrir el popup de Crear Tipo de Ausencia
        function openCreateHolidayType() {
            Swal.fire({
                title: 'Crear Nuevo Tipo de Ausencia',
                html: `
                    <form id="createHolidayTypeForm">
                        <div class="form-group">
                            <label for="type">Nombre del Tipo</label>
                            <input type="text" id="type" name="type" class="form-control" placeholder="Ejemplo: Vacaciones" required>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Crear',
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
                                        'Error al procesar la solicitud');
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
                    Swal.fire('¡Tipo de ausencia creado!', '', 'success');
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
                            Eliminar
                        </button>
                    </li>
                `;
                    });
                    typesHtml += '</ul>';

                    Swal.fire({
                        title: 'Tipos de Ausencia',
                        html: typesHtml,
                        showCloseButton: true,
                        showConfirmButton: false
                    });
                })
                .catch(error => {
                    Swal.fire('Error', 'No se pudieron cargar los tipos de ausencia.', 'error');
                });
        }

        function deleteHolidayType(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
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
                                Swal.fire('¡Eliminado!', data.message, 'success');
                                viewHolidayTypes(); // Recargar la lista
                            } else {
                                Swal.fire('Error', 'No se pudo eliminar el tipo de ausencia.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Ocurrió un error al eliminar el tipo de ausencia.', 'error');
                        });
                }
            });
        }
    </script>
@stop
