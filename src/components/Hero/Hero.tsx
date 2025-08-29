'use client'

import HeroText from './HeroText'
import HeroCollage from './HeroCollage'

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
      </div>
    </section>
  )
}
