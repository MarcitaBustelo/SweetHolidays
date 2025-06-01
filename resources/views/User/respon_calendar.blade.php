@extends('adminlte::page')

@section('title', 'Responsable Calendar')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 style="color: rgb(81, 5, 91);"><i style="color: rgb(81, 5, 91);" class="fas fa-calendar-alt mr-2"></i>Absence
        Calendar</h1>
    <div>
        <a style="background-color: rgb(81, 5, 91)" href="{{ route('menu.responsable') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Menu
        </a>
    </div>
</div>
@stop

@section('content')
<div class="card shadow-sm" style="background-color: #f8f9fa; border-color: #dee2e6;">
    <div class="card-header" style="background-color:rgb(81, 5, 91); border-bottom-color: #094080;">
        <h3 class="card-title" style="color: #ffffff;">
            <i class="far fa-calendar mr-2"></i>View of Free Days
        </h3>
        <div class="card-tools">
            <i class="fas fa-circle mr-1" style="color: red;"></i>
            <span style="color: white;">National</span>
            <i class="fas fa-circle ml-3 mr-1" style="color: green;"></i>
            <span style="color: white;">Other</span>
            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form id="absenceForm">
            <div class="form-group">
                <label for="userSelect">Select a User</label>
                <select id="userSelect" class="form-control">
                    <option value="all">-- All Users --</option>
                    <option value="">-- Select a User --</option>
                    @if (in_array(Auth::user()->employee_id, $specialAccessEmployeeIds) || Auth::user()->employee_id === '10032')
                        @foreach ($users->groupBy('delegation.name') as $delegationName => $usersByDelegation)
                            <optgroup label="{{ $delegationName ?? 'No Delegation' }}">
                                @foreach ($usersByDelegation->groupBy('department.name') as $departmentName => $usersByDepartment)
                                    <optgroup label="&nbsp;&nbsp;&nbsp;{{ $departmentName ?? 'No Department' }}">
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
                            <optgroup label="{{ $delegationName ?? 'No Delegation' }}">
                                @foreach ($usersByDelegation->groupBy('department.name') as $departmentName => $usersByDepartment)
                                    <optgroup label="&nbsp;&nbsp;&nbsp;{{ $departmentName ?? 'No Department' }}">
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
        <button id="filterButton" class="btn btn-primary btn-block mt-2"
            style="background-color: rgb(118, 41, 130); border-color: rgb(118, 41, 130);">
            Filter Absences
        </button>
        <div id="calendar" class="fc-theme-bootstrap"></div>
    </div>
    <div class="card-footer text-muted text-right small">
        <i class="fas fa-info-circle mr-1"></i> Click on any day to request absence
    </div>
</div>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.bootstrap5.min.css">

<!-- NUEVOS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
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
        color: rgb(118, 41, 130);
    }

    .fc-button-active {
        background-color: rgb(118, 41, 130);
    }

    .fc-today-button,
    .fc-dayGridMonth-button,
    .fc-timeGridWeek-button,
    .fc-timeGridDay-button {
        background-color: rgb(118, 41, 130) !important;
        border-color: rgb(118, 41, 130) !important;
    }

    .fc-today-button:hover,
    .fc-dayGridMonth-button:hover,
    .fc-timeGridWeek-button:hover,
    .fc-timeGridDay-button:hover {
        background-color: rgb(145, 56, 150) !important;
        border-color: rgb(145, 56, 150) !important;
    }

    .fc-day-today {
        background-color: #f9f9fa !important;
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
        background-color: rgba(54, 9, 73, 0.07) !important;
    }

    .fc-event-title {
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .fc-prev-button,
    .fc-next-button {
        background-color: rgb(118, 41, 130) !important;
        border-color: rgb(118, 41, 130) !important;
        color: white !important;
    }

    .fc-prev-button:hover,
    .fc-next-button:hover {
        background-color: rgb(145, 56, 150) !important;
        border-color: rgb(145, 56, 150) !important;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>

<!-- NUEVOS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#userSelect').selectize({
            placeholder: "Search for a user...",
            allowEmptyOption: true,
            create: false,
            sortField: 'text'
        });
        var holidayTypes = @json($holiday_types);
        var holidays = @json($holidays);
        var festives = @json($festives);
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'en',
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap',
            firstDay: 1,
            selectable: true,
            editable: true,
            dayMaxEvents: false,
            height: 'auto',
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
            events: [
                ...holidays.map(holiday => ({
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
                ...festives.map(festive => ({
                    title: festive.name,
                    start: festive.date,
                    allDay: true,
                    color: festive.national === 1 ? '#d71616' : '#28a745',
                    textColor: '#fff',
                }))
            ],
            select: async function (arg) {
                const userSelect = document.getElementById('userSelect');
                const userId = userSelect.value;


                if (!userId || userId === 'all') {
                    Swal.fire('Error', 'You must select a specific user before adding an absence.', 'error');
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
                    title: "Select the type of absence",
                    input: 'select',
                    inputOptions: inputOptions,
                    inputPlaceholder: 'Select a type',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                });

                if (absenceType) {
                    const selectedType = holidayTypes.find(type => type.id == absenceType);
                    let title = selectedType.type;
                    let color = selectedType.color || "#f5f6fd";

                    calendar.addEvent({
                        title: `${title}`,
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
                            Swal.fire('Saved!', 'The absence has been successfully registered.',
                                'success')
                                .then(() => {
                                    location.reload();
                                });
                        } else {
                            console.error(result.error);
                            Swal.fire('Error', result.error || 'Could not save the absence.',
                                'error').then(() => {
                                    location.reload();
                                });
                        }
                    } catch (error) {
                        Swal.fire('Error', 'An error occurred while saving the absence.', 'error')
                            .then(() => {
                                location.reload();
                            });
                    }
                }
                calendar.unselect();
            },
            eventClick: async function (arg) {
                const holidayId = arg.event.extendedProps.holiday_id;
                const employeeId = arg.event.extendedProps.employee_id;
                const loggedInUserId = {{ Auth::id() }};
                const hasResponsible =
                        {{ auth()->user()->responsable !== null ? 'true' : 'false' }};

                if (employeeId === loggedInUserId && hasResponsible) {
                    return;
                }

                try {
                    const response = await fetch(`/holidays/${holidayId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const holiday = await response.json();

                    if (response.ok) {
                        const holidayTypes =
                            @json($holiday_types); // Asegúrate de tener esto en tu vista

                        const inputOptions = {};
                        holidayTypes.forEach(type => {
                            inputOptions[type.id] = type.type;
                        });

                        const {
                            value: action
                        } = await Swal.fire({
                            title: 'What would yo like to edit?',
                            input: 'radio',
                            inputOptions: {
                                changeType: 'Type of absence',
                                justifyAbsence: 'Justify or delete'
                            },
                            inputValidator: (value) => {
                                if (!value) {
                                    return 'Must select an option';
                                }
                            },
                            confirmButtonText: 'Continue',
                            showCancelButton: true,
                            cancelButtonText: 'Cancel'
                        });

                        if (action === 'changeType') {
                            const formHtml = `
                    <form id="editHolidayFormType">
                        <div class="form-group">
                            <label for="absenceType">Type of absence</label>
                            <select id="absenceType" name="absenceType" class="form-control">
                                ${Object.entries(inputOptions).map(([id, type]) => `
                                        <option value="${id}" ${holiday.holiday_id == id ? 'selected' : ''}>${type}</option>
                                    `).join('')}
                            </select>
                        </div>
                    </form>
                `;
                            Swal.fire({
                                title: 'Change type of absence',
                                html: `
                        <div class="text-left">
                            <p><strong>Tipo:</strong> ${arg.event.title}</p>
                            <p><strong>Fecha:</strong> ${arg.event.start.toLocaleDateString('es-ES')} ${arg.event.end ? ` - ${arg.event.end.toLocaleDateString('es-ES')}` : ''}</p>
                        </div>
                        ${formHtml}
                    `,
                                showCancelButton: true,
                                confirmButtonText: 'Save',
                                cancelButtonText: 'Cancel',
                                confirmButtonColor: '#28a745',
                                cancelButtonColor: '#dc3545',
                                preConfirm: async () => {
                                    const form = document.getElementById(
                                        'editHolidayFormType');
                                    const formData = new FormData(form);
                                    formData.append('holiday_id', holidayId);

                                    try {
                                        const response = await fetch(
                                            '{{ route('holidays.updateType') }}', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: formData
                                        });

                                        const result = await response.json();

                                        if (!response.ok) {
                                            throw new Error(result.message ||
                                                'Unknown error');
                                        }

                                        return result;
                                    } catch (error) {
                                        Swal.showValidationMessage(
                                            `Error: ${error.message}`);
                                    }
                                }
                            }).then(result => {
                                if (result.isConfirmed) {
                                    Swal.fire('Updated!',
                                        'The type of absence has been updated correctly',
                                        'success')
                                        .then(() => location.reload());
                                }
                            });
                        } else if (action === 'justifyAbsence') {
                            const formHtml = `
        <form id="editHolidayForm">
            <div class="form-group">
                <label for="comment">Comment</label>
                <textarea id="comment" name="comment" class="form-control" rows="3" placeholder="Add a comment">${holiday.comment || ''}</textarea>
            </div>
            <div class="form-group">
                <label for="file">Proof</label>
                ${holiday.file ? `<p><strong>Archivo actual:</strong> <a href="/storage/${holiday.file}" target="_blank">Download</a></p>` : ''}
                <input type="file" id="file" name="file" class="form-control" accept=".jpeg,.png,.jpg,.pdf">
            </div>
        </form>
    `;
                            Swal.fire({
                                title: 'Absence Details',
                                html: `
            <div class="text-left">
                <p><strong>Tipo:</strong> ${arg.event.title}</p>
                <p><strong>Fecha:</strong> ${arg.event.start.toLocaleDateString('es-ES')} ${arg.event.end ? ` - ${arg.event.end.toLocaleDateString('es-ES')}` : ''}</p>
            </div>
            ${formHtml}
        `,
                                icon: 'info',
                                showCancelButton: true,
                                confirmButtonText: 'Save',
                                cancelButtonText: 'Delete',
                                denyButtonText: 'Close',
                                confirmButtonColor: '#28a745',
                                cancelButtonColor: '#dc3545',
                                preConfirm: async () => {
                                    const form = document.getElementById('editHolidayForm');
                                    const formData = new FormData(form);
                                    formData.append('holiday_id', holidayId);

                                    try {
                                        const response = await fetch('{{ route('holidays.edit') }}', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: formData
                                        });

                                        const result = await response.json();

                                        if (!response.ok) {
                                            throw new Error(result.message || 'Unknown error');
                                        }

                                        return result;
                                    } catch (error) {
                                        Swal.showValidationMessage(`Error: ${error.message}`);
                                    }
                                }
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    Swal.fire('Saved!', 'The absence has been updated correctly', 'success')
                                        .then(() => location.reload());
                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    try {
                                        const response = await fetch('{{ route('holidays.delete') }}', {
                                            method: 'DELETE',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                holiday_id: holidayId
                                            }),
                                        });

                                        const result = await response.json();

                                        if (response.ok) {
                                            arg.event.remove();
                                            Swal.fire('Deleted!', 'The absence has been eliminated', 'success');
                                        } else {
                                            console.error(result.error);
                                            Swal.fire('Error', result.error || 'Could not delete the absence', 'error');
                                        }
                                    } catch (error) {
                                        console.error(error);
                                        Swal.fire('Error', 'Oops! Something wrong happended while deleten absence', 'error');
                                    }
                                }
                            });
                        }

                    } else {
                        Swal.fire('Error', holiday.error ||
                            'Could not load absence details', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Something went wrong while loading absence details',
                        'error');
                }
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
                        Swal.fire('Updated!', 'The absence has been updated correctly.',
                            'success');
                    } else {
                        console.error(result.error);
                        Swal.fire('Error', result.error || 'Could not update the absence.',
                            'error');
                        info.revert();
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('Error', 'Something wrong happened while updating the absence', 'error');
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
                        Swal.fire('Updated!', 'The absence has been updated correctly.',
                            'success');
                    } else {
                        console.error(result.error);
                        Swal.fire('Error', result.error || 'Could not update the absence.',
                            'error');
                        info.revert();
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('Error', 'Something wrong happened while updating the absence', 'error');
                    info.revert();
                }
            },
        });
        calendar.render();

        filterButton.addEventListener('click', () => {
            const selectedUserId = userSelect.value;

            const festiveEvents = festives.map(festive => ({
                title: festive.name,
                start: festive.date,
                allDay: true,
                color: festive.national === 1 ? '#d71616' : '#28a745',
                textColor: '#fff',
            }));

            if (selectedUserId === 'all') {
                calendar.removeAllEvents();
                calendar.addEventSource([
                    ...holidays.map(holiday => ({
                        title: `${holiday.holiday_type} - ${holiday.employee.name} - ${holiday.employee.delegation} - ${holiday.employee.department}`,
                        start: holiday.start_date,
                        end: holiday.end_date ? new Date(new Date(holiday.end_date)
                            .getTime() + 86400000).toISOString().split('T')[0] :
                            null,
                        allDay: true,
                        color: holiday.color,
                        textColor: "#f5f6fd",
                        extendedProps: {
                            holiday_id: holiday.id,
                            employee_id: holiday.employee.id,
                        },
                    })),
                    ...festives.map(festive => ({
                        title: festive.name,
                        start: festive.date,
                        allDay: true,
                        color: festive.national === 1 ? '#d71616' : '#28a745',
                        textColor: '#fff',
                    })),
                ]);
                return;
            }

            // Si selecciona un usuario específico, filtrar sus eventos
            if (!selectedUserId) {
                Swal.fire('Error', 'You must choose an employee to filter the absences', 'error');
                return;
            }

            const filteredEvents = holidays
                .filter(holiday => holiday.employee.id == selectedUserId)
                .map(holiday => ({
                    title: `${holiday.holiday_type} - ${holiday.employee.name} - ${holiday.employee.delegation} - ${holiday.employee.department}`,
                    start: holiday.start_date,
                    end: holiday.end_date ? new Date(new Date(holiday.end_date).getTime() +
                        86400000).toISOString().split('T')[0] : null,
                    allDay: true,
                    color: holiday.color,
                    textColor: "#f5f6fd",
                    extendedProps: {
                        holiday_id: holiday.id,
                        employee_id: holiday.employee.id,
                    },
                }));

            calendar.removeAllEvents();
            calendar.addEventSource(filteredEvents);
            calendar.addEventSource(festiveEvents);
        });
    });
</script>
@stop