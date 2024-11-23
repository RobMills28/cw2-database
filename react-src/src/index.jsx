import React from 'react';
import ReactDOM from 'react-dom/client';
import { Dashboard } from './components/Dashboard';
import { LoginForm } from './components/LoginForm';

// Get initial state from PHP
const initialUser = window.USER_DATA || null;

function App() {
  const [user, setUser] = React.useState(initialUser);

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

  // Use PHP session data to determine what to show
  return user ? (
    <Dashboard user={user} onLogout={handleLogout} />
  ) : (
    <LoginForm onLogin={handleLogin} />
  );
}

// Create the root element if it doesn't exist
let rootElement = document.getElementById('root');
if (!rootElement) {
  rootElement = document.createElement('div');
  rootElement.id = 'root';
  document.body.appendChild(rootElement);
}

ReactDOM.createRoot(rootElement).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);

// Hide legacy content if React loads successfully
const legacyContent = document.getElementById('legacy-content');
if (legacyContent) {
  legacyContent.style.display = 'none';
}