<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Nueva reserva BELA</title></head>
<body style="margin:0;padding:0;background:#f3efe6;font-family:Montserrat,Arial,sans-serif;color:#332219;">
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f3efe6;padding:24px 16px;">
<tr><td align="center">
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:640px;background:#ffffff;border:1px solid #d9c19a;border-radius:12px;overflow:hidden;">
<tr><td style="background:#120d08;padding:16px 24px;">
<span style="color:#d4af37;font-family:Cinzel,serif;font-size:13px;letter-spacing:2px;">BELA BEAUTY STUDIO</span>
<span style="float:right;color:#c7b18d;font-size:11px;letter-spacing:1px;">NUEVA RESERVA</span>
</td></tr>
<tr><td style="padding:24px 24px 8px;">
<h2 style="margin:0;font-family:Cinzel,serif;font-size:22px;color:#312116;">Nueva solicitud de visita</h2>
<p style="margin:6px 0 0;color:#7a6648;font-size:12px;">Código <strong style="color:#4b3826;letter-spacing:1px;">{{ $appointment->confirmation_code }}</strong> · {{ $appointment->created_at?->format('d/m/Y H:i') }}</p>
</td></tr>
<tr><td style="padding:16px 24px;">
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #e0cfb2;border-radius:8px;overflow:hidden;">
<tr><td style="padding:12px 14px;background:#f9f0e4;width:140px;color:#98764a;font-size:9px;letter-spacing:1px;">CLIENTE</td><td style="padding:12px 14px;"><strong style="color:#312116;">{{ $appointment->name }}</strong><br><span style="color:#5b4632;font-size:12px;">{{ $appointment->email }} · {{ $appointment->phone }}</span></td></tr>
<tr><td style="padding:12px 14px;background:#f9f0e4;border-top:1px solid #e0cfb2;color:#98764a;font-size:9px;letter-spacing:1px;">INTERÉS</td><td style="padding:12px 14px;border-top:1px solid #e0cfb2;color:#312116;">{{ $appointment->interest }}</td></tr>
<tr><td style="padding:12px 14px;background:#f9f0e4;border-top:1px solid #e0cfb2;color:#98764a;font-size:9px;letter-spacing:1px;">FECHA / HORA</td><td style="padding:12px 14px;border-top:1px solid #e0cfb2;"><strong style="color:#312116;">{{ $appointment->preferred_date->format('d/m/Y') }} · {{ substr($appointment->preferred_time,0,5) }}</strong> <span style="color:#7a6648;">({{ $appointment->duration_minutes }} min) — {{ $appointment->status }}</span></td></tr>
@if($appointment->message)
<tr><td style="padding:12px 14px;background:#f9f0e4;border-top:1px solid #e0cfb2;color:#98764a;font-size:9px;letter-spacing:1px;">MENSAJE</td><td style="padding:12px 14px;border-top:1px solid #e0cfb2;color:#5b4632;font-size:12px;line-height:1.6;">{{ $appointment->message }}</td></tr>
@endif
</table>
</td></tr>
<tr><td style="padding:16px 24px 24px;">
<a href="{{ route('home') }}#reserva" style="display:inline-block;background:#d4af37;color:#1b120a;text-decoration:none;padding:10px 18px;border-radius:999px;font-size:11px;font-weight:600;letter-spacing:1px;">VER EN LA WEB</a>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
