<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Holidays</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('{{ asset('storage/images/logo.png') }}');
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        nav {
            background-color: rgba(113, 23, 141, 0.9);
            backdrop-filter: blur(5px);
            padding: 1rem 2rem;
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
            gap: 2rem;
        }

        footer {
            background-color: rgba(113, 23, 141, 0.9);
            text-align: center;
            padding: 1rem;
            color: white;
        }

        .card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 1rem;
            padding: 2rem;
            width: 450px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .card h2 {
            font-size: 2.2rem;
            color: rgba(113, 23, 141, 0.9);
            margin-bottom: 1rem;
            font-family: 'Georgia', serif;
        }

        .cta-button {
            background-color: rgba(113, 23, 141, 0.9);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 0.5rem;
            font-size: 1.2rem;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            background-color: rgba(90, 19, 120, 1);
            transform: scale(1.05);
        }

        .cta-button:active {
            transform: scale(0.98);
        }

        .highlight {
            font-family: 'Georgia', serif;
            font-size: 1.5rem;
            color: rgba(90, 19, 120, 1);
            font-weight: bold;
        }

        .decorative-line {
            width: 80%;
            height: 3px;
            background: linear-gradient(90deg, rgba(113, 23, 141, 1) 0%, rgba(255, 255, 255, 1) 100%);
            margin: 1rem auto;
            border-radius: 1px;
        }
    </style>
</head>

<body>
    <nav class="p-5">
        <div class="flex flex-col md:flex-row md:justify-between items-center">
            <div class="text-3xl font-bold text-white mb-4 md:mb-0 text-center md:text-left">
                Sweet Holidays
            </div>

            <ul class="flex space-x-0 md:space-x-6 text-white font-semibold flex-col md:flex-row items-center">
                <li>
                    <a href="/login" class="hover:text-blue-300 px-2 py-1 text-center block">Sign In</a>
                </li>
            </ul>
        </div>
    </nav>

    <main>
        <div class="card">
            <h2>Welcome to Sweet Holidays!</h2>
            <p class="highlight">Where every day feels like a treat.</p>
            <div class="decorative-line"></div>
            <p>
                Manage vacations and absences effortlessly. Your journey starts here.
            </p>
        </div>
    </main>

    <footer>
        <p>© 2025 Sweet Holidays | Made with <span class="text-red-500">♥</span> for all sweet lovers</p>
    </footer>
</body>

</html>