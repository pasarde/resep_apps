<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Forum</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Recipe Finder</h1>
            <nav>
                <a href="/" class="mr-4">Home</a>
                <a href="/favorites" class="mr-4">Favorites</a>
                <a href="/chat/forum" class="mr-4">Chat</a>
                <a href="/danu">Danu</a>
            </nav>
        </div>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Chat Forum</h1>
        <div id="error" class="text-red-500 mb-4"></div>
        <div id="messages" class="mb-4"></div>
        <div class="flex">
            <input id="message-input" type="text" class="flex-1 p-2 border rounded-l" placeholder="Type a message...">
            <button id="send-button" class="p-2 bg-blue-500 text-white rounded-r">Send</button>
        </div>
    </main>

    <script>
        const token = localStorage.getItem('token');
        const errorDiv = document.getElementById('error');
        const messagesDiv = document.getElementById('messages');
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');

        if (!token) {
            errorDiv.textContent = 'No authentication token found. Please log in or set a token.';
        } else {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            axios.defaults.withCredentials = true;

            // Fetch messages
            axios.get('http://127.0.0.1:8000/api/chat')
                .then(response => {
                    response.data.forEach(msg => {
                        const p = document.createElement('p');
                        p.textContent = `${msg.user?.name || 'Unknown'}: ${msg.message}`;
                        messagesDiv.appendChild(p);
                    });
                })
                .catch(err => {
                    errorDiv.textContent = 'Failed to fetch messages: ' + err.message;
                });

            // Send message
            sendButton.addEventListener('click', () => {
    const message = messageInput.value.trim();
    if (!message) return;

    axios.get('http://127.0.0.1:8000/sanctum/csrf-cookie')
        .then(() => {
            // Ambil CSRF token dari cookie
            const csrfToken = document.cookie
                .split('; ')
                .find(row => row.startsWith('XSRF-TOKEN='))
                ?.split('=')[1];

            // Kirim CSRF token di header
            axios.post('http://127.0.0.1:8000/api/chat', { message }, {
                headers: {
                    'X-CSRF-TOKEN': decodeURIComponent(csrfToken),
                }
            })
            .then(response => {
                const p = document.createElement('p');
                p.textContent = `${response.data.user?.name || 'Unknown'}: ${response.data.message}`;
                messagesDiv.appendChild(p);
                messageInput.value = '';
            })
            .catch(err => {
                errorDiv.textContent = 'Failed to send message: ' + err.message;
            });
        })
        .catch(err => {
            errorDiv.textContent = 'Failed to fetch CSRF token: ' + err.message;
        });
});
        }
    </script>
</body>
</html>