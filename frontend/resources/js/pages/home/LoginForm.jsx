import axios from "axios";
import React, { useState } from "react";
import { RxEyeClosed, RxEyeOpen } from "react-icons/rx";
import { Link, useNavigate } from "react-router-dom";
import logo from "../../assets/img/loader.gif";

// axios defaults: send cookies with every request to same-origin API
axios.defaults.withCredentials = true;
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

function LoginForm() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const navigate = useNavigate();

  const toggleShowPassword = () => setShowPassword((prev) => !prev);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError("");

    const remember = document.getElementById("rememberMe").checked;

    // Pre-login console message
    if (remember) {
      console.info(
        "[Auth] Remember Me checked. Requesting server to set persistent HttpOnly cookie (remember_web_*)."
      );
    } else {
      console.info("[Auth] Remember Me not checked. Session cookie only.");
    }

    try {
      const response = await axios.post(
        "/api/login",
        { email, password, remember },
        { withCredentials: true }
      );

      // Post-login console message
      if (remember) {
        console.info(
          "[Auth] Login successful. If credentials are valid and route uses 'web' guard, Laravel sets an HttpOnly remember cookie (remember_web_*). You won't see it via document.cookie, but the browser stored it."
        );
      } else {
        console.info(
          "[Auth] Login successful. Session cookie set (will expire per SESSION_LIFETIME)."
        );
      }

      // Store token and redirect
      localStorage.setItem('auth_token', response.data.token);
      window.location.href = "/linkedin-ai";
    } catch (err) {
      const msg =
        err?.response?.data?.error ||
        err?.response?.data?.message ||
        "Invalid credentials";
      console.error("[Auth] Login failed:", msg);
      setError(msg);
      setLoading(false);
    }
  };

  return (
    <>
      {/* Navbar */}
      <nav className="bg-white fixed top-0 w-full shadow">
        <div className="container mx-auto flex items-center justify-between px-4 py-2">
          <Link to="#" className="block w-[250px]">
            <img src={logo} alt="Logo" className="w-full h-auto" />
          </Link>
        </div>
      </nav>

      {/* Login Form */}
      <div className="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4">
        <div className="max-w-md w-full space-y-8 bg-white p-8 rounded shadow">
          <div className="text-center">
            <h2 className="mt-6 text-3xl font-extrabold text-red-600">
              Sign <span className="text-black">in</span>
            </h2>
          </div>

          {error && (
            <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit} className="mt-8 space-y-6">
            {/* Email */}
            <div>
              <label htmlFor="Email" className="block text-sm font-medium text-gray-700">
                Email
              </label>
              <input
                type="email"
                id="Email"
                placeholder="Your email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                className="appearance-none rounded block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-600 focus:border-[#0000ff]"
              />
            </div>

            {/* Password */}
            <div>
              <label htmlFor="Password" className="block text-sm font-medium text-gray-700">
                Password
              </label>
              <div className="relative">
                <input
                  type={showPassword ? "text" : "password"}
                  id="Password"
                  placeholder="Password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                  className="appearance-none rounded block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-600 focus:border-[#0000ff]"
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

            {/* Remember me */}
            <div className="flex items-center">
              <input
                type="checkbox"
                id="rememberMe"
                className="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
              />
              <label htmlFor="rememberMe" className="ml-2 block text-sm text-gray-900">
                Remember me
              </label>
            </div>

            {/* Submit */}
            <div>
              <button
                type="submit"
                disabled={loading}
                className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0000ff]"
                style={{ backgroundColor: "#0014ff" }}
              >
                {loading ? <span className="animate-pulse">Signing in...</span> : "Sign In"}
              </button>
            </div>

            <div className="text-right">
              <Link to="/forgot-password" className="font-medium text-sm text-red-600 hover:underline">
                Forgot your password?
              </Link>
            </div>
          </form>

          <div className="mt-4 text-center text-sm text-gray-600">
            Don’t have an account?{" "}
            <Link to="/sign-up" className="font-medium text-red-600 hover:text-red-500">
              Sign up
            </Link>
          </div>
        </div>
      </div>
    </>
  );
}

export default LoginForm;
