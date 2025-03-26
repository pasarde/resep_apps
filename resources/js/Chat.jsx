import { useState, useEffect, useRef } from 'react';
import axios from 'axios';
// Hapus import CSS: import '../css/Chat.css';

function Chat() {
    const [token, setToken] = useState('');
    const [user, setUser] = useState(null);
    const [message, setMessage] = useState('');
    const [messages, setMessages] = useState([]);
    const [isTokenSet, setIsTokenSet] = useState(false);
    const [tokenError, setTokenError] = useState('');
    const chatContainerRef = useRef(null);

    const fetchUser = async () => {
        try {
            const response = await axios.get('/api/user', {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            console.log('User fetched:', response.data);
            setUser(response.data);
            setIsTokenSet(true);
            setTokenError('');
        } catch (err) {
            console.error('Failed to fetch user:', err.response?.status, err.response?.data || err.message);
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

    useEffect(() => {
        if (token && isTokenSet) {
            fetchMessages();
            const interval = setInterval(fetchMessages, 5000);
            return () => clearInterval(interval);
        }
    }, [token, isTokenSet]);

    useEffect(() => {
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
        console.log('Rendering token form...'); // Debug
        return (
            <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
                <h2 className="text-2xl font-bold text-center mb-4">Masukkan Token</h2>
                <form onSubmit={handleSetToken} className="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                    <input
                        type="text"
                        value={token}
                        onChange={(e) => setToken(e.target.value)}
                        placeholder="Masukkan token Sanctum"
                        className="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm mb-4"
                        required
                    />
                    {tokenError && <p className="text-red-500 text-center mb-4">{tokenError}</p>}
                    <button
                        type="submit"
                        className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200 shadow-md"
                    >
                        Set Token
                    </button>
                </form>
            </div>
        );
    }

    return (
        <div className="flex flex-col w-full h-screen bg-gray-100">
            <div className="bg-gray-800 text-white p-4 flex justify-between items-center">
                <h2 className="text-xl font-bold">Live Chat</h2>
                <p>Logged in as: {user?.name}</p>
            </div>

            <div className="flex-1 p-4 overflow-y-auto" ref={chatContainerRef}>
                {messages.map((msg, index) => (
                    <div
                        key={index}
                        className={`p-4 rounded-lg max-w-[75%] mb-4 ${
                            msg.user_id === user?.id
                                ? 'bg-blue-600 text-white self-end'
                                : 'bg-gray-200 text-gray-800 self-start'
                        }`}
                    >
                        <div className="flex justify-between text-sm mb-1">
                            <span className="font-bold">{msg.user.name}</span>
                            <span>
                                {new Date(msg.created_at).toLocaleTimeString()}
                            </span>
                        </div>
                        <p>{msg.content}</p>
                    </div>
                ))}
            </div>

            <form onSubmit={handleSendMessage} className="flex p-4 bg-white border-t-2 border-gray-300">
                <input
                    type="text"
                    value={message}
                    onChange={(e) => setMessage(e.target.value)}
                    placeholder="Tulis pesan..."
                    className="flex-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mr-2"
                />
                <button
                    type="submit"
                    className="bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700"
                >
                    Kirim
                </button>
            </form>
        </div>
    );
}

export default Chat; 