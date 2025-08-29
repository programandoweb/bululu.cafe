import type { Metadata } from "next";
import { Inter } from "next/font/google";
import Providers from '@/store/provider';
import "./globals.css";

const inter = Inter({ subsets: ["latin"] });

const title         =   process.env.NEXT_PUBLIC_TITLE;
const description   =   process.env.NEXT_PUBLIC_DESCRIPTION;
const image         =   "https://jorgedev.pro/img/profile.jpg";
const url           =   "https://jorgedev.pro";  // Cambia esto por la URL de tu sitio
const fbAppId       =   process.env.NEXT_PUBLIC_FB_APP_ID || ""; 

export const metadata: Metadata = {
  
  title: process.env.NEXT_PUBLIC_NAME+' '+process.env.NEXT_PUBLIC_NAME2,
  description: process.env.NEXT_PUBLIC_NAME,
  applicationName: process.env.NEXT_PUBLIC_NAME+' '+process.env.NEXT_PUBLIC_NAME2,
  keywords: ["react", "server components", 'nextjs', 'tailwind', 'admin', 'dashboard'],
  
  icons: [
    // { rel: "icon", type: 'image/svg', url: "/map/location.svg" },
    { rel: "apple-touch-icon", type: 'image/png', url: "/img/horizon.png" }
  ],
  generator: process.env.NEXT_PUBLIC_GENERATOR,
  authors: [{ name: process.env.NEXT_PUBLIC_AUTHOR, url: process.env.NEXT_PUBLIC_AUTHOR_URL }],
  creator: process.env.NEXT_PUBLIC_AUTHOR, 
  
  manifest:"/manifest.json",

  openGraph: {
    title,
    description,
    url, // Aquí agregamos la URL
    type: "website", // Cambia a "article" si es una página de artículo o blog
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

}

export const viewport = {
  width: 'device-width',
  initialScale: 1,
  maximumScale: 5,
  userScalable: true,  
  colorScheme: '#fff',
}


export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return  <html lang="es" data-iorad-extension="firefox" className="some-class iorad-extension-widget-visible">
            <body id="__next" className={inter.className}>
              <Providers>
                {children}
              </Providers>
            </body>
          </html>    
}
