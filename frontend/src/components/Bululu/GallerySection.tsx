'use client'

import Image from 'next/image'
import { motion } from 'framer-motion'

const gallery = [
  '/img/gallery/bar.jpeg',
  '/img/gallery/bar2.jpeg',
  '/img/gallery/bar3.jpeg',
  '/img/gallery/bar4.jpeg',
  '/img/gallery/bar5.jpeg',
  '/img/gallery/bar6.jpeg',
  
]

export default function GallerySection() {
  return (
    <section className="py-20 bg-black px-6">
      <h2 className="text-4xl font-bold text-center text-fuchsia-300 mb-10">Galería</h2>
      <div className="grid grid-cols-2 md:grid-cols-3 gap-4 max-w-6xl mx-auto">
        {gallery.map((src) => (
          <motion.div
            key={src}
            className="overflow-hidden rounded-xl shadow-lg"
            whileHover={{
              scale: 1.05,
              rotate: 2,
              transition: { type: 'spring', stiffness: 200, damping: 10 },
            }}
          >
            <Image
              src={src}
              alt="Bululú Bar"
              width={400}
              height={400}
              className="rounded-xl object-cover w-full h-full"
            />
          </motion.div>
        ))}
      </div>
    </section>
  )
}
