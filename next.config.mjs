import withPWAInit from "@ducanh2912/next-pwa";

const withPWA = withPWAInit({
  dest: "public",
});

export default withPWA({
  images: {
    // Aquí definimos los patrones de acceso remoto a las imágenes
    // Ajusta según los hosts y dominios desde donde se deben cargar las imágenes
    remotePatterns: [
      {
        protocol: "http",
        hostname: "localhost",
      },
      {
        protocol: "https",
        hostname: "*.programandoweb.net",
      },
      {
        protocol: "https",
        hostname: "jorgedev.pro",
      },
      {
        protocol: "https",
        hostname: "*.jorgedev.pro",
      },
      {
        protocol: "https",
        hostname: "**", // Permitir acceso remoto desde cualquier host
        port: "", // Opcional: definir un puerto específico si es necesario
        pathname: "**", // Opcional: definir una ruta específica si es necesario
      },
    ],
  },
});