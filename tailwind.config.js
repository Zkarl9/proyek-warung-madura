/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        base: '#F4F6F5',
        surface: '#FFFFFF',
        ink: '#16241F',
        mist: '#E1E6E3',
        teal: {
          DEFAULT: '#0F6E64',
          deep: '#0B4F48',
          soft: '#E4F1EF',
        },
        amber: {
          DEFAULT: '#E8A33D',
          soft: '#FBEDD3',
        },
        terracotta: {
          DEFAULT: '#C9483B',
          soft: '#F8E2DF',
        },
      },
      fontFamily: {
        display: ['"Space Grotesk"', 'sans-serif'],
        body: ['"Plus Jakarta Sans"', 'sans-serif'],
        mono: ['"IBM Plex Mono"', 'monospace'],
      },
      borderRadius: {
        tag: '6px',
      },
    },
  },
  plugins: [],
}
