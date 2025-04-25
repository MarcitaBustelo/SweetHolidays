@extends('adminlte::page')

@section('title', 'Calendario de Usuario')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 style="color: #094080;"><i style="color: #094080;" class="fas fa-calendar-alt mr-2"></i>Calendario de Ausencias
        </h1>
        <div>
            <a style="background-color: #094080" href="{{ route('menu.responsable') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Menú
            </a>
            <button class="btn btn-primary ml-2" style="background-color: #3c8dbc; border-color: #3c8dbc;"
                onclick="requestHoliday()">
                <i class="fas fa-calendar-plus"></i> Pedir Ausencia
            </button>
        </div>
    </div>
@stop

@section('content')
    <div class="card shadow-sm" style="background-color: #f8f9fa; border-color: #dee2e6;">
        <div class="card-header" style="background-color: #094080; border-bottom-color: #094080;">
            <h3 class="card-title" style="color: #ffffff;">
                <i class="far fa-calendar mr-2"></i>Ausencias
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="calendar" class="fc-theme-bootstrap"></div>
        </div>
        <div class="card-footer text-muted text-right small">
            <i class="fas fa-info-circle mr-1"></i> Calendario de visualización
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .fc-daygrid-event {
            border-radius: 4px;
            font-size: 0.85em;
            cursor: default !important;
        }

        .fc-event-title {
            font-weight: 500;
        }

        .fc-toolbar-title {
            font-size: 1.5em;
            color: #3c8dbc;
        }

        .fc-button-primary {
            background-color: #3c8dbc;
            border-color: #367fa9;
        }

        .fc-button-primary:hover {
            background-color: #367fa9;
        }

        .fc-button-active {
            background-color: #094080;
        }

        .fc-day-today {
            background-color: #fff8e1 !important;
        }

        .fc-daygrid-day-number {
            font-size: 1.1em;
            font-weight: 500;
        }

        .fc-event {
            cursor: default !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            // Obtén las ausencias del usuario autenticado desde PHP
            var holidays = @json($holidays);

            // Inicializa el calendario
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap',
                firstDay: 1,
                selectable: false,
                editable: false,
                dayMaxEvents: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                },
                events: holidays.map(holiday => ({
                    title: holiday.holiday_type ? holiday.holiday_type.type : 'Ausencia',
                    start: holiday.start_date,
                    end: holiday.end_date ? new Date(new Date(holiday.end_date).getTime() +
                        86400000).toISOString().split('T')[0] : null, // Ajusta la fecha de fin
                    allDay: true,
                    color: holiday.holidayType ? holiday.holidayType.color :
                    '#094080', // Usa el color del tipo de ausencia
                    textColor: '#fff' // Color del texto
                })),
            });

            calendar.render();
        });

        // Función para el botón "Pedir Ausencia"
        function requestHoliday() {
            Swal.fire({
                title: 'Pedir Ausencia',
                html: `
                    <form id="requestHolidayForm">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="reason">Razón de la Ausencia</label>
                            <textarea id="reason" name="reason" class="form-control" rows="4" placeholder="Razón de la ausencia" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Fecha de Inicio</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">Fecha de Fin</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" required>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Enviar Solicitud',
                preConfirm: () => {
                    const form = document.getElementById('requestHolidayForm');
                    const formData = new FormData(form);

                    return fetch('{{ route('holiday_types.send_email', ['id' => Auth::id()]) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message || 'Error desconocido');
                            }
                            return data;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Error: ${error.message}`
                            );
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('¡Enviado!', 'Tu solicitud de ausencia ha sido enviada.', 'success');
                }
            });
        }
    </script>
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