// Tailwind Configuration for Rencontre Éthique Luxury Homepage
// Add this to your existing tailwind.config.js or use these color extensions

module.exports = {
  theme: {
    extend: {
      colors: {
        'sapphire-blue': {
          DEFAULT: '#0f3a7d',
          50: '#f0f4fa',
          100: '#dce6f0',
          200: '#b8cce1',
          300: '#7fa8c9',
          400: '#4a7aaa',
          500: '#2d5a8f',
          600: '#0f3a7d',
          700: '#0c2e65',
          800: '#0a244d',
          900: '#081a39',
        },
        'coral-pink': {
          DEFAULT: '#ff6b6b',
          50: '#fff5f5',
          100: '#ffe0e0',
          200: '#ffc9c9',
          300: '#ffb2b2',
          400: '#ff8787',
          500: '#ff6b6b',
          600: '#fa5252',
          700: '#f03e3e',
          800: '#e03131',
          900: '#c92a2a',
        },
        'teal': {
          DEFAULT: '#17a2b8',
          50: '#f0fafb',
          100: '#dff1f3',
          200: '#afe3e8',
          300: '#7dd5de',
          400: '#4ac7d1',
          500: '#17a2b8',
          600: '#1589a0',
          700: '#117084',
          800: '#0e576a',
          900: '#0a3e50',
        },
        'dark-slate': {
          950: '#020617',
          900: '#0f172a',
          800: '#1e293b',
        },
      },
      backdropFilter: {
        'none': 'none',
        'sm': 'blur(4px)',
        'md': 'blur(12px)',
        'lg': 'blur(20px)',
        'xl': 'blur(30px)',
      },
      boxShadow: {
        'luxury-sm': '0 4px 20px rgba(0, 0, 0, 0.1)',
        'luxury-md': '0 8px 32px rgba(0, 0, 0, 0.15)',
        'luxury-lg': '0 20px 60px rgba(0, 0, 0, 0.2)',
        'luxury-pink': '0 20px 60px rgba(255, 107, 107, 0.2)',
        'luxury-teal': '0 20px 60px rgba(23, 162, 184, 0.2)',
        'glow-pink': '0 0 40px rgba(255, 107, 107, 0.3)',
        'glow-teal': '0 0 40px rgba(23, 162, 184, 0.3)',
        'glow-blue': '0 0 40px rgba(15, 58, 125, 0.3)',
      },
      animation: {
        'float': 'float 20s ease-in-out infinite',
        'float-slow': 'float 25s ease-in-out infinite',
        'float-slower': 'float 30s ease-in-out infinite',
        'moon-oscillate': 'moonOscillate 8s ease-in-out infinite',
        'rotate-slow': 'rotate 30s linear infinite',
        'rotate-slower': 'rotate 40s linear infinite',
        'slide-up': 'slideUp 1s ease-out',
        'fade-in-up': 'fadeInUp 0.8s ease-out',
        'scale-up': 'scaleUp 0.6s ease-out',
        'slide-in-card': 'slideInCard 0.6s ease-out',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0px) translateX(0px)' },
          '50%': { transform: 'translateY(30px) translateX(20px)' },
        },
        moonOscillate: {
          '0%, 100%': { transform: 'translateY(0) rotate(0deg)' },
          '50%': { transform: 'translateY(20px) rotate(5deg)' },
        },
        slideUp: {
          from: {
            opacity: '0',
            transform: 'translateY(40px)',
          },
          to: {
            opacity: '1',
            transform: 'translateY(0)',
          },
        },
        fadeInUp: {
          from: {
            opacity: '0',
            transform: 'translateY(30px)',
          },
          to: {
            opacity: '1',
            transform: 'translateY(0)',
          },
        },
        scaleUp: {
          from: {
            opacity: '0',
            transform: 'scale(0.5)',
          },
          to: {
            opacity: '1',
            transform: 'scale(1)',
          },
        },
        slideInCard: {
          from: {
            opacity: '0',
            transform: 'translateY(40px)',
          },
          to: {
            opacity: '1',
            transform: 'translateY(0)',
          },
        },
      },
      typography: {
        DEFAULT: {
          css: {
            color: 'white',
            h1: {
              color: 'white',
              fontWeight: '700',
            },
            h2: {
              color: 'white',
              fontWeight: '700',
            },
            h3: {
              color: 'white',
              fontWeight: '700',
            },
          },
        },
      },
    },
  },
};
