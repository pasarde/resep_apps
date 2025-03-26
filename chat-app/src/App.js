import React from 'react';
import { BrowserRouter as Router, Route, Routes, useLocation, Navigate } from 'react-router-dom';
import Chat from './Chat';

function SetToken() {
    const location = useLocation();
    const params = new URLSearchParams(location.search);
    const token = params.get('token');
    console.log('Query params:', params.toString()); // Debug query params
    console.log('Token from query:', token); // Debug token
    if (token) {
        localStorage.setItem('token', token);
        console.log('Token set:', token);
    } else {
        console.log('No token found in query params');
    }
    return null;
}

function App() {
    return (
        <Router>
            <Routes>
                <Route path="/chat" element={<Chat />} />
                <Route path="/set-token" element={<SetToken />} />
                <Route path="/" element={<Navigate to="/chat" />} />
            </Routes>
        </Router>
    );
}

export default App;