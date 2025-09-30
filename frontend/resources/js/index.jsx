import React from "react";
import { BrowserRouter } from "react-router-dom";
import { createRoot } from "react-dom/client";
import App from "./Apps.jsx";
import "./index.css";
import axios from "axios";

// Axios global defaults
axios.defaults.withCredentials = true;
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Pull CSRF token from meta tag (for web.php routes)
const token = document
  .querySelector('meta[name="csrf-token"]')
  ?.getAttribute("content");
if (token) {
  axios.defaults.headers.common["X-CSRF-TOKEN"] = token;
}

const rootElement = document.getElementById("app");
const root = createRoot(rootElement);

root.render(
  <React.StrictMode>
    <BrowserRouter>
      <App />
    </BrowserRouter>
  </React.StrictMode>
);
