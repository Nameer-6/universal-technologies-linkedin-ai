/** @type {import('tailwindcss').Config} */
const withMT = require("@material-tailwind/react/utils/withMT");

module.exports = withMT({
  darkMode: ["class"],

  // Merged all paths from both config blocks + typical Laravel structure
  content: [
    "./pages/**/*.{js,jsx}",
    "./components/**/*.{js,jsx}",
    "./app/**/*.{js,jsx}",
    "./src/**/*.{js,jsx}",
    "./resources/**/*.{js,ts,jsx,tsx}",
  ],

  prefix: "",
  theme: {
    container: {
      center: true,
      padding: "2rem",
      screens: {
        // Merged your screens from both configs:
        sm: "640px",
        md: "768px",
        lg: "1024px",
        xl: "1280px",
        "2xl": "1400px",
      },
    },
    extend: {
      // If you want Inter fonts
      fontFamily: {
        inter: ["Inter", "sans-serif"],
      },

      colors: {
        // Base color variables (from both configs)
        border: "hsl(var(--border))",
        input: "hsl(var(--input))",
        ring: "hsl(var(--ring))",
        background: "hsl(var(--background))",
        foreground: "hsl(var(--foreground))",

        primary: {
          DEFAULT: "hsl(var(--primary))",
          foreground: "hsl(var(--primary-foreground))",
        },
        secondary: {
          DEFAULT: "hsl(var(--secondary))",
          foreground: "hsl(var(--secondary-foreground))",
        },
        destructive: {
          DEFAULT: "hsl(var(--destructive))",
          foreground: "hsl(var(--destructive-foreground))",
        },
        muted: {
          DEFAULT: "hsl(var(--muted))",
          foreground: "hsl(var(--muted-foreground))",
        },
        accent: {
          DEFAULT: "hsl(var(--accent))",
          foreground: "hsl(var(--accent-foreground))",
        },
        popover: {
          DEFAULT: "hsl(var(--popover))",
          foreground: "hsl(var(--popover-foreground))",
        },
        card: {
          DEFAULT: "hsl(var(--card))",
          foreground: "hsl(var(--card-foreground))",
        },

        // "sidebar" colors from the first config
        sidebar: {
          DEFAULT: "hsl(var(--sidebar-background))",
          foreground: "hsl(var(--sidebar-foreground))",
          primary: "hsl(var(--sidebar-primary))",
          "primary-foreground": "hsl(var(--sidebar-primary-foreground))",
          accent: "hsl(var(--sidebar-accent))",
          "accent-foreground": "hsl(var(--sidebar-accent-foreground))",
          border: "hsl(var(--sidebar-border))",
          ring: "hsl(var(--sidebar-ring))",
        },

				linkedin: {
					primary: '#0000ff',
					secondary: '#0073B1',
					dark: '#004182',
					light: '#E6F7FF'
				},
        spark: {
          red: "#FF5757",
          blue: "#0091FF",
          purple: "#8E44AD",
          teal: "#1ABC9C",
          yellow: "#F5D76E",
        },

        // Stripe colors from your second config
        stripe: {
          blue: "#635BFF",
          "blue-dark": "#5851EA",
          "blue-light": "#7A73FF",
          gray: "#F6F9FC",
          "gray-dark": "#425466",
          "gray-medium": "#8792A2",
          "gray-light": "#E3E8EE",
          success: "#3ECF8E",
          error: "#EB4785",
        },
        "stripe-blue": "#635BFF",
        "stripe-gray-light": "#e5e7eb",
        "stripe-gray-dark": "#6b7280",
        "stripe-gray-medium": "#9ca3af",
        "stripe-success": "#0f9d58",
        "stripe-error": "#d32f2f",
      },

      borderRadius: {
        lg: "var(--radius)",
        md: "calc(var(--radius) - 2px)",
        sm: "calc(var(--radius) - 4px)",
      },

      // Merge keyframes & animations from both blocks
      keyframes: {
        // From first config
        "accordion-down": {
          from: { height: "0" },
          to: { height: "var(--radix-accordion-content-height)" },
        },
        "accordion-up": {
          from: { height: "var(--radix-accordion-content-height)" },
          to: { height: "0" },
        },
        "pulse-slow": {
          "0%, 100%": { opacity: "1" },
          "50%": { opacity: "0.7" },
        },
        float: {
          "0%, 100%": { transform: "translateY(0)" },
          "50%": { transform: "translateY(-10px)" },
        },
        shimmer: {
          "0%": { backgroundPosition: "-1000px 0" },
          "100%": { backgroundPosition: "1000px 0" },
        },
        wave: {
          "0%": { transform: "translateX(0)" },
          "50%": { transform: "translateX(-25%)" },
          "100%": { transform: "translateX(-50%)" },
        },

        // From second config
        "fade-in": {
          "0%": { opacity: "0" },
          "100%": { opacity: "1" },
        },
        "fade-out": {
          "0%": { opacity: "1" },
          "100%": { opacity: "0" },
        },
        "slide-in": {
          "0%": { transform: "translateY(10px)", opacity: 0 },
          "100%": { transform: "translateY(0)", opacity: 1 },
        },
        "slide-up": {
          "0%": { transform: "translateY(100%)", opacity: "0" },
          "100%": { transform: "translateY(0)", opacity: "1" },
        },
        "slide-right": {
          "0%": { transform: "translateX(-10px)", opacity: "0" },
          "100%": { transform: "translateX(0)", opacity: "1" },
        },
        "pulse-soft": {
          "0%, 100%": { opacity: "1" },
          "50%": { opacity: "0.85" },
        },
        float2: {
          // Named differently since we already have "float"
          "0%, 100%": { transform: "translateY(0)" },
          "50%": { transform: "translateY(-5px)" },
        },
        fadeInUp: {
          "0%": { opacity: 0, transform: "translateY(100%)" },
          "100%": { opacity: 1, transform: "translateY(0)" },
        },
        fadeInLeft: {
          "0%": { opacity: 0, transform: "translateX(-100%)" },
          "100%": { opacity: 1, transform: "translateX(0)" },
        },
        fadeInRight: {
          "0%": { opacity: 0, transform: "translateX(100%)" },
          "100%": { opacity: 1, transform: "translateX(0)" },
        },
        fadeIn: {
          "0%": { opacity: 0 },
          "100%": { opacity: 1 },
        },
        slideIn2: {
          // Named differently to avoid collision with "slide-in"
          "0%": { transform: "translateY(-10px)", opacity: 0 },
          "100%": { transform: "translateY(0)", opacity: 1 },
        },
      },

      animation: {
        // From first config
        "accordion-down": "accordion-down 0.2s ease-out",
        "accordion-up": "accordion-up 0.2s ease-out",
        "pulse-slow": "pulse-slow 3s ease-in-out infinite",
        float: "float 4s ease-in-out infinite",
        shimmer: "shimmer 2.5s infinite linear",
        wave: "wave 10s infinite linear",

        // From second config
        "fade-in": "fade-in 0.5s ease-out",
        "fade-out": "fade-out 0.5s ease-out",
        "slide-in": "slide-in 0.5s ease-out",
        "slide-up": "slide-up 0.6s ease-out",
        "slide-right": "slide-right 0.4s ease-out",
        "pulse-soft": "pulse-soft 3s infinite ease-in-out",
        float2: "float2 5s infinite ease-in-out",
        fadeInUp: "fadeInUp 1.5s ease-in-out",
        fadeInLeft: "fadeInLeft 1.5s ease-in-out",
        fadeInRight: "fadeInRight 1.5s ease-in-out",
        slideIn2: "slideIn2 0.5s ease-out",
      },

      backgroundImage: {
        // Merged from first config
        "gradient-radial": "radial-gradient(var(--tw-gradient-stops))",
        'linkedin-gradient': 'linear-gradient(90deg, #0073B1 0%, #0A66C2 100%)',
        "shimmer-gradient":
          "linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%)",
      },
    },
  },
  plugins: [
    require("tailwindcss-animate"),
    // ...any other plugins you want
  ],
});
