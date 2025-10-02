import { loadStripe } from '@stripe/stripe-js';
import React, { useState } from 'react';
// Logo removed as requested
import {
    CardElement,
    Elements,
    useElements,
    useStripe,
} from '@stripe/react-stripe-js';
import {
    CheckIcon,
    CreditCardIcon,
    InfoIcon,
    LockIcon,
    ShieldIcon,
} from 'lucide-react';
import american_ex from '../../assets/img/american-express.png';
import mastercard from '../../assets/img/mastercard-icon.png';
import paypal from '../../assets/img/paypal.png';
import stripe_logo from '../../assets/img/stripe.png';
import visacard from '../../assets/img/visa-icon.png';

/**
 * 1. PUBLISHABLE KEY & STRIPE SETUP
 */
const stripePromise = loadStripe('pk_live_DyJef6NpobxsKb3d4DlSkGe3'); 
export default function SingleFileCheckout() {
  return (
    <Elements stripe={stripePromise}>
      <CheckoutUI />
    </Elements>
  );
}

function CheckoutUI() {
  const orderItems = [
    { name: 'Monthly Subscription', price: 1 },
    { name: 'One-Time Setup Fee', price: 10 },
  ];
  const subtotal = orderItems.reduce((acc, item) => acc + item.price, 0);
  const tax = 0;
  const total = subtotal + tax;

  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
  });

  const [errors, setErrors] = useState({});
  const [cardError, setCardError] = useState('');
  const [isProcessing, setIsProcessing] = useState(false);

  // Stripe Hooks
  const stripe = useStripe();
  const elements = useElements();

  // C. Simple Validation
  const validateEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  const strongPassword = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{8,}$/;

  const validateForm = () => {
    const newErrors = {};
  
    if (!formData.name) {
      newErrors.name = 'Name is required';
    }
    if (!formData.email) {
      newErrors.email = 'Email is required';
    } else if (!validateEmail(formData.email)) {
      newErrors.email = 'Invalid email address';
    }
    if (!formData.password) {
      newErrors.password = 'Password is required';
    }
    else{
      if (!strongPassword.test(formData.password)) {
        newErrors.password =
          'Password must be at least 8 characters long and include at least one number and one special character';
      }
    }
    if (!formData.confirmPassword) {
      newErrors.confirmPassword = 'Confirm Password is required';
    } else if (formData.password !== formData.confirmPassword) {
      newErrors.confirmPassword = 'Passwords do not match';
    }
  
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };
  
  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setCardError('');

    // 1) Validate local fields
    if (!validateForm()) return;

    // 2) Ensure Stripe is ready
    if (!stripe || !elements) {
      console.error('Stripe.js has not loaded yet.');
      return;
    }

    setIsProcessing(true);

    // 3) Create PaymentMethod (CardElement)
    const cardElement = elements.getElement(CardElement);
    const { error, paymentMethod } = await stripe.createPaymentMethod({
      type: 'card',
      card: cardElement,
      billing_details: {
        name: formData.name,
        email: formData.email,
      },
    });

    if (error) {
      console.error('PaymentMethod creation error:', error);
      setCardError(error.message);
      setIsProcessing(false);
      return;
    }

    // 4) Build payload - only required fields
    const payload = {
      name: formData.name,
      email: formData.email,
      password: formData.password,
      payment_method: paymentMethod.id,
    };

    try {
      // 5) POST to backend
      const apiBaseUrl = window.location.hostname !== 'localhost' 
        ? 'https://universal-technologies-linkedin-ai-production.up.railway.app' 
        : '';
      
      const response = await fetch(`${apiBaseUrl}/api/signup`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });

      // 6) Read response as text
      const text = await response.text();

      // 7) Try to parse as JSON
      let jsonData = null;
      try {
        jsonData = JSON.parse(text);
      } catch {
        jsonData = null;
      }

      // 8) If not OK, show error
      if (!response.ok) {
        if (jsonData && jsonData.error) {
          throw new Error(jsonData.error);
        }
        throw new Error(text || `Request failed with status ${response.status}`);
      }

      // 9) Success
      alert('Payment successful! Please log in.');
      window.location.href = '/login';

    } catch (err) {
      console.error('Fetch error:', err);
      alert(`Payment failed: ${err.message}`);
    } finally {
      setIsProcessing(false);
    }
  };

  // ----------------------------------
  // F. Render Entire UI
  // ----------------------------------
  return (
    <div className="min-h-screen flex flex-col bg-gradient-to-b from-white to-stripe-gray">
      {/* Header */}
      <header className="w-full py-4 border-b border-stripe-gray-light">
        <div className="container max-w-6xl mx-auto px-4">
          <div className="flex justify-between items-center">
              <a
                className="navbar-brand logo_landing"
                href="#"
                style={{ width: "250px" }}
              >
                <span className="text-2xl font-bold text-blue-600">Universal Technologies</span>
              </a>
            <div className="flex items-center justify-end">
            <img src={stripe_logo} alt="Logo" className="img-fluid w-[75px]" />
            </div>
          </div>
        </div>
      </header>

      {/* Main */}
      <main className="flex-1 container max-w-6xl mx-auto px-4 py-6">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">

          {/* LEFT: The Checkout Form */}
          <div className="lg:col-span-2">
            <form onSubmit={handleSubmit} className="w-full space-y-6 signup-form">
              <h2 className="text-2xl font-medium mb-5">Billing <span className='text-[#D60000]'>Information</span></h2>

              <div className="mb-4">
                <label className="block text-sm font-medium mb-1">
                  Name <span className="text-stripe-error ml-1">*</span>
                </label>
                <input
                  name="name"
                  value={formData.name}
                  onChange={handleChange}
                  placeholder="Enter your name"
                  className={`w-full py-2.5 px-4 rounded-md text-foreground 
                              bg-white placeholder:text-gray-400
                              ${
                                errors.name
                                  ? // Error styling
                                    "border border-destructive focus:border-destructive focus:ring-destructive/20"
                                  : // Default/focus styling
                                    "border border-white focus:border-[#0000FF] focus:outline-none"
                              }`}
                />
                {errors.name && (
                  <p className="mt-1.5 text-sm text-destructive animate-slide-in">
                    {errors.name}
                  </p>
                )}
              </div>
              <div className="mb-4">
                <label className="block text-sm font-medium mb-1">
                  Email <span className="text-stripe-error ml-1">*</span>
                </label>
                <input
                  name="email"
                  type="email"
                  value={formData.email}
                  onChange={handleChange}
                  placeholder="Enter your email address"
                  className={`w-full py-2.5 px-4 rounded-md text-foreground 
                              bg-white placeholder:text-gray-400
                              ${
                                errors.email
                                  ? "border border-destructive focus:border-destructive focus:ring-destructive/20"
                                  : "border border-white focus:border-[#0000FF] focus:outline-none"
                              }`}
                />
                {errors.email && (
                  <p className="mt-1.5 text-sm text-destructive animate-slide-in">
                    {errors.email}
                  </p>
                )}
              </div>
              <div className="mb-4">
                <label className="block text-sm font-medium mb-1">
                  Password <span className="text-stripe-error ml-1">*</span>
                </label>
                <input
                  name="password"
                  type="password"
                  value={formData.password}
                  onChange={handleChange}
                  placeholder="Enter your password"
                  className={`w-full py-2.5 px-4 rounded-md text-foreground 
                              bg-white placeholder:text-gray-400
                              ${
                                errors.password
                                  ? "border border-destructive focus:border-destructive focus:ring-destructive/20"
                                  : "border border-white focus:border-[#0000FF] focus:outline-none"
                              }`}
                />
                {errors.password && (
                  <p className="mt-1.5 text-sm text-destructive animate-slide-in">
                    {errors.password}
                  </p>
                )}
              </div>
              <div className="mb-4">
                <label className="block text-sm font-medium mb-1">
                  Confirm Password <span className="text-stripe-error ml-1">*</span>
                </label>
                <input
                  name="confirmPassword"
                  type="password"
                  value={formData.confirmPassword}
                  onChange={handleChange}
                  placeholder="Confirm your password"
                  className={`w-full py-2.5 px-4 rounded-md text-foreground 
                            bg-white placeholder:text-gray-400
                            ${
                              errors.confirmPassword
                                ? "border border-destructive focus:border-destructive focus:ring-destructive/20"
                                : "border border-white focus:border-[#0000FF] focus:outline-none"
                            }`}
                />
                {errors.confirmPassword && (
                  <p className="mt-1.5 text-sm text-destructive animate-slide-in">
                    {errors.confirmPassword}
                  </p>
                )}
              </div>
              <h2 className="text-2xl font-medium"><span className='text-[#D60000]'>Card</span> Information</h2>
              <div className="border rounded-md p-4">
                <CardElement options={{ hidePostalCode: true }} />
                {cardError && (
                  <p className="mt-1 text-sm text-destructive">{cardError}</p>
                )}
              </div>

              {/* Submit Button */}
              <div className="flex justify-end">
                <button
                  type="submit"
                  disabled={isProcessing}
                  className="relative inline-flex items-center justify-center rounded-md text-sm font-medium bg-[#0000FF] text-white hover:bg-[#0000FF]/90 px-4 py-2"
                >
                  {isProcessing && (
                    <div className="absolute inset-0 flex items-center justify-center bg-inherit">
                      <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                    </div>
                  )}
                  <span className={isProcessing ? 'invisible' : 'flex items-center gap-x-2'}>
                    Complete Purchase
                    <CheckIcon className="h-4 w-4 ml-1" />
                  </span>
                </button>
              </div>
            </form>
          </div>

          {/* RIGHT: Order Summary */}
          <div className="lg:col-span-1">
            <div className="border rounded-lg overflow-hidden glass">
              <div className="p-5 animate-fade-in">
                <h3 className="text-lg font-medium mb-4">Order <span className='text-[#0000FF]'>Summary</span></h3>
                <div className="space-y-3 mb-5">
                  {orderItems.map((item, index) => (
                    <div key={index} className="flex justify-between text-sm">
                      <span className="text-stripe-gray-dark">
                        {item.name}
                      </span>
                      <span className="font-medium">
                        {new Intl.NumberFormat('en-US', {
                          style: 'currency',
                          currency: 'USD',
                        }).format(item.price)}
                      </span>
                    </div>
                  ))}
                </div>
                <div className="border-t border-stripe-gray-light pt-4 mb-4">
                  <div className="flex justify-between text-sm mb-2">
                    <span className="text-stripe-gray-dark">Subtotal</span>
                    <span>
                      {new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                      }).format(subtotal)}
                    </span>
                  </div>
                  {tax > 0 && (
                    <div className="flex justify-between text-sm mb-2">
                      <span className="text-stripe-gray-dark">Tax</span>
                      <span>
                        {new Intl.NumberFormat('en-US', {
                          style: 'currency',
                          currency: 'USD',
                        }).format(tax)}
                      </span>
                    </div>
                  )}
                  <div className="flex justify-between font-medium mt-3 pt-3 border-t border-stripe-gray-light">
                    <span>Total</span>
                    <span className="text-lg">
                      {new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                      }).format(total)}
                    </span>
                  </div>
                </div>
                <div className="flex items-center text-xs text-stripe-gray-dark p-3 bg-stripe-gray rounded-md">
                  <InfoIcon className="h-4 w-4 mr-2 flex-shrink-0" />
                  <p>
                    Prices shown in US Dollars. International orders may be subject to customs
                    duties and taxes.
                  </p>
                </div>
              </div>
              <div className="bg-stripe-gray p-4 border-t border-stripe-gray-light">
                <div className="flex items-center text-xs text-stripe-gray-dark">
                  <ShieldIcon className="h-4 w-4 mr-2 text-[stripe-success]" />
                  <p>
                    All transactions are secure and encrypted. Your payment information is never
                    stored.
                  </p>
                </div>
                <div className="flex items-center justify-between mt-4">
                  <div className="flex items-center text-xs text-stripe-gray-dark">
                    <CreditCardIcon className="h-4 w-4 mr-2" />
                    <span>Supported payment methods:</span>
                  </div>
                  <div className="flex space-x-1.5">
                    <img src={mastercard} className="w-[35px] h-[25px] rounded "/>
                    <img src={visacard} className="w-[35px] h-[25px]  rounded "/>
                    <img src={paypal} className="w-[35px] h-[25px]  rounded "/>
                    <img src={american_ex} className="w-[35px] h-[25px]  rounded "/>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </main>

      {/* Footer */}
      <footer className="w-full py-6 mt-12 border-t border-stripe-gray-light">
        <div className="container max-w-6xl mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-center">
            <div className="flex items-center mb-4 md:mb-0">
              <LockIcon className="h-4 w-4 text-[#D60000] mr-2" />
              <span className="text-sm text-stripe-gray-dark">
                Your information is secured with SSL encryption
              </span>
            </div>
            <div className="flex flex-wrap justify-center md:justify-end gap-4">
              <a
                href="#"
                className="text-sm text-stripe-gray-dark hover:text-stripe-blue transition-colors"
              >
                Terms &amp; Conditions
              </a>
              <a
                href="#"
                className="text-sm text-stripe-gray-dark hover:text-stripe-blue transition-colors"
              >
                Privacy Policy
              </a>
              <a
                href="#"
                className="text-sm text-stripe-gray-dark hover:text-stripe-blue transition-colors"
              >
                Contact Support
              </a>
            </div>
          </div>
          <div className="mt-6 text-center text-xs text-stripe-gray-medium">
            <p>© {new Date().getFullYear()} Your Company. All rights reserved.</p>
            <div className="flex items-center justify-center mt-2">
              <ShieldIcon className="h-3 w-3 mr-1 text-stripe-success" />
              <span>Powered by Stripe</span>
            </div>
          </div>
        </div>
      </footer>
    </div>
  );
}
