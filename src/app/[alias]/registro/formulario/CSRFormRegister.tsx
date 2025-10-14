'use client'

import { NextPage } from 'next'
import { motion } from 'framer-motion'
import Image from 'next/image' 

const CSRFormRegister: NextPage = () => {
  // Puedes manejar el estado del formulario aquí si quieres ser más avanzado,
  // pero para la estructura básica, no es estrictamente necesario.

  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    // Aquí iría la lógica para enviar los datos a tu API.
    console.log("Formulario enviado. ¡Usuario registrado y ganando un cóctel!");
  };

  // Componente de Input reutilizable
  const InputField = ({ label, type, name, placeholder }: { label: string, type: string, name: string, placeholder: string }) => (
    <div className="text-left mb-4 md:mb-5"> {/* Espacio ajustado */}
      <label htmlFor={name} className="block text-xs md:text-sm font-medium text-yellow-400 mb-1 md:mb-2"> {/* Texto más pequeño en móvil */}
        {label}
      </label>
      <input
        type={type}
        id={name}
        name={name}
        placeholder={placeholder}
        required
        // Clases ajustadas: px/py más pequeños y texto de input responsivo
        className="w-full px-4 py-2 text-base md:text-lg text-white bg-purple-700/60 border border-purple-500/50 rounded-lg md:rounded-xl focus:ring-yellow-400 focus:border-yellow-400 transition-all placeholder-gray-400 shadow-inner"
      />
    </div>
  );

  return (
    <section className="relative flex items-center justify-center min-h-screen w-full overflow-hidden text-white p-4"> {/* Añadido padding de 4 para seguridad en móviles */}
      {/* Background Image */}
      <Image
        src="/img/desing/bululu/bululu-bg-registro.jpg" 
        alt="Fondo de Bululú Café Bar con personas felices"
        layout="fill" 
        objectFit="cover" 
        quality={80} 
        className="absolute inset-0 z-0" 
      />

      {/* Background radial light effect */}
      <div className="absolute inset-0 z-10 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.15),transparent_70%)]"></div>

      {/* Decorative confetti */}
      <div className="absolute top-0 left-0 w-full h-full overflow-hidden z-20">
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
        // AJUSTE CLAVE 1: Reducción del max-w (de lg a md)
        className="relative z-30 max-w-sm md:max-w-md w-full px-6 py-8 md:px-8 md:py-10 bg-gradient-to-b from-purple-800/70 to-purple-900/80 rounded-2xl md:rounded-3xl shadow-2xl border border-purple-400/30 text-center"
      >
        {/* Header */}
        <motion.div
          initial={{ y: -20, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          className="mb-8 md:mb-10" // Margen inferior ajustado
        >
            <div className="flex justify-center mb-4">
                {/* AJUSTE CLAVE 2: Logo más pequeño para reducir el espacio */}
                <Image src="/img/logo.png" alt='Logo Bululú' width={200} height={70}/> 
            </div>
            <h1 className="text-2xl md:text-3xl font-extrabold tracking-tight uppercase">
                ¡Tu Premio te espera!
            </h1>
            <h2 className="text-yellow-400 text-lg md:text-xl font-bold mt-1 md:mt-2">
                Completa tus datos para reclamar tu Cóctel Gratis.
            </h2>
            <p className="text-gray-200 mt-1 text-xs md:text-sm">
                Además, participas por <span className="text-yellow-400 font-bold">$100.000</span> el 20 de diciembre.
            </p>
        </motion.div>

        {/* Formulario de Registro */}
        <form onSubmit={handleSubmit} className="w-full mt-6 md:mt-8">
            <InputField 
                label="Nombre Completo" 
                type="text" 
                name="name" 
                placeholder="Ej: Juan Pérez" 
            />
            <InputField 
                label="Email" 
                type="email" 
                name="email" 
                placeholder="ejemplo@correo.com" 
            />
            <InputField 
                label="Celular / WhatsApp" 
                type="tel" 
                name="phone" 
                placeholder="Ej: 300 123 4567" 
            />

            {/* Botón de Enviar */}
            <motion.button
                type="submit"
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.97 }}
                // Tamaño de botón ajustado y responsivo
                className="mt-6 w-full px-6 py-3 text-lg md:text-xl bg-yellow-400 text-purple-900 font-bold rounded-full uppercase tracking-wide shadow-2xl hover:bg-yellow-300 transition-all transform hover:shadow-yellow-500/50"
            >
                ¡Reclamar Cóctel y Participar!
            </motion.button>
        </form>

        {/* Footer */}
        <p className="mt-6 text-xs md:text-sm text-gray-300">
          *Al registrarte aceptas recibir promociones de Bululú Café Bar.
        </p>
      </motion.div>
    </section>
  )
}

export default CSRFormRegister