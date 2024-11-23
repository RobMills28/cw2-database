import React, { useState, useEffect } from 'react';
import { LoginForm } from './components/LoginForm';
import { Dashboard } from './components/Dashboard';

const App = () => {
  const [user, setUser] = useState(window.USER_DATA || null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Check session status
    const checkSession = async () => {
      try {
        const response = await fetch('dashboard.php', {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        const data = await response.json();
        
        if (data.success) {
          setUser(data.user);
        } else {
          setUser(null);
        }
      } catch (err) {
        setUser(null);
      } finally {
        setLoading(false);
      }
    };

    checkSession();
  }, []);

  const handleLogin = (userData) => {
    setUser(userData);
    window.location.href = 'dashboard.php';
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

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <p className="text-lg">Loading...</p>
      </div>
    );
  }

  if (!user) {
    return <LoginForm onLogin={handleLogin} />;
  }

  return <Dashboard user={user} onLogout={handleLogout} />;
};

export default App;