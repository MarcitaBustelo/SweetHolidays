<!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>BayPortal</title>
     <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
     <style>
         /* Fondo profesional */
         .background-container {
             position: fixed;
             top: 0;
             left: 0;
             width: 100%;
             height: 100%;
             background: linear-gradient(rgba(0, 27, 113, 0.9), rgba(0, 27, 113, 0.95)), 
                         url('{{ asset('images/Diseño Bayportal.png') }}') no-repeat center center fixed;
             background-size: cover;
             z-index: -1;
         }
 
         /* Contenedor principal */
         .reset-container {
             position: relative;
             max-width: 420px;
             margin: 5% auto;
             padding: 40px;
             background: #ffffff;
             border-radius: 8px;
             box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
         }
 
         /* Encabezado corporativo */
         .company-branding {
             text-align: center;
             margin-bottom: 35px;
         }
 
         .company-branding .logo {
             width: 120px;
             margin-bottom: 20px;
         }
 
         .company-name {
             font-family: 'Segoe UI', sans-serif;
             font-size: 28px;
             font-weight: 600;
             color: #001B71;
             letter-spacing: 0.5px;
             margin: 10px 0;
         }
 
         .company-slogan {
             font-size: 14px;
             color: #5a5a5a;
             letter-spacing: 0.3px;
         }
 
         /* Estilo del mensaje */
         .reset-message {
             text-align: center;
             font-size: 16px;
             color: #34495e;
             margin-top: 20px;
             line-height: 1.5;
         }
 
         .contact-info {
             text-align: center;
             margin-top: 20px;
             font-size: 14px;
             color: #7f8c8d;
         }
 
         .contact-info a {
             color: #001B71;
             text-decoration: none;
             font-weight: bold;
         }
 
         .contact-info a:hover {
             color: #002699;
         }
 
         .login-link {
             text-align: center;
             margin-top: 20px;
         }
 
         .login-link a {
             display: inline-block;
             padding: 10px 20px;
             background-color: #001B71;
             color: #ffffff;
             text-decoration: none;
             border-radius: 5px;
             font-size: 14px;
             font-weight: bold;
             transition: background-color 0.3s ease;
         }
 
         .login-link a:hover {
             background-color: #002699;
         }
 
         /* Responsive design */
         @media (max-width: 576px) {
             .reset-container {
                 margin: 10% 15px;
                 padding: 25px;
             }
         }
     </style>
 </head>
 <body>
     <div class="background-container"></div>
 
     <div class="reset-container">
         <div class="company-branding">
             <img src="{{ asset('images/logotipo_BAYPORT-bicolor-03.png') }}" alt="BayPortal Logo" class="logo">
             <div class="company-name">BAYPORTAL</div>
             <div class="company-slogan">Gestión Logística Integral</div>
         </div>
 
         <div class="reset-message">
             <p>Para restablecer tu contraseña, por favor contacta con el administrador del sistema.</p>
             <p>El administrador será el encargado de realizar el cambio de contraseña por ti.</p>
         </div>
 
         <div class="contact-info">
             <p>Si necesitas ayuda, puedes escribir a:</p>
             <a href="mailto:ja.ceballos@bayportal.com">ja.ceballos@bayport.eu</a>
         </div>
 
         <div class="login-link">
             <a href="{{ route('login') }}">Volver al inicio de sesión</a>
         </div>
     </div>
 </body>
 </html>