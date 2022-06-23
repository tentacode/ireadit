/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors')

module.exports = {
  darkMode: 'class',
  content: [
    'templates/**/*.html.twig',
    'assets/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        error: colors.red,
        primary: colors.pink,
      }
    },
  },
  plugins: [],
};