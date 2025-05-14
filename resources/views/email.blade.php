<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absence Request</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7fb; margin: 0; padding: 0;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <tr>
            <td style="padding: 20px; background-color: #4a148c; color: #ffffff; text-align: center; border-radius: 8px 8px 0 0;">
                <h1 style="margin: 0; font-size: 24px;">Absence Request</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; color: #333333; line-height: 1.6;">
                <p>Hello,</p>
                <p>The user <strong style="color: #4a148c;">{{ $name }}</strong> has submitted an absence request with the following details:</p>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
                    <tr>
                        <td style="padding: 10px; background-color: #f3e5f5; border-radius: 4px; color: #333333;">
                            <strong>Reason:</strong> {{ $reason }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; background-color: #f8f8fc; border-radius: 4px; color: #333333;">
                            <strong>Start Date:</strong> {{ $start_date }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; background-color: #f3e5f5; border-radius: 4px; color: #333333;">
                            <strong>End Date:</strong> {{ $end_date }}
                        </td>
                    </tr>
                </table>
                <p style="margin-top: 20px;">Please review and process this request.</p>
                <p style="margin-top: 20px;">Best regards,</p>
                <p style="font-style: italic;">Sweet Holidays Team</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; background-color: #f4f7fb; text-align: center; color: #666666; font-size: 12px; border-radius: 0 0 8px 8px;">
                <p style="margin: 0;">This is an automated email. Please do not reply to this message.</p>
            </td>
        </tr>
    </table>
</body>
</html>