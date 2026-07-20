<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Selamat Datang</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 20px;">
    <h2>Halo, {{ $name ?? 'Pengunjung' }}! 👋</h2>
    <p>Terima kasih telah mendaftar. Akun kamu sudah aktif dan siap digunakan.</p>
    <p>Jika kamu membutuhkan bantuan atau memiliki pertanyaan, jangan ragu untuk membalas email ini.</p>
    <br>
    <p>Salam hangat,<br>Tim {{ config('app.name') }}</p>
</body>
</html>
