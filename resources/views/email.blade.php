<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Ausencia</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7fb; margin: 0; padding: 0;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <tr>
            <td style="padding: 20px; background-color: #0e0c5e; color: #ffffff; text-align: center; border-radius: 8px 8px 0 0;">
                <h1 style="margin: 0; font-size: 24px;">Solicitud de Ausencia</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; color: #333333; line-height: 1.6;">
                <p>Hola,</p>
                <p>El usuario <strong style="color: #0e0c5e;">{{ $name }}</strong> ha solicitado una ausencia con los siguientes detalles:</p>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
                    <tr>
                        <td style="padding: 10px; background-color: #f0f7ff; border-radius: 4px; color: #333333;">
                            <strong>Razón:</strong> {{ $reason }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; background-color: #f9fafc; border-radius: 4px; color: #333333;">
                            <strong>Fecha de inicio:</strong> {{ $start_date }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; background-color: #f0f7ff; border-radius: 4px; color: #333333;">
                            <strong>Fecha de fin:</strong> {{ $end_date }}
                        </td>
                    </tr>
                </table>
                <p style="margin-top: 20px;">Por favor, revisa y procesa esta solicitud.</p>
                <p style="margin-top: 20px;">Saludos,</p>
                <p style="font-style: italic;">Equipo de Gestión de Ausencias</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; background-color: #f4f7fb; text-align: center; color: #666666; font-size: 12px; border-radius: 0 0 8px 8px;">
                <p style="margin: 0;">Este es un correo automático. Por favor, no respondas a este mensaje.</p>
            </td>
        </tr>
    </table>
</body>
</html>