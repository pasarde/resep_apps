import React, { useState, useEffect } from 'react';
import { fetchUser, fetchChat, sendMessage } from './api';

function Chat() {
    const [messages, setMessages] = useState([]);
    const [newMessage, setNewMessage] = useState('');
    const [error, setError] = useState('');
    const [user, setUser] = useState(null);

    useEffect(() => {
        const token = localStorage.getItem('token');
        if (!token) {
            setError('No authentication token found. Please log in or set a token.');
            return;
        }

        const fetchData = async () => {
            try {
                // Ambil data user
                const userResponse = await fetchUser();
                setUser(userResponse.data);

                // Ambil chat
                const chatResponse = await fetchChat();
                setMessages(chatResponse.data);
            } catch (err) {
                setError('Failed to fetch data: ' + err.message);
            }
        };

        fetchData();
    }, []);

    const handleSendMessage = async () => {
        if (!newMessage.trim()) return;

        try {
            const response = await sendMessage(newMessage);
            setMessages([...messages, response.data]);
            setNewMessage('');
        } catch (err) {
            setError('Failed to send message: ' + err.message);
        }
    };

    return (
        <div>
            <h1>Chat Forum</h1>
            {user && <p>Welcome, {user.name}!</p>}
            {error && <p style={{ color: 'red' }}>{error}</p>}
            <div>
                {messages.map((msg, index) => (
                    <p key={index}>
                        <strong>{msg.user?.name || 'Unknown'}:</strong> {msg.message}
                    </p>
                ))}
            </div>
            <input
                type="text"
                value={newMessage}
                onChange={(e) => setNewMessage(e.target.value)}
                placeholder="Type a message..."
            />
            <button onClick={handleSendMessage}>Send</button>
        </div>
    );
}

export default Chat;