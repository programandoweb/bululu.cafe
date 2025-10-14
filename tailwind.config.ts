import type { Config } from "tailwindcss";

const config: Config = {
  darkMode: "class",
  content: [
    "./src/pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/components/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      backgroundImage: {
        "gradient-radial": "radial-gradient(var(--tw-gradient-stops))",
        "gradient-conic":
          "conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))",
        "hero-gradient":
          "linear-gradient(180deg, #000000 0%, #1a1a1a 50%, #000000 100%)",
      },
      colors: {
        bululu: {
          orange: "#F7941D",
          black: "#000000",
          white: "#FFFFFF",
        },
        overlay: {
          light: "rgba(0,0,0,0.4)",
          dark: "rgba(0,0,0,0.7)",
        },
        heroOverlay: "rgba(0,0,0,0.65)",
      },
      boxShadow: {
        glow: "0 0 20px rgba(247, 148, 29, 0.4)",
        "3xl": "14px 17px 40px 4px rgba(0,0,0,0.25)",
        inset: "inset 0px 18px 22px rgba(0,0,0,0.15)",
        darkinset: "0px 4px 4px inset rgba(0,0,0,0.25)",
      },
      borderRadius: {
        primary: "20px",
      },
      fontFamily: {
        poppins: ["Poppins", "sans-serif"],
        dm: ["DM Sans", "sans-serif"],
      },
      width: Object.fromEntries(
        Array.from({ length: 99 }, (_, i) => [`${i + 1}p`, `${i + 1}%`])
      ),
    },
    screens: {
      sm: "576px",
      "sm-max": { max: "576px" },
      md: "768px",
      "md-max": { max: "768px" },
      lg: "992px",
      "lg-max": { max: "992px" },
      xl: "1200px",
      "xl-max": { max: "1200px" },
      "2xl": "1320px",
      "2xl-max": { max: "1320px" },
      "3xl": "1600px",
      "3xl-max": { max: "1600px" },
      "4xl": "1850px",
      "4xl-max": { max: "1850px" },
    },
  },
  plugins: [],
};

export default config;
