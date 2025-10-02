import React, { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import Header from "@/components/Header"; // Adjust the import path as needed
import { RxEyeClosed, RxEyeOpen } from "react-icons/rx";

function UpdatePasswordForm() {
  const [currentPassword, setCurrentPassword] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();
  const [showPassword, setShowPassword] = useState(false);
  const [showCurrentPassword, setShowCurrentPassword] = useState(false);

  const toggleShowPassword = () => setShowPassword(prev => !prev);
  const toggleShowCurrentPassword = () => setShowCurrentPassword(prev => !prev);

  const handleUpdatePassword = async (e) => {
    e.preventDefault();

    const isPasswordValid = (pwd) => {
      return /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!"#$%&'()*+,\-./:;<=>?@[\\\]\^_`{|}~]).{8,}$/.test(pwd);
    };

    if (!currentPassword || !newPassword || !confirmPassword) {
      setError("All fields are required.");
      return;
    }
    if (!isPasswordValid(newPassword)) {
      setError(
        "Password must be at least 8 characters long, include at least one letter, one digit, and one special character."
      );
      return;
    }
    if (newPassword !== confirmPassword) {
      setError("New passwords don't match.");
      return;
    }

    setError("");
    setSuccess("");
    setLoading(true);

    try {
      const apiBaseUrl = window.location.hostname !== 'localhost' 
        ? 'https://universal-technologies-linkedin-ai-production.up.railway.app' 
        : '';
      
      const response = await fetch(`${apiBaseUrl}/api/update-password`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          current_password: currentPassword,
          new_password: newPassword
        })
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.error || "Failed to update password");
      }

      setSuccess("Password updated successfully.");
      setCurrentPassword("");
      setNewPassword("");
      setConfirmPassword("");
    } catch (err) {
      setError(err.message);
    }

    setLoading(false);
  };

  return (
    <>
      {/* Fixed Header with max width */}
      <header className="fixed top-0 left-0 w-full z-30">
        <div className="max-w-7xl mx-auto">
          <Header />
          
        </div>
      </header>

      {/* Update Password Form Container */}
      <section className="min-h-screen relative bg-gray-50">
        {/* Background Decorative Elements */}
        <div className="absolute top-10 left-10 w-48 h-48 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>
        <div className="absolute bottom-10 right-10 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>

        {/* Main Content Container */}
        <div className="pt-20 flex items-center justify-center min-h-screen">
          <div className="max-w-md w-full space-y-8 bg-white p-8 rounded shadow relative z-10">
            <div className="text-center">
              <h2 className="mt-6 text-3xl font-extrabold text-red-600">
                Update <span className="text-black">Password</span>
              </h2>
            </div>

            {error && (
              <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {error}
              </div>
            )}
            {success && (
              <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {success}
              </div>
            )}

            <form onSubmit={handleUpdatePassword} className="mt-8 space-y-6">
              {/* Current Password Field */}
              <div>
                <label
                  htmlFor="currentPassword"
                  className="block text-sm font-medium text-gray-700"
                >
                  Current Password
                </label>
                <div className="relative">
                  <input
                    type={showCurrentPassword ? "text" : "password"}
                    id="currentPassword"
                    placeholder="Current Password"
                    value={currentPassword}
                    onChange={(e) => setCurrentPassword(e.target.value)}
                    required
                    className="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-600 focus:border-[#0000ff]"
                  />
                  <button
                  type="button"
                  onClick={toggleShowCurrentPassword}
                  className="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                  {showCurrentPassword ? <RxEyeOpen size={20} /> : <RxEyeClosed size={20} />}
                </button>
                </div>
              </div>

              {/* New Password Field */}
              <div>
                <label
                  htmlFor="newPassword"
                  className="block text-sm font-medium text-gray-700"
                >
                  New Password
                </label>
                <div className="relative">
                  <input
                    type={showPassword ? "text" : "password"}
                    id="newPassword"
                    placeholder="New Password"
                    value={newPassword}
                    onChange={(e) => setNewPassword(e.target.value)}
                    required
                    className="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-600 focus:border-[#0000ff]"
                  />
                  <button
                    type="button"
                    onClick={toggleShowPassword}
                    className="absolute inset-y-0 right-0 pr-3 flex items-center"
                  >
                    {showPassword ? <RxEyeOpen size={20} /> : <RxEyeClosed size={20} />}
                  </button>
                </div>
              </div>

              {/* Confirm New Password Field */}
              <div>
                <label
                  htmlFor="confirmPassword"
                  className="block text-sm font-medium text-gray-700"
                >
                  Confirm New Password
                </label>
                <div className="relative">
                  <input
                    type={showPassword ? "text" : "password"}
                    id="confirmPassword"
                    placeholder="Confirm New Password"
                    value={confirmPassword}
                    onChange={(e) => setConfirmPassword(e.target.value)}
                    required
                    className="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-600 focus:border-[#0000ff]"
                  />
                </div>
              </div>

              {/* Submit Button */}
              <div>
                <button
                  type="submit"
                  disabled={loading}
                  className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0000ff]"
                  style={{ backgroundColor: "#0014ff" }}
                >
                  {loading ? (
                    <span className="animate-pulse">Updating...</span>
                  ) : (
                    "Update Password"
                  )}
                </button>
              </div>
            </form>
          </div>
        </div>
      </section>
    </>
  );
}

export default UpdatePasswordForm;