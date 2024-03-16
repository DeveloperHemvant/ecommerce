/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'rgba-200-219-255': 'rgba(200, 219, 255, 1)',
      },
    },
  },
  plugins: [
    
],
}

