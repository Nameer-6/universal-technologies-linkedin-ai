// App.jsx
import axios from "axios";
import React, { useEffect, useState } from "react";
import { Navigate, Route, Routes } from "react-router-dom";
import { CreditsProvider } from "./lib/CreditsContext";
import BillingDetails from "./pages/home/BillingDetails";
import CarouselMaker from "./pages/home/CarouseMaker";
import CreatePromoForm from "./pages/home/CreatePromoForm";
import EditScheduledPost from "./pages/home/EditSchedulePost";
import ForgotPassword from "./pages/home/ForgotPassword";
import GenerateImage from "./pages/home/GenerateImage";
import HomeThree from "./pages/home/HomeThree";
import LoginForm from "./pages/home/LoginForm";
import ResetPassword from "./pages/home/ResetPassword";
import ScheduledPosstItem from "./pages/home/ScheduledPosstItem";
import UpdatePasswordForm from "./pages/home/UpdatePassword";
import Welcome from "./pages/home/Welcome";

// Always send cookies
axios.defaults.withCredentials = true;

// Hook to check session-based auth with your backend
function useSessionAuth() {
  const [state, setState] = useState({ loading: true, authed: false });

  useEffect(() => {
    let mounted = true;
    
    // Get token from localStorage
    const token = localStorage.getItem('auth_token');
    console.log('🔍 Auth check - Token:', token ? token.substring(0, 20) + '...' : 'NO TOKEN');
    
    if (!token) {
      console.log('❌ No auth token found');
      if (mounted) setState({ loading: false, authed: false });
      return;
    }
    
    // Use token for authentication  
    axios
      .get("/api/whoami", { 
        headers: { 'Authorization': `Bearer ${token}` }
      })
      .then((res) => {
        console.log('✅ Raw response:', res);
        
        let responseData = res.data;
        
        // Handle corrupted response (PHP warnings + JSON)
        if (typeof responseData === 'string' && responseData.includes('<br />')) {
          console.log('🔧 Cleaning corrupted response');
          // Extract JSON from HTML-wrapped response
          const jsonMatch = responseData.match(/\{[^}]+\}/);
          if (jsonMatch) {
            try {
              responseData = JSON.parse(jsonMatch[0]);
              console.log('✅ Cleaned JSON:', responseData);
            } catch (e) {
              console.error('❌ Failed to parse extracted JSON:', e);
            }
          }
        }
        
        // Check if authentication is successful (simplified check)
        const ok = Boolean(responseData?.auth_id);
        console.log('🎯 Auth result:', { ok, auth_id: responseData?.auth_id, auth_check: responseData?.auth_check });
        if (mounted) setState({ loading: false, authed: ok });
        
        // If authentication failed but we have a token, clear it
        if (!ok) {
          console.log('❌ Auth failed, clearing token');
          localStorage.removeItem('auth_token');
        }
      })
      .catch((err) => {
        console.error('❌ Auth check failed:', err);
        // Token invalid or server error, remove it
        localStorage.removeItem('auth_token');
        if (mounted) setState({ loading: false, authed: false });
      });
    return () => (mounted = false);
  }, []);

  return state;
}

function ProtectedRoute({ children }) {
  const { loading, authed } = useSessionAuth();
  if (loading) {
    return (
      <div style={{ 
        display: 'flex', 
        justifyContent: 'center', 
        alignItems: 'center', 
        height: '100vh',
        fontSize: '18px',
        color: '#666'
      }}>
        Loading...
      </div>
    );
  }
  return authed ? children : <Navigate to="/" replace />;
}

function PublicRoute({ children }) {
  const { loading, authed } = useSessionAuth();
  if (loading) {
    return (
      <div style={{ 
        display: 'flex', 
        justifyContent: 'center', 
        alignItems: 'center', 
        height: '100vh',
        fontSize: '18px',
        color: '#666'
      }}>
        Loading...
      </div>
    );
  }
  return authed ? <Navigate to="/linkedin-ai" replace /> : children;
}

function App() {
  return (
    <CreditsProvider>
      <Routes>
        <Route
          path="/"
          element={
            <PublicRoute>
              <LoginForm />
            </PublicRoute>
          }
        />
        <Route path="/login" element={<LoginForm />} />
        <Route path="/sign-up" element={<Welcome />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />
        <Route path="/reset-password" element={<ResetPassword />} />
        <Route path="/carousel-maker" element={<CarouselMaker />} />
        <Route path="/create-promo" element={<CreatePromoForm />} />

        <Route
          path="/linkedin-ai"
          element={
            <ProtectedRoute>
              <HomeThree />
            </ProtectedRoute>
          }
        />
        <Route
          path="/welcome"
          element={
            <ProtectedRoute>
              <CarouselMaker />
            </ProtectedRoute>
          }
        />
        <Route
          path="/billing-details"
          element={
            <ProtectedRoute>
              <BillingDetails />
            </ProtectedRoute>
          }
        />
        <Route
          path="/metrics"
          element={
            <ProtectedRoute>
              <ScheduledPosstItem />
            </ProtectedRoute>
          }
        />
        <Route
          path="/generate-image"
          element={
            <ProtectedRoute>
              <GenerateImage />
            </ProtectedRoute>
          }
        />
        <Route
          path="/edit-scheduled-post/:id"
          element={
            <ProtectedRoute>
              <EditScheduledPost />
            </ProtectedRoute>
          }
        />
        <Route
          path="/update-password"
          element={
            <ProtectedRoute>
              <UpdatePasswordForm />
            </ProtectedRoute>
          }
        />
      </Routes>
    </CreditsProvider>
  );
}

export default App;
