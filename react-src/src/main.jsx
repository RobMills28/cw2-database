import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';
import './index.css';

const rootElement = document.getElementById('root') || document.createElement('div');
if (!rootElement.id) {
  rootElement.id = 'root';
  document.body.appendChild(rootElement);
}

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