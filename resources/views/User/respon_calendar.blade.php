@extends('adminlte::page')

@section('title', 'Absence Calendar')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 style="color: #3b2469;"><i style="color: #3b2469;" class="fas fa-calendar-alt mr-2"></i>Absence Calendar</h1>
    <div>
        <a style="background-color: #3b2469; border-color: #3b2469;" href="{{ route('menu.responsable') }}"
            class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Menu
        </a>
        @if (auth()->user()->responsable !== null)
            <button class="btn btn-primary ml-2" style="background-color: #3b2469; border-color: #a389d4;"
                onclick="requestHoliday()">
                <i class="fas fa-calendar-plus"></i> Request Absence
            </button>
        @endif
    </div>
</div>
@stop

@section('content')
<div class="card shadow-sm" style="background-color: #f8f9fa; border-color: #dee2e6;">
    <div class="card-header" style="background-color: #3b2469; border-bottom-color: #3b2469;">
        <h3 class="card-title" style="color: #ffffff;">
            <i class="far fa-calendar mr-2"></i>Free Days Overview
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form id="absenceForm">
            <div class="form-group">
                <label for="userSelect">Select a user</label>
                <select id="userSelect" class="form-control">
                    <option value="">-- Choose a user --</option>

                    @if (in_array(Auth::user()->employee_id, $specialAccessEmployeeIds))
                        @foreach ($users->groupBy('delegation.name') as $delegationName => $usersByDelegation)
                            <optgroup label="{{ $delegationName ?? 'No delegation' }}">
                                @foreach ($usersByDelegation->groupBy('department.name') as $departmentName => $usersByDepartment)
                                    <optgroup label="&nbsp;&nbsp;&nbsp;{{ $departmentName ?? 'No department' }}">
                                        @foreach ($usersByDepartment as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </optgroup>
                        @endforeach
                    @else
                        @foreach ($users->groupBy('delegation.name') as $delegationName => $usersByDelegation)
                            <optgroup label="{{ $delegationName ?? 'No delegation' }}">
                                @foreach ($usersByDelegation->groupBy('department.name') as $departmentName => $usersByDepartment)
                                    <optgroup label="&nbsp;&nbsp;&nbsp;{{ $departmentName ?? 'No department' }}">
                                        @foreach ($usersByDepartment as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </optgroup>
                        @endforeach
                    @endif

                    @if (Auth::user()->role === 'responsable' && Auth::user()->responsable === null)
                        <optgroup label="--- You ---">
                            <option value="{{ Auth::id() }}">{{ Auth::user()->name }}</option>
                        </optgroup>
                    @endif
                </select>
            </div>
        </form>
        <div id="calendar" class="fc-theme-bootstrap"></div>
    </div>
    <div class="card-footer text-muted text-right small">
        <i class="fas fa-info-circle mr-1"></i> Click on any day to request an absence
    </div>
</div>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.bootstrap5.min.css">
<style>
    .fc-daygrid-event {
        border-radius: 4px;
        font-size: 0.85em;
    }

    .fc-event-title {
        font-weight: 500;
    }

    .fc-toolbar-title {
        font-size: 1.5em;
        color: #3b2469;
    }

    .fc-button-active {
        background-color: #3b2469;
    }

    .fc-day-today {
        background-color: #f4eaf7 !important;
    }

    .fc-daygrid-day-number {
        font-size: 1.1em;
        font-weight: 500;
    }

    .selectize-control .selectize-input {
        padding: 0.375rem 0.75rem;
        min-height: 38px;
    }

    .selectize-dropdown {
        z-index: 1060 !important;
    }

    .fc-day-sat,
    .fc-day-sun {
        background-color: rgba(172, 146, 205, 0.15) !important;
    }
</style>
@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializar Selectize
        $('#userSelect').selectize({
            placeholder: "Pick a user...",
            allowEmptyOption: true,
            create: false,
            sortField: 'text'
        });
        var holidayTypes = @json($holiday_types);
        var holidays = @json($holidays);
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'en',
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap',
            firstDay: 1,
            selectable: true,
            editable: true,
            dayMaxEvents: false,
            dayMaxEventRows: false,
            height: 'auto',
            contentHeight: 'auto',
            expandRows: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                prev: '<',
                next: '>',
                today: 'Today',
                month: 'Month',
                week: 'Week',
                day: 'Day'
            },
            events: holidays.map(holiday => ({
                title: `${holiday.holiday_type} - ${holiday.employee.name} - ${holiday.employee.delegation} - ${holiday.employee.department}`,
                start: holiday.start_date,
                end: holiday.end_date ? new Date(new Date(holiday.end_date).getTime() +
                    86400000).toISOString().split('T')[0] : null,
                allDay: true,
                color: holiday.color,
                textColor: "#f5f6fd",
                extendedProps: {
                    holiday_id: holiday.id,
                    employee_id: holiday.employee.id
                }
            })),
            select: async function (arg) {
                const userSelect = document.getElementById('userSelect');
                const userId = userSelect.value;

                if (!userId) {
                    Swal.fire('Error', 'You must select a user before adding an absence.',
                        'error');
                    calendar.unselect();
                    return;
                }

                const inputOptions = {};
                holidayTypes.forEach(type => {
                    inputOptions[type.id] = type.type;
                });

                const {
                    value: absenceType
                } = await Swal.fire({
                    title: "Select the type of absence.",
                    input: 'select',
                    inputOptions: inputOptions,
                    inputPlaceholder: 'Select absence type',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                });

                if (absenceType) {
                    const selectedType = holidayTypes.find(type => type.id == absenceType);
                    let title = selectedType.type;
                    let color = selectedType.color || "#f5f6fd";

                    calendar.addEvent({
                        title: `${title} - Usuario ${userId}`,
                        start: arg.start,
                        end: arg.end,
                        allDay: arg.allDay,
                        color: color,
                        textColor: "#f5f6fd"
                    });


                    const data = {
                        employee_id: userId,
                        start_date: new Date(arg.start.getTime() + arg.start
                            .getTimezoneOffset() * 60000).toISOString().split('T')[0],
                        end_date: arg.end ? new Date(arg.end.getTime() + arg.end
                            .getTimezoneOffset() * 60000).toISOString().split('T')[0] :
                            null,
                        holiday_id: absenceType,
                        _token: '{{ csrf_token() }}'
                    };

                    try {
                        const response = await fetch('{{ route('holiday.assign') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        });

                        const result = await response.json();
                        if (response.ok) {
                            Swal.fire('¡Guardado!', 'La ausencia ha sido registrada correctamente.',
                                'success')
                                .then(() => {
                                    location.reload();
                                });
                        } else {
                            console.error(result.error);
                            Swal.fire('Error', result.error || 'No se pudo guardar la ausencia.',
                                'error').then(() => {
                                    location.reload();
                                });
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Ocurrió un error al guardar la ausencia.', 'error')
                            .then(() => {
                                location.reload();
                            });
                    }
                }
                calendar.unselect();
            },
            eventClick: async function (arg) {
                Swal.fire({
                    title: 'Detalles de la ausencia',
                    html: `<div class="text-left">
            <p><strong>Tipo:</strong> ${arg.event.title}</p>
            <p><strong>Fecha:</strong> ${arg.event.start.toLocaleDateString('es-ES')} ${arg.event.end ? ` - ${arg.event.end.toLocaleDateString('es-ES')}` : ''}</p>
        </div>`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cerrar',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(
                                '{{ route('holidays.delete') }}', {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    holiday_id: arg.event.extendedProps
                                        .holiday_id
                                }),
                            });

                            const result = await response.json();

                            if (response.ok) {
                                arg.event.remove();
                                Swal.fire('¡Eliminado!',
                                    'La ausencia ha sido eliminada.', 'success');
                            } else {
                                console.error(result.error);
                                Swal.fire('Error', result.error ||
                                    'No se pudo eliminar la ausencia.', 'error');
                            }
                        } catch (error) {
                            console.error(error);
                            Swal.fire('Error',
                                'Ocurrió un error al eliminar la ausencia.', 'error'
                            );
                        }
                    }
                });
            },
            eventDrop: async function (info) {
                const {
                    event
                } = info;

                try {
                    const response = await fetch('{{ route('holiday.update') }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            holiday_id: event.extendedProps.holiday_id,
                            start_date: event.start.toISOString().split('T')[0],
                            end_date: event.end ? event.end.toISOString().split(
                                'T')[0] : null,
                        }),
                    });

                    const result = await response.json();

                    if (response.ok) {
                        Swal.fire('¡Actualizado!', 'La ausencia ha sido actualizada correctamente.',
                            'success');
                    } else {
                        console.error(result.error);
                        Swal.fire('Error', result.error || 'No se pudo actualizar la ausencia.',
                            'error');
                        info.revert();
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('Error', 'Ocurrió un error al actualizar la ausencia.', 'error');
                    info.revert();
                }
            },
            eventResize: async function (info) {
                const {
                    event
                } = info;

                try {
                    const response = await fetch('{{ route('holiday.update') }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            holiday_id: event.extendedProps.holiday_id,
                            start_date: event.start.toISOString().split('T')[0],
                            end_date: event.end ? event.end.toISOString().split(
                                'T')[0] : null,
                        }),
                    });

                    const result = await response.json();

                    if (response.ok) {
                        Swal.fire('¡Actualizado!', 'La ausencia ha sido actualizada correctamente.',
                            'success');
                    } else {
                        console.error(result.error);
                        Swal.fire('Error', result.error || 'No se pudo actualizar la ausencia.',
                            'error');
                        info.revert();
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('Error', 'Ocurrió un error al actualizar la ausencia.', 'error');
                    info.revert();
                }
            },
        });
        calendar.render();
    });

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