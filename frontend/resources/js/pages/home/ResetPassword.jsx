// src/components/auth/ResetPassword.jsx

import React, { useState, useEffect } from "react";
import axios from "axios";
import { useLocation, Link, useNavigate } from "react-router-dom";

export default function ResetPassword() {
  const [email, setEmail]     = useState("");
  const [password, setPassword]     = useState("");
  const [confirm, setConfirm] = useState("");
  const [token, setToken]     = useState("");
  const [message, setMessage] = useState("");
  const [error, setError]     = useState("");
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();
  const query = new URLSearchParams(useLocation().search);

  useEffect(() => {
    setToken(query.get("token") || "");
    setEmail(query.get("email") || "");
  }, [query]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(""); setMessage("");

    try {
      await axios.post("/api/password/reset", {
        token, email, password, password_confirmation: confirm
      });
      setMessage("Password has been reset. Redirecting to sign in…");
      setTimeout(() => navigate("/login"), 2000);
    } catch (err) {
      setError(err.response?.data?.email || "Reset failed.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100 p-4">
      <div className="max-w-md w-full bg-white p-8 rounded shadow">
        <h2 className="text-xl font-bold mb-4">Reset Password</h2>

        {message && <div className="p-3 bg-green-100 text-green-700 rounded">{message}</div>}
        {error   && <div className="p-3 bg-red-100 text-red-700 rounded">{error}</div>}

        <form onSubmit={handleSubmit} className="space-y-4 mt-4">
          <input type="hidden" value={token} />

          <div>
            <label className="block text-sm">New Password</label>
            <input
              type="password"
              value={password}
              onChange={e => setPassword(e.target.value)}
              required
              className="appearance-none rounded block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-600 focus:border-[#0000ff]"
              />
          </div>
          <div>
            <label className="block text-sm">Confirm Password</label>
            <input
              type="password"
              value={confirm}
              onChange={e => setConfirm(e.target.value)}
              required
              className="appearance-none rounded block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-600 focus:border-[#0000ff]"
              />
          </div>
          <button
            type="submit"
            disabled={loading}
            style={{ backgroundColor: "#0000ff" }}
            className="w-full py-2 text-white rounded disabled:opacity-50"
          >
            {loading ? "Resetting…" : "Reset Password"}
          </button>
        </form>

        <div className="mt-4 text-center">
          <Link to="/login" className="text-sm text-gray-600 hover:underline">
            ← Back to Sign In
          </Link>
        </div>
      </div>
    </div>
  );
}
