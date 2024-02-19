<!DOCTYPE html>
<html lang="en">
<head>
    <title>Actualización de la cita</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .message {
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>
<div class="container">
    <p class="message">
        Hola, {{ $nombre }},
    </p>
    <p class="message">
        El estatus de tu cita ha cambiado a: {{ $estatus }}.
    </p>
    <p class="message">
        Por favor, revisa tu cuenta para más detalles, o comunícate con nosotros.
    </p>
</div>
</body>
</html>
