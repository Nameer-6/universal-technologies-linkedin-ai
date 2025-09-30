// src/components/auth/ForgotPassword.jsx

import React, { useState } from "react";
import axios from "axios";
import { Link } from "react-router-dom";

export default function ForgotPassword() {
    const [email, setEmail] = useState("");
    const [message, setMessage] = useState("");
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError(""); setMessage("");

        try {
            const res = await axios.post("/api/password/email", { email });
            setMessage(res.data.message);
        } catch (err) {
            setError(err.response?.data?.email || "Unable to send reset link.");
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100 p-4">
            <div className="max-w-md w-full bg-white p-8 rounded shadow">
                <h2 className="text-xl font-bold mb-4">Forgot Password</h2>

                {message && <div className="p-3 bg-green-100 text-green-700 rounded">{message}</div>}
                {error && <div className="p-3 bg-red-100 text-red-700 rounded">{error}</div>}

                <form onSubmit={handleSubmit} className="space-y-4 mt-4">
                    <div>
                        <label htmlFor="Email" className="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <input
                            type="email"
                            value={email}
                            onChange={e => setEmail(e.target.value)}
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
                        {loading ? "Sending…" : "Send Reset Link"}
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
