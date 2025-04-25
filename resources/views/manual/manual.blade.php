
@extends('adminlte::page')

@section('title', 'Manual de Usuario - Vacaciones')

@section('content_header')
    <h1 class="page-title">Manual de Usuario - Vacaciones</h1>
    <a style="background-color: #094080" href="{{ route('menu.responsable') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver al Menú
    </a>
@stop

@section('content')
    <div class="markdown-content">
        {!! $htmlContent !!}
    </div>
@stop

@section('css')
    <style>
        .page-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #001B71;
            text-align: center;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
        }
        .page-title::after {
            content: '';
            width: 100px;
            height: 4px;
            background-color: #8BB8E3;
            display: block;
            margin: 10px auto 0;
            border-radius: 2px;
        }
        .markdown-content {
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .markdown-content h1, .markdown-content h2, .markdown-content h3 {
            color: #001B71;
        }
        .markdown-content p {
            line-height: 1.6;
        }
    </style>
@stop
