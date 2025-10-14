import { motion } from 'framer-motion'

export default function AboutSection() {
  return (
    <section className="py-20 bg-gradient-to-b from-black to-purple-950 text-center px-6">
      <motion.h2
        initial={{ opacity: 0, y: 40 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true }}
        className="text-4xl font-bold text-fuchsia-300 mb-6"
      >
        Sobre Bululú
      </motion.h2>
      <p className="max-w-3xl mx-auto text-white/80 text-lg leading-relaxed">
        Bululú café bar! Tu lugar de encuentro en Dosquebradas, ubicado en Milán.
        <br/>
        En Bululú, te invitamos a disfrutar de una experiencia completa, con una amplia variedad de opciones para cualquier momento del día o de la noche. Nuestro menú se adapta a todos los gustos
      </p>
    </section>
  )
}
