/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#0F766E', // Teal 700
        secondary: '#f3f4f6',
      }
    },
  },
  plugins: [],
}
