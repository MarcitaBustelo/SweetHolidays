@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_body')
    <a href="{{ url('/') }}" class="back-corner-logo">
        <i class="fas fa-arrow-left"></i> <!-- Ícono de flecha hacia atrás -->
    </a>
    <div class="logo-section">
        <img src="{{ asset('images/logotipo_BAYPORT-bicolor-03.png') }}" alt="BayPortal Logo">
        <h2>VACACIONES BAYPORT</h2>
    </div>

    <div class="calendar-section" style="border-radius: 20px;">
        <div class="month">{{ ucfirst(now()->locale('es')->monthName) }} {{ now()->year }}</div>
        <div class="weekdays">
            <span>L</span>
            <span>M</span>
            <span>X</span>
            <span>J</span>
            <span>V</span>
        </div>
    </div>

    <form class="form-section" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="input-field" style="border-radius: 10px;">
            <i class="fas fa-id-card"></i>
            <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id') }}"
                placeholder="Número de Empleado" required autofocus>
        </div>
        @error('employee_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
        <span id="employee_id_error" class="text-danger" style="display: none;">El número de empleado solo puede contener
            números.</span>

        <div class="input-field" style="border-radius: 10px;">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Contraseña" required>
        </div>
        @error('password')
            <span class="text-danger">{{ $message }}</span>
        @enderror

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Recordarme</label>
        </div>

        <button type="submit" class="submit-btn" style="border-radius: 10px;">
            <i class="fas fa-sign-in-alt"></i> Iniciar sesión
        </button>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        @endif
    </form>
@endsection

@section('css')
    <style>
        body {
            background: linear-gradient(rgba(0, 27, 113, 0.9), rgba(0, 27, 113, 0.95)),
                url('{{ asset('images/Diseño Bayportal.png') }}') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            position: relative;
        }

        .bayport-corner-logo {
            position: fixed;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            z-index: 1000;
        }

        .bayport-corner-logo:hover {
            color: #f0f0f0;
            text-decoration: none;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo-section img {
            width: 100px;
            margin-bottom: 10px;
        }

        .logo-section h2 {
            color: #001B71;
            font-size: 22px;
            margin: 0;
            font-weight: 600;
        }

        .calendar-section {
            background: #f5f8ff;
            padding: 15px;
            margin-bottom: 25px;
            text-align: center;
        }

        .month {
            color: #001B71;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .weekdays {
            display: flex;
            justify-content: space-around;
            color: #001B71;
        }

        .input-field {
            display: flex;
            align-items: center;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
            margin-bottom: 15px;
        }

        .input-field i {
            color: #001B71;
            margin-right: 10px;
        }

        .input-field input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 14px;
        }

        .submit-btn {
            width: 100%;
            background: #001B71;
            color: white;
            padding: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: #002699;
        }

        .footer-link {
            text-align: center;
            margin-top: 20px;
        }

        .footer-link a {
            color: #001B71;
            font-size: 14px;
            text-decoration: none;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const employeeInput = document.getElementById('employee_id');
            const form = document.querySelector('.form-section'); // Selecciona el formulario por su clase
            const errorMessage = document.getElementById('employee_id_error'); // Mensaje de error existente

            employeeInput.addEventListener('input', function() {
                // Validar si el input contiene solo números
                if (!/^\d*$/.test(employeeInput.value)) {
                    errorMessage.style.display = 'block'; // Mostrar mensaje de error
                } else {
                    errorMessage.style.display = 'none'; // Ocultar mensaje de error
                }
            });

            form.addEventListener('submit', function(event) {
                // Evitar envío si el campo no es válido
                if (!/^\d+$/.test(employeeInput.value)) {
                    event.preventDefault(); // Bloquear el envío del formulario
                    errorMessage.style.display = 'block'; // Asegurar que el mensaje de error esté visible
                }
            });
        });
    </script>
@endsection
