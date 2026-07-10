<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'COFIMA'))</title>
    <style>
        body { margin: 0; padding: 0; background: #f1f5f9; font-family: Arial, Helvetica, sans-serif; }
        table { border-collapse: collapse; }
        a { color: #1B3A6B; }
    </style>
</head>
<body>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding: 24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%;">
                    <tr>
                        <td style="text-align:center; padding: 0 0 16px;">
                            <span style="font-size:1.1rem; font-weight:800; color:#1B3A6B; letter-spacing:.02em;">COFIMA</span>
                            <div style="font-size:.65rem; color:#6B7280; text-transform:uppercase; letter-spacing:.05em; margin-top:2px;">
                                Compagnie Fiduciaire de Management et d&apos;Audit
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="card" style="background:#ffffff; border-radius:8px; overflow:hidden; box-shadow: 0 1px 3px rgba(0,0,0,.08);">
                                @yield('content')
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center; padding: 20px 0 0; font-size:11px; color:#94a3b8;">
                            © {{ now()->year }} COFIMA — Usage interne. Ceci est un e-mail automatique, merci de ne pas y répondre directement.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
