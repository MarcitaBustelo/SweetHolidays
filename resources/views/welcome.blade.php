<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Vacaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background:
                url('{{ asset('images/Vacaciones.png') }}') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        nav {
            background-color: rgba(23, 60, 141, 0.9);
            backdrop-filter: blur(5px);
        }

        .nav-links a {
            transition: all 0.3s ease;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 1.5rem;
        }

        footer {
            background-color: rgba(23, 60, 141, 0.9);
            text-align: center;
        }

        .card-small {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 1rem;
            padding: 1.5rem;
            width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-small:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .card-large {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 1rem;
            padding: 2rem;
            width: 500px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-large:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .card-small h2,
        .card-large h2 {
            font-size: 1.8rem;
            color:rgba(23, 60, 141, 0.9);/* Azul */
            margin-bottom: 1rem;
        }

        .card-small p,
        .card-large ul {
            color: gray;
            font-size: 1rem;
            line-height: 1.8;
        }

        .card-large ul {
            list-style: none;
            padding: 0;
        }

        .card-large ul li {
            margin: 0.5rem 0;
        }
    </style>
</head>

<body>
    <nav class="p-5">
        <div class="flex flex-col md:flex-row md:justify-between items-center">
            <!-- Logo -->
            <div class="text-2xl font-bold text-white mb-4 md:mb-0 text-center md:text-left">
                Gestión de Vacaciones
            </div>
    
            <!-- Links -->
            <ul class="flex space-x-0 md:space-x-6 text-white font-semibold flex-col md:flex-row items-center">
                <li>
                    <a href="/login" class="hover:text-blue-300 px-2 py-1 text-center block">Iniciar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <main>
        <!-- Tarjeta pequeña -->
        <div class="card-small">
            <h2>¿Para qué sirve?</h2>
            <p>
                Gestiona las vacaciones de manera rápida y sencilla. Permite a empleados y responsables organizar ausencias de forma eficiente.
            </p>
        </div>

        <!-- Tarjeta grande -->
        <div class="card-large">
            <h2>Funciones</h2>
            <ul>
                <li>Consulta fácil de vacaciones.</li>
                <li>Solicita días en segundos.</li>
                <li>Gestión de ausencias.</li>
                <li>Planificación clara.</li>
                <li>Notificaciones instantáneas.</li>
            </ul>
        </div>
    </main>

    <footer class="text-white p-4">
        <p class="text-sm">&copy; 2025 Gestión de Vacaciones. Todos los derechos reservados.</p>
    </footer>
</body>
</html>