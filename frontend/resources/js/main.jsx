import React from 'react'; // Ensure React is imported
import { BrowserRouter } from 'react-router-dom';
import { createRoot } from 'react-dom/client';
import App from './App.jsx';

// Necessary CSS imports
import 'bootstrap/dist/css/bootstrap.min.css';
import './assets/css/slick.css';
import './assets/css/swiper-gl.min.css';
import './assets/css/font-awesome-pro.css';
import './assets/css/ion.rangeSlider.min.css';
import './assets/css/spacing.css';
import './assets/css/animate.css';
import './assets/css/main.css';

// Necessary JS imports
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import './assets/js/nice-select.js';
import './assets/js/parallax-scroll.js';
import './assets/js/onpage-menu.js';
import './assets/js/jquery-knob.js';
import './assets/js/jquery-appear.js';
import './assets/js/ajax-form.js';

// Create the root and render the app inside the "app" div
const rootElement = document.getElementById('app');
const root = createRoot(rootElement);

root.render(
  <React.StrictMode>
    <BrowserRouter>
      <App />
    </BrowserRouter>
  </React.StrictMode>
);
