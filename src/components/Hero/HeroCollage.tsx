'use client'

import { motion, easeOut } from 'framer-motion'

export default function HeroCollage() {
  return (
    <motion.div
      className="relative h-[460px] md:h-[520px]"
      initial={{ opacity: 0, y: 18 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      transition={{ duration: 0.7, ease: easeOut }}
    >
      <div className="absolute inset-0 grid grid-cols-3 gap-3">
        <div className="col-span-2 grid grid-rows-2 gap-3">
          <div className="rounded-2xl bg-[url('/img/desing/bululu/hero-1.jpg')] bg-cover bg-center" />
          <div className="rounded-2xl bg-[url('/img/desing/bululu/hero-2.jpg')] bg-cover bg-center" />
        </div>
        <div className="grid grid-rows-3 gap-3">
          <div className="rounded-2xl bg-[url('/img/desing/bululu/hero-3.jpg')] bg-cover bg-center" />
          <div className="rounded-2xl bg-[url('/img/desing/bululu/hero-4.jpg')] bg-cover bg-center" />
          <div className="rounded-2xl bg-[url('/img/desing/bululu/hero-5.jpg')] bg-cover bg-center" />
        </div>
      </div>

    </motion.div>
  )
}
