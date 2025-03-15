/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php', // Scan semua file Blade
    './resources/**/*.js',        // Scan file JS
    './resources/**/*.vue',       // Kalo pake Vue
  ],
  theme: {
    extend: {
      // Tambah custom class kalo perlu, misal:
      '.weather-recipe-card': {
        'background-color': '#f9f9f9',
      },
    },
  },
  plugins: [],
}