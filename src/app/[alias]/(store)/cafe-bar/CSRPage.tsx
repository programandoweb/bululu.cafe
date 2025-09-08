'use client'

import HeroCollage from "@/components/Hero/HeroCollage"
import HeroText from "@/components/Hero/HeroText"
import GoogleMaps from "@/components/Maps/GoogleMaps";

const googleMapEmbedCode = '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d248.4784323846278!2d-75.67618184866026!3d4.8291803229034675!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sco!4v1756507880154!5m2!1ses-419!2sco" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';

export default function Hero() {
  return (
    <section className="relative overflow-hidden">
      {/* Fondo gradiente */}
      <div className="absolute inset-0 -z-20 bg-hero-gradient" />

      {/* Overlay oscuro para contraste */}
      <div className="absolute inset-0 -z-10 bg-heroOverlay" />

      <div className="mx-auto max-w-7xl px-4 py-16 md:py-24 grid md:grid-cols-2 gap-10 items-center text-white">
        <HeroText />
        <HeroCollage />
        <GoogleMaps embedHtml={googleMapEmbedCode} />
      </div>
    </section>
  )
}
