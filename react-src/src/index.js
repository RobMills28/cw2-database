import React, { useState } from 'react';
import ReactDOM from 'react-dom/client';
import { Dashboard } from './components/Dashboard';
import { LoginForm } from './components/LoginForm';

// Get initial state from PHP
const initialUser = window.USER_DATA || null;

function App() {
  const [user, setUser] = useState(initialUser);

  const handleLogin = (userData) => {
    setUser(userData);
  };

  const handleLogout = async () => {
    try {
      await fetch('logout.php');
      setUser(null);
      window.location.href = 'index.php';
    } catch (err) {
      console.error('Logout failed:', err);
    }
  };

  return user ? (
    <Dashboard user={user} />
  ) : (
    <LoginForm onLogin={handleLogin} />
  );
}

// Determine which root element to use
const rootElement = document.getElementById('root');

if (rootElement) {
  const root = ReactDOM.createRoot(rootElement);
  root.render(
    <React.StrictMode>
      <App />
    </React.StrictMode>
  );

  // Hide legacy content if React loads successfully
  const legacyContent = document.getElementById('legacy-content');
  if (legacyContent) {
    legacyContent.style.display = 'none';
  }
}