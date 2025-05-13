@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_body')
    <a href="{{ url('/') }}" class="back-corner-logo">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="logo-section">
        <img src="{{ asset('images/Galleta_logo.png') }}" alt="SweetHoliday Logo">
        <h2>SWEET HOLIDAYS</h2>
    </div>

    <div class="calendar-section" style="border-radius: 20px;">
        <div class="month">{{ ucfirst(now()->locale('en')->monthName) }} {{ now()->year }}</div>
        <div class="weekdays">
            <span>M</span>
            <span>T</span>
            <span>W</span>
            <span>T</span>
            <span>F</span>
        </div>
    </div>

    <form class="form-section" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="input-field" style="border-radius: 10px;">
            <i class="fas fa-id-card"></i>
            <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id') }}"
                placeholder="Employee ID" required autofocus>
        </div>
        @error('employee_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
        <span id="employee_id_error" class="text-danger" style="display: none;">
            Employee ID must contain only numbers.
        </span>

        <div class="input-field" style="border-radius: 10px;">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        @error('password')
            <span class="text-danger">{{ $message }}</span>
        @enderror

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <button type="submit" class="submit-btn" style="border-radius: 10px;">
            <i class="fas fa-sign-in-alt"></i> Log In
        </button>

        @if (Route::has('password.request'))
            <div class="forgot-password-link">
                <a href="{{ route('password.request') }}">Forgot your password?</a>
            </div>
        @endif
    </form>
@endsection

@section('css')
    <style>
        body {
            background: linear-gradient(rgba(56, 7, 76, 0.9), rgba(56, 7, 76, 0.9)),
                url('{{ asset('images/logo.png') }}') no-repeat center center fixed;
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
            color: #38074C;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            z-index: 1000;
        }

        .bayport-corner-logo:hover {
            color: #4d0b63;
            text-decoration: none;
        }

        .card {
            border-top: none !important;
            box-shadow: none !important;
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
            color: #38074C;
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
            color: #38074C;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .weekdays {
            display: flex;
            justify-content: space-around;
            color: #38074C;
        }

        .input-field {
            display: flex;
            align-items: center;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
            margin-bottom: 15px;
        }

        .input-field i {
            color: #38074C;
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
            background: #38074C;
            color: white;
            padding: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: #4d0b63;
        }

        .footer-link {
            text-align: center;
            margin-top: 20px;
        }

        .footer-link a {
            color: #38074C;
            font-size: 14px;
            text-decoration: none;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }

        .forgot-password-link {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-password-link a {
            color: #38074C;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password-link a:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const employeeInput = document.getElementById('employee_id');
            const form = document.querySelector('.form-section');
            const errorMessage = document.getElementById('employee_id_error');

            employeeInput.addEventListener('input', function () {
                if (!/^\d*$/.test(employeeInput.value)) {
                    errorMessage.style.display = 'block';
                } else {
                    errorMessage.style.display = 'none';
                }
            });

            form.addEventListener('submit', function (event) {
                if (!/^\d+$/.test(employeeInput.value)) {
                    event.preventDefault();
                    errorMessage.style.display = 'block';
                }
            });
        });
    </script>
@endsection