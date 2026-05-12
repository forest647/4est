<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #39792D; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 8px 8px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #39792D; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Contact Message from 4est.info</h2>
        </div>
        <div class="content">
            <div class="field">
                <span class="label">From:</span> {{ $senderName }} ({{ $senderEmail }})
            </div>
            <div class="field">
                <span class="label">Subject:</span> {{ $senderSubject }}
            </div>
            <div class="field">
                <span class="label">Message:</span>
                <p>{{ $body }}</p>
            </div>
        </div>
    </div>
</body>
</html>
