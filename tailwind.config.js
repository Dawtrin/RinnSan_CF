export default {
  content: [
    './resources/src/**/*.{js,jsx,ts,tsx,html}'
  ],
  theme: {
    extend: {
      colors: {
        // Bakery color palette
        primary: {
          50: '#fefaf6',
          100: '#fdf1e8',
          200: '#f9e0cc',
          300: '#f2c6a5',
          400: '#e9a273',
          500: '#e0874c', // Main bakery color
          600: '#d26c35',
          700: '#af552d',
          800: '#8c4529',
          900: '#723a25',
        },
        secondary: {
          500: '#8B4513', // Chocolate brown
        },
        accent: {
          500: '#FF6B6B', // Red accent
        }
      },
      fontFamily: {
        'cursive': ['"Dancing Script"', 'cursive'],
        'sans': ['"Open Sans"', 'sans-serif'],
        'display': ['"Playfair Display"', 'serif'],
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'float': 'float 3s ease-in-out infinite',
      }
    },
  },
  plugins: [],
}