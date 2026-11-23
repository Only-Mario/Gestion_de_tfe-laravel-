<!doctype html>
<html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ $titre }}</title></head>
<body style="margin:0;background:#f2f4fb;font-family:Roboto,Arial,sans-serif;color:#191c21">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f2f4fb;padding:24px 12px"><tr><td align="center">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:20px;overflow:hidden">
    <tr><td style="background:#0d4293;border-bottom:5px solid #83bd45;padding:20px 28px;color:#ffffff">
        <table role="presentation" cellpadding="0" cellspacing="0"><tr>
            @if (isset($message) && is_file(public_path('images/logo_insti.png')))<td style="padding-right:14px"><img src="{{ $message->embed(public_path('images/logo_insti.png')) }}" width="56" height="56" alt="INSTI" style="display:block;background:#ffffff;border-radius:28px"></td>@endif
            <td style="font-size:18px;font-weight:bold;color:#ffffff">INSTI Lokossa<br><span style="font-size:13px;font-weight:normal;color:#dbe4ff">Rapports de fin d’études</span></td>
        </tr></table>
    </td></tr>
    <tr><td style="padding:28px;font-size:15px;line-height:1.6">
        <h1 style="margin:0 0 16px;font-size:22px;color:#0d4293">{{ $titre }}</h1>
        @foreach ($lignes as $i => $ligne)
            <p style="margin:0 0 14px">{{ $ligne }}</p>
            @if ($i === 1 && !empty($code))<p style="margin:20px 0;padding:18px;background:#d9e2ff;border-radius:14px;text-align:center;font-size:32px;font-weight:bold;letter-spacing:8px;color:#001945">{{ $code }}</p>@endif
            @if ($i === 1 && !empty($bouton))<p style="margin:22px 0"><a href="{{ $bouton[1] }}" style="display:inline-block;padding:13px 26px;background:#0d4293;border-radius:24px;color:#ffffff;text-decoration:none;font-weight:bold">{{ $bouton[0] }}</a></p>@endif
        @endforeach
        @if (!empty($bouton))<p style="margin:18px 0 0;font-size:12px;color:#44474f;word-break:break-all">Si le bouton ne fonctionne pas : {{ $bouton[1] }}</p>@endif
    </td></tr>
    <tr><td style="padding:16px 28px;background:#f2f4fb;font-size:12px;color:#44474f">Message automatique de la plateforme des rapports de l’INSTI de Lokossa.</td></tr>
</table></td></tr></table></body></html>
