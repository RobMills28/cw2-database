import React, { useState } from 'react';
import { Search, FileText, Users, Car, Settings, LogOut, PlusCircle, List, Shield } from 'lucide-react';

export const Dashboard = ({ user, onLogout }) => {
  // State for search inputs
  const [peopleSearch, setPeopleSearch] = useState('');
  const [vehicleSearch, setVehicleSearch] = useState('');

  // Handle form submissions
  const handlePeopleSearch = (e) => {
    e.preventDefault();
    window.location.href = `search_people.php?search=${encodeURIComponent(peopleSearch)}`;
  };

  const handleVehicleSearch = (e) => {
    e.preventDefault();
    window.location.href = `search_vehicles.php?search=${encodeURIComponent(vehicleSearch)}`;
  };

  const handleLogout = async (e) => {
    e.preventDefault();
    try {
      await onLogout();
    } catch (err) {
      console.error('Logout failed:', err);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Navigation Bar */}
      <nav className="bg-blue-700 text-white px-6 py-4 flex justify-between items-center shadow-lg">
        <div className="flex items-center space-x-2">
          <Shield className="w-8 h-8" />
          <h1 className="text-2xl font-bold">Police Traffic Database</h1>
        </div>
        <div className="flex items-center space-x-4">
          <span className="text-sm">Officer {user.username}</span>
          <button
            onClick={handleLogout}
            className="flex items-center space-x-1 hover:bg-blue-800 px-3 py-2 rounded transition-colors"
          >
            <LogOut className="w-4 h-4" />
            <span>Logout</span>
          </button>
        </div>
      </nav>

      {/* Main Content */}
      <div className="container mx-auto px-6 py-8">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {/* Search People Card */}
          <div className="bg-white p-6 rounded-lg shadow-md">
            <h2 className="flex items-center space-x-2 text-xl font-semibold text-blue-700 mb-4">
              <Users className="w-5 h-5" />
              <span>Search People</span>
            </h2>
            <form onSubmit={handlePeopleSearch} className="space-y-4">
              <input
                type="text"
                value={peopleSearch}
                onChange={(e) => setPeopleSearch(e.target.value)}
                placeholder="Name or License Number"
                className="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
              <button type="submit" className="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                <Search className="w-4 h-4" />
                <span>Search</span>
              </button>
            </form>
          </div>

          {/* Search Vehicles Card */}
          <div className="bg-white p-6 rounded-lg shadow-md">
            <h2 className="flex items-center space-x-2 text-xl font-semibold text-blue-700 mb-4">
              <Car className="w-5 h-5" />
              <span>Search Vehicles</span>
            </h2>
            <form onSubmit={handleVehicleSearch} className="space-y-4">
              <input
                type="text"
                value={vehicleSearch}
                onChange={(e) => setVehicleSearch(e.target.value)}
                placeholder="Registration Number"
                className="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
              <button type="submit" className="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                <Search className="w-4 h-4" />
                <span>Search</span>
              </button>
            </form>
          </div>

          {/* Incident Reports Card */}
          <div className="bg-white p-6 rounded-lg shadow-md">
            <h2 className="flex items-center space-x-2 text-xl font-semibold text-blue-700 mb-4">
              <FileText className="w-5 h-5" />
              <span>Incident Reports</span>
            </h2>
            <div className="space-y-4">
              <a href="file_report.php" className="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                <PlusCircle className="w-4 h-4" />
                <span>File New Report</span>
              </a>
              <a href="view_reports.php" className="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center justify-center space-x-2">
                <List className="w-4 h-4" />
                <span>View Reports</span>
              </a>
            </div>
          </div>

          {/* Admin Functions Card - Only shown if user is admin */}
          {user.isAdmin && (
            <div className="bg-white p-6 rounded-lg shadow-md">
              <h2 className="flex items-center space-x-2 text-xl font-semibold text-blue-700 mb-4">
                <Settings className="w-5 h-5" />
                <span>Admin Functions</span>
              </h2>
              <div className="space-y-4">
                <a href="manage_officers.php" className="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center space-x-2">
                  <Users className="w-4 h-4" />
                  <span>Manage Officers</span>
                </a>
                <a href="audit_log.php" className="w-full bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors flex items-center justify-center space-x-2">
                  <FileText className="w-4 h-4" />
                  <span>View Audit Log</span>
                </a>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default Dashboard;