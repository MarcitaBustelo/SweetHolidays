@extends('adminlte::page')

@section('title', 'Absences Calendar')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 style="color: #51055b;;">
        <i style="color: #51055b;" class="fas fa-calendar-alt mr-2"></i>Calendar to Check Absences per Day
    </h1>
    <div>
        <a style="background-color: #51055b; color: white;" href="{{ route('menu.responsable') }}" class="btn">
            <i class="fas fa-arrow-left"></i> Back to Menu
        </a>
    </div>
</div>
@stop

@section('content')
@php
    $specialAccessEmployeeIds = ['10332', '10342'];
    $hasSpecialAccess = in_array(Auth::user()->employee_id, $specialAccessEmployeeIds);
@endphp

@if ($hasSpecialAccess)
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> You have full access to all absences.
    </div>
@endif
<div class="card shadow-sm" style="background-color: #f8f9fa; border-color: #7f4cdb;">
    <div class="card-header" style="background-color: #51055b; border-bottom-color: #51055b;">
        <h3 class="card-title" style="color: #ffffff;">
            <i class="far fa-calendar mr-2"></i>Absences Calendar
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
        <i class="fas fa-info-circle mr-1"></i> Calendar for visualization
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
        background-color: rgba(81, 5, 91, 0.04) !important;
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
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/en-gb.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        // Initialize the calendar
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'en',
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap',
            firstDay: 1,
            selectable: false,
            editable: false,
            dayMaxEvents: false,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Today',
                month: 'Month',
                week: 'Week',
                day: 'Day'
            },
            events: function (fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: "{{ route('holidays.getByTypeAndDate') }}",
                    method: "GET",
                    data: {
                        start_date: fetchInfo.startStr,
                        end_date: fetchInfo.endStr
                    },
                    success: function (response) {
                        if (response.success) {
                            var events = [];
                            $.each(response.absences, function (date, absencesByType) {
                                $.each(absencesByType, function (type, data) {
                                    if (data.count > 0) {
                                        events.push({
                                            title: type + ' - ' + data.count,
                                            start: date,
                                            color: data.color 
                                        });
                                    }
                                });
                            });
                            successCallback(events);
                        } else {
                            failureCallback('Could not fetch events.');
                        }
                    },
                    error: function () {
                        failureCallback('Error getting events.');
                    }
                });
            }
        });

        calendar.render();
    });
</script>
@stop