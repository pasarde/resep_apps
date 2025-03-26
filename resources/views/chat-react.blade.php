@extends('layouts.app')

@section('title', 'Forum Chat')

@section('content')
    <div id="root"></div>
    <!-- Tambah React via CDN -->
    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        // Chat.jsx inline
        function Chat() {
            const [token, setToken] = React.useState('');
            const [user, setUser] = React.useState(null);
            const [message, setMessage] = React.useState('');
            const [messages, setMessages] = React.useState([]);
            const [isTokenSet, setIsTokenSet] = React.useState(false);
            const [tokenError, setTokenError] = React.useState('');
            const chatContainerRef = React.useRef(null);

            const fetchUser = async () => {
                try {
                    const response = await axios.get('/api/user', {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    });
                    setUser(response.data);
                    setIsTokenSet(true);
                    setTokenError('');
                } catch (err) {
                    console.error('Failed to fetch user:', err);
                    setTokenError('Token tidak valid. Silakan masukkan token yang benar.');
                    setIsTokenSet(false);
                }
            };

            const fetchMessages = async () => {
                if (!token) return;
                try {
                    const response = await axios.get('/api/messages', {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    });
                    setMessages(response.data);
                } catch (err) {
                    console.error('Failed to fetch messages:', err);
                }
            };

            React.useEffect(() => {
                if (token && isTokenSet) {
                    fetchMessages();
                    const interval = setInterval(fetchMessages, 5000);
                    return () => clearInterval(interval);
                }
            }, [token, isTokenSet]);

            React.useEffect(() => {
                if (chatContainerRef.current) {
                    chatContainerRef.current.scrollTop = chatContainerRef.current.scrollHeight;
                }
            }, [messages]);

            const handleSetToken = (e) => {
                e.preventDefault();
                if (token.trim()) {
                    fetchUser();
                } else {
                    setTokenError('Token tidak boleh kosong.');
                }
            };

            const handleSendMessage = async (e) => {
                e.preventDefault();
                if (message.trim() && user) {
                    try {
                        const response = await axios.post(
                            '/api/messages',
                            { content: message },
                            {
                                headers: {
                                    Authorization: `Bearer ${token}`,
                                },
                            }
                        );
                        setMessages((prevMessages) => [...prevMessages, response.data]);
                        setMessage('');
                    } catch (err) {
                        console.error('Failed to send message:', err);
                    }
                }
            };

            if (!isTokenSet) {
                console.log('Rendering token form...');
                return React.createElement(
                    'div',
                    { className: 'flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4' },
                    React.createElement(
                        'h2',
                        { className: 'text-2xl font-bold text-center mb-4' },
                        'Masukkan Token'
                    ),
                    React.createElement(
                        'form',
                        { onSubmit: handleSetToken, className: 'bg-white p-6 rounded-lg shadow-lg w-full max-w-md' },
                        React.createElement('input', {
                            type: 'text',
                            value: token,
                            onChange: (e) => setToken(e.target.value),
                            placeholder: 'Masukkan token Sanctum',
                            className: 'w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm mb-4',
                            required: true,
                        }),
                        tokenError && React.createElement('p', { className: 'text-red-500 text-center mb-4' }, tokenError),
                        React.createElement(
                            'button',
                            { type: 'submit', className: 'w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200 shadow-md' },
                            'Set Token'
                        )
                    )
                );
            }

            return React.createElement(
                'div',
                { className: 'flex flex-col w-full h-screen bg-gray-100' },
                React.createElement(
                    'div',
                    { className: 'bg-gray-800 text-white p-4 flex justify-between items-center' },
                    React.createElement('h2', { className: 'text-xl font-bold' }, 'Live Chat'),
                    React.createElement('p', null, `Logged in as: ${user?.name}`)
                ),
                React.createElement(
                    'div',
                    { className: 'flex-1 p-4 overflow-y-auto', ref: chatContainerRef },
                    messages.map((msg, index) =>
                        React.createElement(
                            'div',
                            {
                                key: index,
                                className: `p-4 rounded-lg max-w-[75%] mb-4 ${
                                    msg.user_id === user?.id
                                        ? 'bg-blue-600 text-white self-end'
                                        : 'bg-gray-200 text-gray-800 self-start'
                                }`,
                            },
                            React.createElement(
                                'div',
                                { className: 'flex justify-between text-sm mb-1' },
                                React.createElement('span', { className: 'font-bold' }, msg.user.name),
                                React.createElement('span', null, new Date(msg.created_at).toLocaleTimeString())
                            ),
                            React.createElement('p', null, msg.content)
                        )
                    )
                ),
                React.createElement(
                    'form',
                    { onSubmit: handleSendMessage, className: 'flex p-4 bg-white border-t-2 border-gray-300' },
                    React.createElement('input', {
                        type: 'text',
                        value: message,
                        onChange: (e) => setMessage(e.target.value),
                        placeholder: 'Tulis pesan...',
                        className: 'flex-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mr-2',
                    }),
                    React.createElement(
                        'button',
                        { type: 'submit', className: 'bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700' },
                        'Kirim'
                    )
                )
            );
        }

        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(React.createElement(Chat));
    </script>
@endsection