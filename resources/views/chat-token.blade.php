<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Token</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .token { margin: 20px 0; word-break: break-all; }
        button { padding: 10px 20px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h1>Generate Token</h1>
    <p>Token Anda:</p>
    <div class="token">{{ $token }}</div>
    <button onclick="setToken()">Set Token dan Buka Forum Chat</button>

    <script>
        function setToken() {
            const token = "{{ $token }}";
            // Set token di localStorage domain http://localhost:3000
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = 'http://localhost:3000/set-token?token=' + encodeURIComponent(token);
            iframe.onload = () => {
                window.location.href = 'http://localhost:3000/chat';
            };
            document.body.appendChild(iframe);
        }
    </script>
</body>
</html>