<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mensaje</title>
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
        {!! nl2br(e($text)) !!}
    </p>
</div>
</body>
</html>
