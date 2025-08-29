'use client'

import { FaBeer } from 'react-icons/fa'
import { FiCoffee, FiMusic } from 'react-icons/fi'
import MotionFadeUp from './MotionFadeUp'

export default function HeroText() {
  return (
    <MotionFadeUp>
      <p className="uppercase tracking-[0.28em] text-xs text-fuchsia-300/80 mb-3">
        Café • Coctelería • Buen parche
      </p>
      <h1 className="text-4xl md:text-6xl font-extrabold leading-tight">
        El spot para <span className="text-fuchsia-300">brindar, reír</span> y quedarte.
      </h1>
      <p className="mt-4 text-white/80 md:text-lg max-w-xl">
        Cerveza helada, micheladas y cocteles de autor — todo en un ambiente cálido y con buena música.
      </p>

      {/* Botones */}
      <div className="mt-6 flex flex-wrap items-center gap-3">
        <a
          href="#menu"
          className="rounded-full bg-fuchsia-500 px-6 py-3 font-semibold hover:bg-fuchsia-400 transition"
        >
          Ver menú
        </a>
        <a
          href="#visitanos"
          className="rounded-full border border-white/20 px-6 py-3 font-semibold hover:bg-white/10 transition"
        >
          Cómo llegar
        </a>
      </div>

      {/* Badges */}
      <div className="mt-8 flex flex-wrap gap-3 text-xs">
        <span className="rounded-full bg-white/10 border border-white/20 px-3 py-1 inline-flex items-center gap-2">
          <FaBeer /> Cervezas frías
        </span>
        <span className="rounded-full bg-white/10 border border-white/20 px-3 py-1 inline-flex items-center gap-2">
          <FiCoffee /> Café y brunch
        </span>
        <span className="rounded-full bg-white/10 border border-white/20 px-3 py-1 inline-flex items-center gap-2">
          <FiMusic /> Música en vivo
        </span>
      </div>
    </MotionFadeUp>
  )
}
