import React from 'react';
import ReactDOM from 'react-dom/client';
import { Dashboard } from './components/Dashboard';

// Get the user data that was passed from PHP
const userData = window.USER_DATA || {
  userId: null,
  username: null,
  isAdmin: false
};

ReactDOM.createRoot(document.getElementById('dashboard-root')).render(
  <React.StrictMode>
    <Dashboard user={userData} />
  </React.StrictMode>
);