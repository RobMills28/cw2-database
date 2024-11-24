import React, { useState } from 'react';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Shield } from 'lucide-react';

const LoginForm = ({ onLogin }) => {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const formData = new FormData();
      formData.append('username', username);
      formData.append('password', password);

      // Just use the current URL as the endpoint
      const response = await fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      const data = await response.json();
      
      if (data.success) {
        onLogin(data.user);
      } else {
        setError(data.message || 'Invalid username or password');
      }
    } catch (err) {
      setError('An error occurred. Please try again.');
      console.error('Login error:', err);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 flex items-center justify-center p-4">
      <Card className="w-full max-w-md shadow-lg">
        <CardHeader className="space-y-2">
          <div className="flex justify-center">
            <div className="bg-blue-50 p-3 rounded-full">
              <Shield className="h-12 w-12 text-blue-600" />
            </div>
          </div>
          <CardTitle className="text-2xl font-bold text-center">
            Police Traffic Database
          </CardTitle>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="space-y-2">
              <label htmlFor="username" className="block text-sm font-medium">
                Username
              </label>
              <input
                id="username"
                type="text"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                className="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            <div className="space-y-2">
              <label htmlFor="password" className="block text-sm font-medium">
                Password
              </label>
              <input
                id="password"
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                className="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            
            {error && (
              <div className="text-red-600 text-sm text-center p-2 bg-red-50 rounded-md border border-red-100">
                {error}
              </div>
            )}
            
            <button
              type="submit"
              className="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
            >
              Login
            </button>

            <div className="mt-6 p-4 bg-gray-50 rounded-md border">
              <p className="text-sm font-medium text-center mb-2">Available accounts:</p>
              <div className="space-y-2">
                <div className="bg-white p-2 rounded-md border">
                  <code className="text-sm">mcnulty/plod123</code> - Officer
                </div>
                <div className="bg-white p-2 rounded-md border">
                  <code className="text-sm">moreland/fuzz42</code> - Officer
                </div>
                <div className="bg-white p-2 rounded-md border">
                  <code className="text-sm">daniels/copper99</code> - Administrator
                </div>
              </div>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  );
};

export { LoginForm };