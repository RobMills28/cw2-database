/** @type {import('tailwindcss').Config} */
export default {
    prefix: 'tw-',
    content: [
      "./src/**/*.{js,jsx,ts,tsx}",
      "./index.html",
    ],
    theme: {
      extend: {
        colors: {
          blue: {
            50: '#eff6ff',
            100: '#dbeafe',
            200: '#bfdbfe',
            300: '#93c5fd',
            400: '#60a5fa',
            500: '#3b82f6',
            600: '#2563eb',
            700: '#1d4ed8',
            800: '#1e40af',
            900: '#1e3a8a',
          },
          gray: {
            50: '#f9fafb',
            100: '#f3f4f6',
            200: '#e5e7eb',
            300: '#d1d5db',
            400: '#9ca3af',
            500: '#6b7280',
            600: '#4b5563',
            700: '#374151',
            800: '#1f2937',
            900: '#111827',
          },
          red: {
            500: '#ef4444',
            600: '#dc2626',
          },
        },
        borderRadius: {
          'lg': '0.5rem',
          'md': '0.375rem',
        },
        spacing: {
          '0': '0',
          '0.5': '0.125rem',
          '1': '0.25rem',
          '2': '0.5rem',
          '3': '0.75rem',
          '4': '1rem',
          '5': '1.25rem',
          '6': '1.5rem',
          '8': '2rem',
          '10': '2.5rem',
          '12': '3rem',
          '16': '4rem',
        },
      },
    },
    plugins: [],
    corePlugins: {
      preflight: false, // This prevents Tailwind from resetting MVP.css styles
    }
  }