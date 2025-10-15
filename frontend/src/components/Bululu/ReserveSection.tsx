'use client'

import { motion } from 'framer-motion'
import { FaWhatsapp } from 'react-icons/fa'

export default function ReserveSection() {
  const handleReserve = () => {
    const message = encodeURIComponent('Hola, quiero reservar una mesa en Bululú Café Bar 🍹')
    const whatsappUrl = `https://wa.me/573115000926?text=${message}`
    window.open(whatsappUrl, '_blank')
  }

  return (
    <section className="py-20 bg-gradient-to-b from-black to-fuchsia-950 text-center">
      <motion.h2
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.5 }}
        className="text-4xl font-bold text-fuchsia-300 mb-6"
      >
        ¡Reserva tu mesa ahora!
      </motion.h2>

      <motion.p
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ delay: 0.2, duration: 0.5 }}
        className="text-white/80 mb-10 max-w-md mx-auto"
      >
        Disfruta de micheladas, cocteles y buena música en el mejor ambiente de Dosquebradas.
      </motion.p>

      <motion.button
        whileHover={{ scale: 1.05 }}
        whileTap={{ scale: 0.95 }}
        onClick={handleReserve}
        className="flex items-center gap-3 mx-auto px-8 py-4 bg-fuchsia-600 hover:bg-fuchsia-700 text-white font-semibold rounded-full shadow-lg"
      >
        <FaWhatsapp className="text-2xl" />
        Reservar por WhatsApp
      </motion.button>
    </section>
  )
}
