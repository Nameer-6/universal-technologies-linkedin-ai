// src/components/CreatePromoForm.jsx

import React, { useState } from 'react';
import axios from 'axios';

const CreatePromoForm = () => {
  const [form, setForm] = useState({
    code: '',
    type: 'percentage',      // "percentage" or "free_months"
    percent_off: 10,         // editable for both types
    duration_months: 12,     // only used when type="free_months"
  });

  const [loading, setLoading] = useState(false);
  const [successMsg, setSuccessMsg] = useState('');
  const [errorMsg, setErrorMsg] = useState('');

  // Handle any input change
  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({
      ...prev,
      [name]: name === 'code' ? value.toUpperCase() : value,
    }));
  };

  // Form submission
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrorMsg('');
    setSuccessMsg('');
    setLoading(true);

    try {
      // Build payload
      const payload = {
        code: form.code.trim(),
        type: form.type,
        percent_off: parseInt(form.percent_off, 10),
        duration_months: form.type === 'free_months' ? parseInt(form.duration_months, 10) : null,
      };

      // Send to backend
      const { data } = await axios.post('/api/promo/create', payload);

      setSuccessMsg(`✅ Coupon "${data.coupon.id}" created successfully!`);
      // Reset form to defaults
      setForm({
        code: '',
        type: 'percentage',
        percent_off: 10,
        duration_months: 12,
      });
    } catch (err) {
      if (err.response && err.response.data && err.response.data.error) {
        setErrorMsg(err.response.data.error);
      } else {
        setErrorMsg('Unexpected error. Please try again.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-md mx-auto mt-12 p-6 bg-white rounded-2xl shadow-lg">
      <h2 className="text-2xl font-semibold text-gray-800 mb-4 text-center">
        Create a New Promo Code
      </h2>

      {successMsg && (
        <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
          {successMsg}
        </div>
      )}
      {errorMsg && (
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
          {errorMsg}
        </div>
      )}

      <form onSubmit={handleSubmit} className="space-y-6">
        {/* Promo Code Input */}
        <div>
          <label htmlFor="code" className="block text-sm font-medium text-gray-700 mb-1">
            Promo Code
          </label>
          <input
            type="text"
            name="code"
            id="code"
            value={form.code}
            onChange={handleChange}
            required
            placeholder="E.g. SPRING10 or FREE12"
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 transition"
          />
        </div>

        {/* Type Dropdown */}
        <div>
          <label htmlFor="type" className="block text-sm font-medium text-gray-700 mb-1">
            Type
          </label>
          <select
            name="type"
            id="type"
            value={form.type}
            onChange={handleChange}
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 transition bg-white"
          >
            <option value="percentage">10% Off (One-Time)</option>
            <option value="free_months">Free N Months</option>
          </select>
        </div>

        {/* Percent Off Field (always visible for both types) */}
        <div>
          <label htmlFor="percent_off" className="block text-sm font-medium text-gray-700 mb-1">
            Percent Off
          </label>
          <input
            type="number"
            name="percent_off"
            id="percent_off"
            value={form.percent_off}
            onChange={handleChange}
            min="1"
            max="100"
            required
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 transition"
          />
          {form.type === 'free_months' && (
            <p className="mt-1 text-sm text-gray-600">
              For "Free N Months", set this to 100 (100% off) or adjust if you want a different percentage for those months.
            </p>
          )}
        </div>

        {/* Duration Field (only if type = free_months) */}
        {form.type === 'free_months' && (
          <div>
            <label htmlFor="duration_months" className="block text-sm font-medium text-gray-700 mb-1">
              Duration (in months)
            </label>
            <input
              type="number"
              name="duration_months"
              id="duration_months"
              value={form.duration_months}
              onChange={handleChange}
              min="1"
              max="24"
              required
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 transition"
            />
          </div>
        )}

        {/* Submit Button */}
        <button
          type="submit"
          disabled={loading}
          className={`w-full py-3 text-white font-medium rounded-lg transition ${
            loading ? 'bg-indigo-300 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'
          }`}
        >
          {loading ? 'Creating…' : 'Create Promo Code'}
        </button>
      </form>
    </div>
  );
};

export default CreatePromoForm;
