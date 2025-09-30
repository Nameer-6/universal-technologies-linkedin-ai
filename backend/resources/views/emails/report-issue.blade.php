<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; color: #333;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <tr>
            <td style="background-color: #0000ff; border-radius: 8px 8px 0 0; padding: 20px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">New Issue Report</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px;">
                <h2 style="font-size: 20px; color: #333; margin-top: 0;">Hello Support Team,</h2>
                <p>A new issue has been reported. Below are the details:</p>
                
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin: 20px 0;">
                    <tr>
                        <td style="padding: 10px 0; font-weight: bold; width: 120px;">Name:</td>
                        <td style="padding: 10px 0;">{{ $data['name'] }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; font-weight: bold; width: 120px;">Email:</td>
                        <td style="padding: 10px 0;">
                            <a href="mailto:{{ $data['email'] }}" style="color: #0000ff; text-decoration: none;">{{ $data['email'] }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; font-weight: bold; width: 120px;">Message:</td>
                        <td style="padding: 10px 0;">{{ $data['message'] }}</td>
                    </tr>
                    @if($data['image_path'])
                    <tr>
                        <td style="padding: 10px 0; font-weight: bold; width: 120px;">Screenshot:</td>
                        <td style="padding: 10px 0;">Attached below</td>
                    </tr>
                    @endif
                </table>

                <p style="margin: 20px 0;">Please review the details and take appropriate action. If you need to contact the user, you can reply to their email directly.</p>

                <a href="mailto:{{ $data['email'] }}" style="display: inline-block; padding: 10px 20px; background-color: #0000ff; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;">Reply to User</a>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f8f8f8; padding: 20px; text-align: center; border-radius: 0 0 8px 8px;">
                <p style="margin: 0; color: #666; font-size: 12px;">This email was sent from your application’s issue reporting system.</p>
                <p style="margin: 5px 0 0; color: #666; font-size: 12px;">&copy; {{ date('Y') }} Your Application Name. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>