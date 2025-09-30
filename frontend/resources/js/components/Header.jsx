import { Coins } from 'lucide-react';
import React, { useEffect, useRef, useState } from "react";
import { toast } from "react-hot-toast";
import { Link, useNavigate } from "react-router-dom";
import defaultProfile from "../assets/img/user-default.jpg"; // Update path as needed
import { useCredits } from "../lib/CreditsContext";


const Header = () => {
    const [profile, setProfile] = useState(null);
    const { credits, loadingCredits, fetchCredits } = useCredits();
    const [dropdownOpen, setDropdownOpen] = useState(false);
    const navigate = useNavigate();
    const dropdownRef = useRef(null);

    // Fetch the profile data on mount
    useEffect(() => {
        const fetchProfile = async () => {
            try {
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    setProfile(null);
                    return;
                }
                
                const res = await fetch("/api/profile", { 
                    credentials: 'include',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.error) {
                    setProfile(null);
                } else {
                    setProfile(data);
                }
            } catch (error) {
                console.error("Error fetching profile:", error);
                toast.error("Error fetching profile.");
            }
        };
        fetchProfile();
    }, []);

    useEffect(() => {
        if (profile) fetchCredits();
    }, [profile, fetchCredits]);

    // Close the dropdown if a click occurs outside it
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setDropdownOpen(false);
            }
        };
        document.addEventListener("mousedown", handleClickOutside);
        return () => document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    // Handle logout action
    const handleLogout = async () => {
        try {
            // Remove token from localStorage
            localStorage.removeItem('auth_token');
            await fetch("/api/logout", { method: "POST", credentials: "include" });
            toast.success("Logged out!");
            navigate("/login");
        } catch (err) {
            console.error("Error logging out:", err);
            toast.error("Error logging out.");
        }
    };

  return (
    <header className="w-full px-3 py-2 flex items-center justify-between sticky top-0 z-40">
      {/* Logo removed as requested */}
      <div className="flex items-center min-w-0">
        <Link to="/" className="block">
          <span className="text-xl font-bold text-blue-600">Universal Technologies</span>
        </Link>
      </div>

      {/* Right side */}
      <div className="flex items-center gap-3 sm:gap-6">
        {/* Credits badge */}
        <div className="flex items-center gap-2 bg-gradient-to-r from-[#0000ff] to-indigo-600 shadow rounded-lg px-3 py-1 border border-indigo-300 min-w-[90px] justify-between">
          <Coins className="h-4 w-4 sm:h-5 sm:w-5 text-white" />
          <span className="hidden sm:inline text-sm font-semibold text-white/90 tracking-wide">Credits</span>
          <div className="h-4 w-px bg-white/20 mx-2 hidden sm:block" />
          {loadingCredits ? (
            <div className="flex items-center space-x-1">
              <div className="w-3 h-3 rounded-full bg-white/20 animate-pulse" />
              <div className="w-3 h-3 rounded-full bg-white/20 animate-pulse" style={{ animationDelay: '0.2s' }} />
            </div>
          ) : credits !== null ? (
            <span className="text-white font-semibold text-md tabular-nums">{credits.toLocaleString()}</span>
          ) : (
            <span className="text-white text-xs font-bold">n/a</span>
          )}
        </div>
        {/* Profile dropdown */}
        <div className="relative" ref={dropdownRef}>
          <img
            src={profile && profile.profilePic ? profile.profilePic : defaultProfile}
            alt="User"
            className="h-9 w-9 sm:h-10 sm:w-10 rounded-full cursor-pointer border border-gray-200 object-cover bg-gray-50 transition-all"
            onClick={() => setDropdownOpen((prev) => !prev)}
          />
          {dropdownOpen && (
            <div className="absolute right-0 mt-2 w-40 sm:w-48 bg-white border rounded shadow-lg z-30 animate-fade-in">
              <Link
                to="/billing-details"
                onClick={() => setDropdownOpen(false)}
                className="block px-4 py-2 hover:bg-gray-100"
              >
                Billing Details
              </Link>
              <Link
                to="/update-password"
                onClick={() => setDropdownOpen(false)}
                className="block px-4 py-2 hover:bg-gray-100"
              >
                Change Password
              </Link>
              <button
                onClick={handleLogout}
                className="w-full text-left px-4 py-2 hover:bg-gray-100"
              >
                Logout
              </button>
            </div>
          )}
        </div>
      </div>
    </header>
  );
};

export default Header;
