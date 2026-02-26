<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation à rejoindre une colocation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e9ecef;
        }
        .details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background: #5a67d8;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 14px;
        }
        .token {
            background: #f1f3f4;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏠 Invitation à rejoindre une colocation</h1>
        <p>Bonjour !</p>
    </div>
    
    <div class="content">
        <p>Vous avez été invité(e) à rejoindre la colocation <strong>{{ $invitation->colocation->name }}</strong>.</p>
        
        <div class="details">
            <h3>📋 Détails de l'invitation</h3>
            <ul>
                <li><strong>Colocation :</strong> {{ $invitation->colocation->name }}</li>
                <li><strong>Propriétaire :</strong> {{ $invitation->colocation->owner()->first()->name ?? 'Non spécifié' }}</li>
                <li><strong>Date d'invitation :</strong> {{ $invitation->created_at->format('d/m/Y à H:i') }}</li>
                <li><strong>Token :</strong> <span class="token">{{ $invitation->token }}</span></li>
            </ul>
        </div>
        
        <p style="text-align: center;">
            <a href="{{ route('invitations.show', $invitation->token) }}" class="button">
                🚀 Rejoindre la colocation
            </a>
        </p>
        
        <hr style="border: none; border-top: 1px solid #e9ecef; margin: 30px 0;">
        
        <h3>📝 Instructions</h3>
        <ol>
            <li>Cliquez sur le bouton ci-dessus pour voir les détails</li>
            <li>Si vous n'avez pas de compte, créez-en un d'abord</li>
            <li>Acceptez ou refusez l'invitation selon votre choix</li>
        </ol>
        
        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <h3>⚠️ Important</h3>
            <p>Cette invitation expirera dans 7 jours. Si vous ne l'acceptez pas avant cette date, vous devrez demander une nouvelle invitation.</p>
        </div>
        
        <p style="text-align: center; margin-top: 30px;">
            <a href="{{ route('dashboard') }}" class="button" style="background: #6c757d;">
                🏠 Accéder au site
            </a>
        </p>
    </div>
    
    <div class="footer">
        <p>Ceci est un email automatique de {{ config('app.name') }}. Si vous n'avez pas demandé cette invitation, vous pouvez ignorer cet email.</p>
        <p>Merci,<br>L'équipe {{ config('app.name') }}</p>
    </div>
</body>
</html>