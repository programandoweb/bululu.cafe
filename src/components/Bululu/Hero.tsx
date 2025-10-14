'use client'

import { motion } from 'framer-motion'
import { FiArrowDown } from 'react-icons/fi'
import HeroCollage from './HeroCollage'
import HeroText from './HeroText'

export default function Hero() {
  return (
    <section className="relative h-screen flex items-center justify-center overflow-hidden bg-gradient-to-b from-bululu.black via-black to-bululu.black">
      {/* Fondo dinámico con imagen libre */}
      <div className="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1557683316-973673baf926?q=80&w=1920')] bg-cover bg-center opacity-30"></div>

      {/* Overlay oscuro para contraste */}
      <div className="absolute inset-0 bg-heroOverlay"></div>

      <motion.div
        initial={{ opacity: 0, y: 40 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.8 }}
        className="relative z-10 max-w-6xl px-6 text-center"
      >
        <HeroText />
        <HeroCollage />
        <motion.div
          animate={{ y: [0, 10, 0] }}
          transition={{ repeat: Infinity, duration: 2 }}
          className="mt-10 flex justify-center"
        >
          <FiArrowDown className="text-3xl text-bululu.orange" />
        </motion.div>
      </motion.div>
    </section>
  )
}
