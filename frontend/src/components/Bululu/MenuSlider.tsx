'use client'

import { useState, useEffect } from 'react'
import { motion } from 'framer-motion'
import { FaChevronLeft, FaChevronRight } from 'react-icons/fa'

const menuItems = [
  { name: 'Michelada Clásica', price: '$12.000', img: '/img/desing/bululu/michelada_clasca.jpg' },
  { name: 'Cerveza Poker', price: '$7.000', img: '/img/desing/bululu/cerveza-poker-botella-x-330ml.jpg' },
  { name: 'Cóctel Bululú', price: '$18.000', img: '/img/desing/bululu/coctel-bululu.png' },
  { name: 'Café Americano', price: '$10.000', img: '/img/desing/bululu/cafe-americano-bululu.jpg' },
]

export default function MenuSlider() {
  const [visibleItems, setVisibleItems] = useState(3)
  const [startIndex, setStartIndex] = useState(0)

  useEffect(() => {
    const handleResize = () => {
      setVisibleItems(window.innerWidth < 768 ? 1 : 3)
    }
    handleResize()
    window.addEventListener('resize', handleResize)
    return () => window.removeEventListener('resize', handleResize)
  }, [])

  const next = () => setStartIndex((i) => (i + 1) % menuItems.length)
  const prev = () => setStartIndex((i) => (i - 1 + menuItems.length) % menuItems.length)

  const itemsToShow = Array.from({ length: visibleItems }, (_, i) => menuItems[(startIndex + i) % menuItems.length])

  return (
    <section id="menu" className="py-20 bg-black text-center">
      <h2 className="text-4xl font-bold mb-10 text-fuchsia-300">Nuestro Menú</h2>
      <div className="relative flex items-center justify-center w-full">
        <button onClick={prev} className="absolute left-4 text-fuchsia-300 text-3xl z-10">
          <FaChevronLeft />
        </button>

        <div className="flex gap-6 justify-center w-full max-w-6xl px-8">
          {itemsToShow.map((item) => (
            <motion.div
              key={item.name}
              initial={{ opacity: 0, scale: 0.9 }}
              animate={{ opacity: 1, scale: 1 }}
              transition={{ duration: 0.5 }}
              className="w-full max-w-sm bg-white/10 rounded-2xl shadow-lg p-4"
            >
              <img src={item.img} alt={item.name} className="rounded-xl mb-4 w-full h-56 object-cover" />
              <h3 className="text-2xl font-semibold text-white">{item.name}</h3>
              <p className="text-fuchsia-300 text-lg mt-2">{item.price}</p>
            </motion.div>
          ))}
        </div>

        <button onClick={next} className="absolute right-4 text-fuchsia-300 text-3xl z-10">
          <FaChevronRight />
        </button>
      </div>
    </section>
  )
}
