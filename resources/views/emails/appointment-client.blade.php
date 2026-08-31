<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Tu visita a BELA</title></head>
<body style="margin:0;padding:0;background:#0a0806;font-family:Montserrat,Arial,sans-serif;color:#332219;">
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#0a0806;padding:32px 16px;">
<tr><td align="center">
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;background:#fffaf2;border:1px solid #d9c19a;border-radius:16px;overflow:hidden;">
<tr><td style="background:#120d08;padding:22px 28px;text-align:center;border-bottom:1px solid #8a6a2a;">
<img src="{{ asset('media/bela-logo.png') }}" alt="BELA Beauty Studio" style="height:42px;width:auto;display:inline-block;">
</td></tr>
<tr><td style="padding:32px 28px 8px;text-align:center;">
<p style="margin:0;color:#9a702e;font-size:10px;letter-spacing:2px;text-transform:uppercase;">SOLICITUD CONFIRMADA</p>
<h1 style="margin:14px 0 0;font-family:Cinzel,serif;font-size:28px;line-height:1.15;color:#312116;font-weight:400;">Tu visita a BELA<br><span style="color:#976d2e;">ya está en camino.</span></h1>
</td></tr>
<tr><td style="padding:16px 28px 24px;">
<p style="margin:0 0 20px;color:#5b4632;font-size:13px;line-height:1.7;">Hola {{ $appointment->name }}, hemos recibido tu solicitud y nuestro equipo te contactará para confirmar la disponibilidad.</p>
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #cdb38f;border-radius:10px;overflow:hidden;background:#f9f0e4;">
<tr><td style="padding:14px 16px;border-bottom:1px solid #dcc7a7;"><span style="display:block;color:#98764a;font-size:8px;letter-spacing:1px;">INTERÉS</span><strong style="color:#4b3826;font-family:Cinzel,serif;font-size:12px;">{{ $appointment->interest }}</strong></td>
<td style="padding:14px 16px;border-bottom:1px solid #dcc7a7;border-left:1px solid #dcc7a7;"><span style="display:block;color:#98764a;font-size:8px;letter-spacing:1px;">FECHA Y HORA</span><strong style="color:#4b3826;font-family:Cinzel,serif;font-size:12px;">{{ $appointment->preferred_date->format('d/m/Y') }} · {{ substr($appointment->preferred_time,0,5) }} ({{ $appointment->duration_minutes }} min)</strong></td></tr>
<tr><td style="padding:14px 16px;"><span style="display:block;color:#98764a;font-size:8px;letter-spacing:1px;">REFERENCIA</span><strong style="color:#4b3826;font-family:Cinzel,serif;font-size:13px;letter-spacing:1px;">{{ $appointment->confirmation_code }}</strong></td>
<td style="padding:14px 16px;border-left:1px solid #dcc7a7;"><span style="display:block;color:#98764a;font-size:8px;letter-spacing:1px;">CONTACTO</span><strong style="color:#4b3826;font-size:11px;">{{ $appointment->email }}<br>{{ $appointment->phone }}</strong></td></tr>
</table>
@if($appointment->message)
<p style="margin:16px 0 0;color:#7a6648;font-size:11px;line-height:1.6;background:#fff9ef;border:1px solid #e0cfb2;border-radius:8px;padding:10px 12px;"><strong>Tu mensaje:</strong> {{ $appointment->message }}</p>
@endif
<p style="margin:20px 0 0;color:#8a755a;font-size:11px;line-height:1.6;text-align:center;">Si necesitas cambiar tu visita, responde a este correo con tu código de referencia.</p>
</td></tr>
<tr><td style="padding:18px 28px;background:#f9f0e4;border-top:1px solid #e0cfb2;text-align:center;">
<p style="margin:0;color:#9e8a73;font-size:11px;">BELA Beauty Studio · Tu talento merece un lugar donde crecer.</p>
</td></tr>
</table>
<p style="margin:16px 0 0;text-align:center;color:#6f5f4c;font-size:10px;">© 2026 BELA Beauty Studio</p>
</td></tr>
</table>
</body>
</html>
