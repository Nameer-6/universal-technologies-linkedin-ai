// lib/CreditsContext.js
import React, { createContext, useContext, useState, useCallback } from "react";

const CreditsContext = createContext();

export function CreditsProvider({ children }) {
  const [credits, setCredits] = useState(null);
  const [loadingCredits, setLoadingCredits] = useState(true);

  // Fetch credits from backend
  const fetchCredits = useCallback(async () => {
    setLoadingCredits(true);
    try {
      const apiBaseUrl = window.location.hostname !== 'localhost' 
        ? 'https://universal-technologies-linkedin-ai-production.up.railway.app' 
        : '';
      
      const res = await fetch(`${apiBaseUrl}/api/user/credits`, { credentials: "include" });
      const data = await res.json();
      setCredits(data.credits ?? null);
    } catch {
      setCredits(null);
    }
    setLoadingCredits(false);
  }, []);

  return (
    <CreditsContext.Provider value={{ credits, loadingCredits, fetchCredits, refreshCredits: fetchCredits }}>
      {children}
    </CreditsContext.Provider>
  );
}

// Custom hook for easy access
export function useCredits() {
  return useContext(CreditsContext);
}
