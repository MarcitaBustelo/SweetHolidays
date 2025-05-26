@extends('adminlte::page')

@section('title', __('Employee Arrivals'))

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 style="color: #3b2469;">@lang('Employee Arrivals')</h1>
    <a href="{{ route('menu.responsable') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> @lang('Back to Menu')
    </a>
</div>
@stop

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-light">
        <h3 class="card-title" style="font-weight: bold; color: #6a3cc9;">@lang('Arrival Records')</h3>
    </div>
    <div class="card-body bg-white">
        <table id="arrivalsTable" class="table table-bordered table-hover">
            <thead>
                <tr style="background-color: #ebe4f6; color: #4b2e83;">
                    <th>@lang('Employee')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Arrival Time')</th>
                    <th>@lang('Departure Time')</th>
                    <th>@lang('Late')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($arrivals as $arrival)
                    <tr>
                        <td>{{ $arrival->employee_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($arrival->date)->format('Y-m-d') }}</td>
                        <td>{{ $arrival->arrival_time }}</td>
                        <td>{{ $arrival->departure_time ?? '-' }}</td>
                        <td class="text-center">
                            @if ($arrival->late)
                                <span class="text-danger"><i class="fas fa-times-circle"></i></span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">@lang('No arrival records found.')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    body {
        background-color: #f9f9fb;
    }

    table tbody tr:hover {
        background-color: #f5f2fa;
    }

    .text-danger {
        font-size: 1.2rem;
    }
</style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#arrivalsTable').DataTable({
            responsive: true,
            order: [[1, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/English.json'
            }
        });
    });
</script>
@stop
