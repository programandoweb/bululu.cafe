/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular: 3115000926
 *  website: Programandoweb.net
 *  Proyecto: Bululú Café Bar - Store
 * ---------------------------------------------------
 */

import type { Metadata } from "next";

const name          =   "¡Regístrate y gana un Cóctel Bululú Gratis! en Bululú Café Bar Milán Dosquebradas";
const baseTitle     =   process.env.NEXT_PUBLIC_NAME;
const title         =   `${baseTitle} ${name}`;
const description   =   'Gana un Cóctel Bululú Gratis y participa además por $100.000 el 20 de diciembre con la Lotería de Risaralda';
const image         =   "https://www.bululu.cafe/img/desing/bululu/bululu-bg-registro.jpg";
const url           =   "https://www.bululu.cafe/bululu-cafe-bar-dosquebradas-que-hacer-en-dosquebras-pereira/registro";
const fbAppId       =   process.env.NEXT_PUBLIC_FB_APP_ID || "";

export const metadata: Metadata = {
  title,
  description,
  applicationName: title,
  keywords: ["store", "ecommerce", "bululu", "nextjs", "tailwind"],
  generator: process.env.NEXT_PUBLIC_GENERATOR,
  authors: [{ name: process.env.NEXT_PUBLIC_AUTHOR, url: process.env.NEXT_PUBLIC_AUTHOR_URL }],
  creator: process.env.NEXT_PUBLIC_AUTHOR,
  manifest: "/manifest.json",

  icons: [
    { rel: "apple-touch-icon", type: "image/png", url: "/img/horizon.png" }
  ],

  openGraph: {
    title,
    description,
    url,
    type: "website",
    images: [
      {
        url: image,
        width: 1200,
        height: 630,
        alt: "Imagen Open Graph",
      },
    ],
  },

  twitter: {
    card: "summary_large_image",
    title,
    description,
    images: [image],
  },

  other: {
    ["fb:app_id"]: fbAppId,
  },
};

export const viewport = {
  width: "device-width",
  initialScale: 1,
  maximumScale: 5,
  userScalable: true,
  colorScheme: "#fff",
};

export default function RootLayout({
  children,
}: Readonly<{ children: React.ReactNode }>) {
  return children
}
