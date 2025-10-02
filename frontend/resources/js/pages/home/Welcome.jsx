import React, { useState, useRef } from 'react';
import { Link } from 'react-router-dom';
import ReCAPTCHA from 'react-google-recaptcha';
import logo from '../../assets/img/loader.gif';
import { RxEyeClosed, RxEyeOpen } from "react-icons/rx";

function Welcome() {
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [promoCode, setPromoCode] = useState("");            // ← state for promo code
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [recaptchaToken, setRecaptchaToken] = useState("");

  const recaptchaRef = useRef(null);
  const toggleShowPassword = () => setShowPassword(prev => !prev);

  // Simple email validation regex
  const isEmailValid = (email) => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  };

  // Password: at least 8 chars, one letter, one digit, one special char
  const isPasswordValid = (pwd) => {
    return /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!"#$%&'()*+,\-./:;<=>?@[\\\]\^_`{|}~]).{8,}$/.test(pwd);
  };

  const handlePayNow = async (e) => {
    e.preventDefault();

    // 1) Check for empty fields
    if (!name || !email || !password || !confirmPassword) {
      setError("Please fill in all fields.");
      return;
    }

    // 2) Email validation
    if (!isEmailValid(email)) {
      setError("Please enter a valid email address.");
      return;
    }

    // 3) Promo code is optional—no need to validate format client-side

    // 4) Password validation
    if (!isPasswordValid(password)) {
      setError(
        "Password must be at least 8 characters long, include at least one letter, one digit, and one special character."
      );
      return;
    }

    // 5) Confirm Password
    if (password !== confirmPassword) {
      setError("Passwords do not match.");
      return;
    }

    // 6) reCAPTCHA temporarily disabled for deployment
    // if (!recaptchaToken) {
    //   setError("Please complete the reCAPTCHA.");
    //   return;
    // }

    setLoading(true);
    setError("");

    try {
      const apiBaseUrl = window.location.hostname !== 'localhost' 
        ? 'https://universal-technologies-linkedin-ai-production.up.railway.app' 
        : '';
      
      const response = await fetch(`${apiBaseUrl}/api/signup`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name,
          email,
          promo_code: promoCode.trim(),   // ← send promo code to backend
          password
          // recaptcha_token: recaptchaToken // Temporarily disabled
        })
      });

      // Determine if the response is JSON based on the Content-Type header
      const contentType = response.headers.get("content-type");
      let data;
      if (contentType && contentType.includes("application/json")) {
        data = await response.json();
      } else {
        data = await response.text();
        console.error("Expected JSON but received:", data);
      }

      if (!response.ok) {
        if (typeof data === "string" && data.toLowerCase().includes("email already exist")) {
          setError("Email already exists.");
        } else if (data.error) {
          setError(data.error);
        } else {
          setError("Failed to create checkout session.");
        }
        return;
      }

      // Expecting { url: "https://checkout.stripe.com/..." }
      if (data.url) {
        window.location.href = data.url;
      } else {
        throw new Error("Failed to retrieve Stripe checkout URL.");
      }
    } catch (err) {
      setError(err.message);
    } finally {
      // Reset the reCAPTCHA widget
      if (recaptchaRef.current) {
        recaptchaRef.current.reset();
        setRecaptchaToken("");
      }
      setLoading(false);
    }
  };

  return (
    <>
      {/* Navigation Bar */}
      <nav className="bg-white fixed top-0 w-full shadow">
        <div className="container mx-auto flex items-center justify-between px-4 py-2">
          <a href="#" className="block" style={{ width: "250px" }}>
            <img src={logo} alt="Logo" className="w-full h-auto" />
          </a>
        </div>
      </nav>

      {/* Main Content */}
      <div className="min-h-screen flex items-center justify-center bg-gray-100 pt-20">
        <div className="max-w-md w-full bg-white p-8 rounded-lg shadow">
          <div className="mb-6 text-center">
            <h1 className="text-2xl font-bold" style={{ color: '#D60000' }}>
              <span style={{ color: '#000' }}>User </span>Information
            </h1>
          </div>

          {error && (
            <div className="bg-red-100 text-red-700 p-2 rounded mb-4">{error}</div>
          )}

          <form onSubmit={handlePayNow}>
            <div className="mb-4">
              <label
                htmlFor="full_name"
                className="block text-sm font-medium text-gray-700"
              >
                Full Name
              </label>
              <input
                type="text"
                id="full_name"
                placeholder="Your Full Name"
                value={name}
                onChange={(e) => setName(e.target.value)}
                required
                className="appearance-none rounded block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-600 focus:border-[#0000ff]"
              />
            </div>

            <div className="mb-4">
              <label
                htmlFor="Email"
                className="block text-sm font-medium text-gray-700"
              >
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

            {/* Promo Code Field */}
            <div className="mb-4">
              <label
                htmlFor="promo-code"
                className="block text-sm font-medium text-gray-700"
              >
                Promo Code (optional)
              </label>
              <input
                type="text"
                id="promo-code"
                placeholder="Enter promo code"
                value={promoCode}
                onChange={(e) => setPromoCode(e.target.value.toUpperCase())}
                className="appearance-none rounded block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-600 focus:border-[#0000ff]"
              />
            </div>

            <div className="mb-4">
              <label
                htmlFor="Password"
                className="block text-sm font-medium text-gray-700"
              >
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
                  {showPassword ? (
                    <RxEyeOpen size={20} />
                  ) : (
                    <RxEyeClosed size={20} />
                  )}
                </button>
              </div>
            </div>

            <div className="mb-4">
              <label
                htmlFor="confirm-password"
                className="block text-sm font-medium text-gray-700"
              >
                Confirm Password
              </label>
              <div className="relative">
                <input
                  type={showPassword ? "text" : "password"}
                  id="confirm-password"
                  placeholder="Re-enter Password"
                  value={confirmPassword}
                  onChange={(e) => setConfirmPassword(e.target.value)}
                  required
                  className="appearance-none rounded block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-600 focus:border-[#0000ff]"
                />
                <button
                  type="button"
                  onClick={toggleShowPassword}
                  className="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                  {/* Icon not strictly needed here */}
                </button>
              </div>
            </div>

            {/* reCAPTCHA widget - temporarily disabled for deployment */}
            {/* <div className="mb-4">
              <ReCAPTCHA
                sitekey="6LfVcQErAAAAAIim_YPPu2Vu2jSAr-VxjAgTEcon"
                onChange={(token) => setRecaptchaToken(token)}
                ref={recaptchaRef}
              />
            </div> */}

            <div className="mb-4">
              <button
                type="submit"
                className="w-full text-white py-2 rounded hover:bg-blue-700 transition"
                style={{ backgroundColor: "#0014ff" }}
                disabled={loading}
              >
                {loading ? "Processing..." : "Subscribe Now For Free Trial"}
              </button>
            </div>
          </form>

          <div className="text-center">
            <p className="text-sm">
              Already have an account?{" "}
              <Link to="/" className="font-medium text-red-600 hover:text-red-500">
                Sign in
              </Link>
            </p>
          </div>
        </div>
      </div>
    </>
  );
}

export default Welcome;
