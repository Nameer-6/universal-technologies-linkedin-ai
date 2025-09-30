import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Header from '@/components/Header'; // Adjust the import path as needed

export default function BillingDetails() {
  const [subscription, setSubscription] = useState(null);
  const [message, setMessage] = useState('');

  // Retrieve the auth token from localStorage
  const token = localStorage.getItem('authAuthToken') || localStorage.getItem('authToken');

  // Fetch subscription info from backend
  const fetchSubscription = async () => {
    setMessage('');
    try {
      const response = await fetch(`/api/user/subscription`, {
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
      });
      const data = await response.json();
      if (!response.ok) {
        throw new Error(data.error || 'Failed to fetch subscription');
      }
      setSubscription(data);
    } catch (error) {
      setMessage(error.message);
    }
  };

  // Cancel subscription
  const handleCancel = async () => {
    setMessage('Canceling subscription...');
    try {
      const response = await fetch(`/api/cancel-subscription`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
      });
      const data = await response.json();
      if (!response.ok) {
        throw new Error(data.message || 'Error canceling subscription');
      }
      setMessage('Subscription canceled successfully!');
      // Refresh subscription info
      fetchSubscription();
    } catch (error) {
      setMessage(error.message);
    }
  };

  // Logout user (remove token)
  const handleLogout = () => {
    localStorage.removeItem('authToken');
    window.location.href = '/login';
  };

  useEffect(() => {
    fetchSubscription();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  return (
    <div className="min-h-screen relative bg-gray-50">
      {/* Background Decorative Elements */}
      <div className="absolute top-10 left-10 w-48 h-48 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>
      <div className="absolute bottom-10 right-10 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>

      {/* Fixed Header */}
      <header className="fixed top-0 left-0 w-full z-30">
        <div className="max-w-7xl mx-auto">
          <Header />
        </div>
      </header>

      {/* Centered Subscription Card */}
      <div className="flex items-center justify-center min-h-screen pt-20">
        <div className="max-w-md w-full bg-white p-8 rounded shadow relative z-10">
          {message && (
            <div className="mb-4 text-center text-green-500 font-semibold">
              {message}
            </div>
          )}

          {!subscription ? (
            <p className="text-center">Loading subscription info...</p>
          ) : !subscription.active ? (
            <div>
              <div className="uppercase text-sm font-bold text-gray-500 mb-2">
                Your Subscription Plan
              </div>
              <div className="text-2xl font-bold text-black mb-4">
                No active subscription
              </div>
            </div>
          ) : (
            <div>
              <div className="uppercase text-sm font-bold text-gray-500 mb-2">
                Your Subscription Plan
              </div>
              <div className="text-2xl font-bold text-black mb-4">
                {subscription.type || 'Subscription Plan'}
              </div>

              <div className="flex justify-between mb-2">
                <span className="text-gray-500 text-sm">Price</span>
                <span className="text-black text-sm font-medium">
                  {subscription.stripe_price || 'N/A'}
                </span>
              </div>

              <div className="flex justify-between mb-2">
                <span className="text-gray-500 text-sm">Next Billing Date</span>
                <span className="text-black text-sm font-medium">
                  {subscription.ends_at || 'N/A'}
                </span>
              </div>

              <div className="mt-6 flex flex-col gap-3">
                {!subscription.canceled ? (
                  <button
                    onClick={handleCancel}
                    style={{ backgroundColor: '#ef4444' }}
                    className="py-2 px-4 rounded uppercase text-white font-semibold"
                  >
                    Cancel Subscription
                  </button>
                ) : (
                  <p className="text-gray-500 m-0">
                    Already canceled. Ends at {subscription.ends_at || 'N/A'}.
                  </p>
                )}
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
