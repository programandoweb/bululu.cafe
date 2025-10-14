'use client'

import { NextPage } from 'next'
import { motion } from 'framer-motion'
import { FiUserPlus, FiThumbsUp, FiShare2 } from 'react-icons/fi'
import Image from 'next/image' // Importa el componente Image de Next.js
import Link from 'next/link'; // <--- 1. ¡No olvides importar Link!


const CSRRegister: NextPage = () => {
  return (
    <section className="relative flex items-center justify-center min-h-screen w-full overflow-hidden text-white">
      {/* Background Image */}
      {/* Usamos el componente Image de Next.js para optimización */}
      <Image
        src="/img/desing/bululu/bululu-bg-registro.jpg" // Asegúrate de que esta ruta sea accesible desde la raíz de tu proyecto 'public'
        alt="Fondo de Bululú Café Bar con personas felices"
        layout="fill" // Esto hace que la imagen ocupe todo el espacio del padre
        objectFit="cover" // Cubre el área manteniendo la relación de aspecto
        quality={80} // Puedes ajustar la calidad si es necesario
        className="absolute inset-0 z-0" // Posición absoluta y un z-index bajo
      />

      {/* Background radial light effect */}
      {/* Asegúrate de que este div tenga un z-index ligeramente superior al de la imagen para que el efecto se vea */}
      <div className="absolute inset-0 z-10 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.15),transparent_70%)]"></div>

      {/* Decorative confetti */}
      <div className="absolute top-0 left-0 w-full h-full overflow-hidden z-20"> {/* z-index más alto para los confetis */}
        {[...Array(40)].map((_, i) => (
          <motion.span
            key={i}
            className="absolute w-2 h-2 rounded-full bg-yellow-400 opacity-70"
            style={{
              left: `${Math.random() * 100}%`,
              top: `${Math.random() * 100}%`,
            }}
            animate={{
              y: [0, 20, 0],
              opacity: [0.8, 0.2, 0.8],
            }}
            transition={{
              duration: 3 + Math.random() * 2,
              repeat: Infinity,
              delay: Math.random() * 2,
            }}
          />
        ))}
      </div>

      {/* Main content */}
      <motion.div
        initial={{ scale: 0.9, opacity: 0 }}
        animate={{ scale: 1, opacity: 1 }}
        transition={{ duration: 0.6 }}
        className="relative z-30 max-w-3xl w-full px-8 py-10 bg-gradient-to-b from-purple-800/70 to-purple-900/80 rounded-3xl shadow-2xl border border-purple-400/30 text-center"
      >
        {/* Header */}
        <motion.div
          initial={{ y: -20, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          className="mb-10"
        >
            <div className="flex justify-center">
                <Image src="/img/logo.png" alt='' width={300} height={100}/>
            </div>
            <h2 className="text-yellow-400 text-2xl font-bold mt-3">
                ¡Regístrate y gana un Cóctel Bululú Gratis!
            </h2>
            <p className="text-gray-200 mt-2">
                Participa además por <span className="text-yellow-400">$100.000</span> el 20 de diciembre con la Lotería de Risaralda
            </p>
        </motion.div>

        {/* Steps */}
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6">
          {[
            { icon: <FiUserPlus size={36} />, text: 'Regístrate' },
            { icon: <FiThumbsUp size={36} />, text: 'Disfruta tu Cóctel' },
            { icon: <FiShare2 size={36} />, text: 'Participa por $100.000' },
          ].map((step, i) => (
            <motion.div
              key={i}
              whileHover={{ scale: 1.05 }}
              className="flex flex-col items-center justify-center bg-purple-700/40 rounded-2xl p-6 shadow-lg border border-purple-500/30 hover:border-yellow-400 transition-all"
            >
              <div className="text-yellow-400 mb-3">{step.icon}</div>
              <p className="font-semibold text-lg">{step.text}</p>
            </motion.div>
          ))}
        </div>

        {/* Call to action */}
        <Link 
          href="/bululu-cafe-bar-dosquebradas-que-hacer-en-dosquebras-pereira/registro/formulario" 
          passHref // Recomendado para asegurar que la referencia se pase correctamente a children          
        >
          <motion.div
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.97 }}
            // Aquí van las clases de estilo del botón
            className="mt-10 px-8 py-4 bg-yellow-400 text-purple-900 font-bold rounded-full text-lg uppercase tracking-wide shadow-lg hover:bg-yellow-300 transition-all cursor-pointer inline-block" 
          >
            <span className="block">¡Regístrate Ahora!</span> {/* Envuelve el texto para asegurar que se comporte como bloque dentro de la div */}
          </motion.div>
        </Link>

        {/* Footer */}
        <p className="mt-8 text-sm text-gray-300">
          Bululú Café Bar – Milán, Dosquebradas 🍹 | Promoción válida hasta el 20 de diciembre
        </p>
      </motion.div>
    </section>
  )
}

export default CSRRegister