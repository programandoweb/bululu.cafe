'use client'

/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Proyecto: Bululú - Registro Promoción Cóctel
 * ---------------------------------------------------
 */

import { NextPage } from 'next'
import { motion } from 'framer-motion'
import Image from 'next/image'
import { useState, useEffect } from 'react'
import useFormData from '@/hooks/useFormDataNew'

type Attrib = {
  utm_source?: string
  utm_medium?: string
  utm_campaign?: string
  utm_term?: string
  utm_content?: string
  fbclid?: string
  gclid?: string
  referrer?: string
  detected_source?: string
  first_touch?: string
}

const STORAGE_KEY = 'marketing_attrib'

const CSRFormRegister: NextPage = () => {
  const formData = useFormData()
  const [inputs, setInputs] = useState<any>({
    id: '',
    name: '',
    email: '',
    phone: '',
  })
  const [message, setMessage] = useState<any>({})
  const [attrib, setAttrib] = useState<Attrib | null>(null)

  // helpers
  const parseQuery = (q: string) =>
    q
      .replace(/^\?/, '')
      .split('&')
      .filter(Boolean)
      .reduce<Record<string, string>>((acc, pair) => {
        const [k, v] = pair.split('=')
        acc[decodeURIComponent(k)] = decodeURIComponent((v || '').replace(/\+/g, ' '))
        return acc
      }, {})

  const detectSourceFromReferrer = (ref?: string) => {
    if (!ref) return undefined
    const r = ref.toLowerCase()
    if (r.includes('facebook.com')) return 'facebook'
    if (r.includes('instagram.com')) return 'instagram'
    if (r.includes('tiktok.com')) return 'tiktok'
    if (r.includes('google')) return 'google'
    if (r.includes('wa.me') || r.includes('api.whatsapp') || r.includes('whatsapp')) return 'whatsapp'
    return 'referral'
  }

  // On mount: read UTM / params / referrer and persist them
  useEffect(() => {
    try {
      const qs = typeof window !== 'undefined' ? window.location.search : ''
      const params = parseQuery(qs)

      const candidate: Attrib = {
        utm_source: params.utm_source || params.source || undefined,
        utm_medium: params.utm_medium || undefined,
        utm_campaign: params.utm_campaign || undefined,
        utm_term: params.utm_term || undefined,
        utm_content: params.utm_content || undefined,
        fbclid: params.fbclid || undefined,
        gclid: params.gclid || undefined,
        referrer: typeof document !== 'undefined' ? document.referrer || undefined : undefined,
      }

      // heurística para origen detectado
      candidate.detected_source =
        params.utm_source ||
        params.source ||
        params.fbclid ? 'facebook' :
        params.gclid ? 'google' :
        detectSourceFromReferrer(candidate.referrer) ||
        'direct'

      // first_touch: si no existe en localStorage lo guardamos como first_touch con timestamp
      const stored = localStorage.getItem(STORAGE_KEY)
      if (!stored) {
        candidate.first_touch = new Date().toISOString()
        localStorage.setItem(STORAGE_KEY, JSON.stringify(candidate))
        setAttrib(candidate)
      } else {
        const parsed = JSON.parse(stored) as Attrib
        // actualizamos si vienen nuevos params (pero mantenemos first_touch)
        const merged = { ...parsed, ...candidate, first_touch: parsed.first_touch || new Date().toISOString() }
        localStorage.setItem(STORAGE_KEY, JSON.stringify(merged))
        setAttrib(merged)
      }
    } catch (err) {
      // no interrumpir la UX por errores de parsing
      console.error('attrib parse error', err)
    }
  }, [])

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault()

    // adjunta los atributos de marketing al payload
    const marketing = attrib || (localStorage.getItem(STORAGE_KEY) ? JSON.parse(localStorage.getItem(STORAGE_KEY)!) : {})

    const payload = {
      ...inputs,
      marketing,
    }

    const res = await formData.handleRequest(
      formData.backend + '/auth/register',
      inputs.id ? 'put' : 'post',
      payload
    )

    if (res) {
      setMessage({ message: 'Registro enviado correctamente. ¡Buena suerte!' })
      setInputs({ id: '', name: '', email: '', phone: '' })
    }
  }

  useEffect(() => {
    if (message?.message) {
      const timer = setTimeout(() => setMessage({}), 5000)
      return () => clearTimeout(timer)
    }
  }, [message])

  const InputField = ({
    label,
    type,
    name,
    placeholder,
  }: {
    label: string
    type: string
    name: string
    placeholder: string
  }) => (
    <div className="text-left mb-4 md:mb-5">
      <label
        htmlFor={name}
        className="block text-xs md:text-sm font-medium text-yellow-400 mb-1 md:mb-2"
      >
        {label}
      </label>
      <input
        type={type}
        id={name}
        name={name}
        placeholder={placeholder}
        required
        value={inputs[name] || ''}
        onChange={(e) =>
          setInputs((prev: any) => ({ ...prev, [name]: e.target.value }))
        }
        className="w-full px-4 py-2 text-base md:text-lg text-white bg-purple-700/60 border border-purple-500/50 rounded-lg md:rounded-xl focus:ring-yellow-400 focus:border-yellow-400 transition-all placeholder-gray-400 shadow-inner"
      />
    </div>
  )

  return (
    <section className="relative flex items-center justify-center min-h-screen w-full overflow-hidden text-white p-4">
      <Image
        src="/img/desing/bululu/bululu-bg-registro.jpg"
        alt="Fondo de Bululú Café Bar con personas felices"
        layout="fill"
        objectFit="cover"
        quality={80}
        className="absolute inset-0 z-0"
      />

      <div className="absolute inset-0 z-10 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.15),transparent_70%)]"></div>

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

      <motion.div
        initial={{ scale: 0.9, opacity: 0 }}
        animate={{ scale: 1, opacity: 1 }}
        transition={{ duration: 0.6 }}
        className="relative z-30 max-w-sm md:max-w-md w-full px-6 py-8 md:px-8 md:py-10 bg-gradient-to-b from-purple-800/70 to-purple-900/80 rounded-2xl md:rounded-3xl shadow-2xl border border-purple-400/30 text-center"
      >
        <motion.div
          initial={{ y: -20, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          className="mb-8 md:mb-10"
        >
          <div className="flex justify-center mb-4">
            <Image src="/img/logo.png" alt="Logo Bululú" width={200} height={70} />
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

        <form onSubmit={handleSubmit} className="w-full mt-6 md:mt-8">
          <InputField label="Nombre Completo" type="text" name="name" placeholder="Ej: Juan Pérez" />
          <InputField label="Email" type="email" name="email" placeholder="ejemplo@correo.com" />
          <InputField label="Celular / WhatsApp" type="tel" name="phone" placeholder="Ej: 300 123 4567" />

          <motion.button
            type="submit"
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.97 }}
            className="mt-6 w-full px-6 py-3 text-lg md:text-xl bg-yellow-400 text-purple-900 font-bold rounded-full uppercase tracking-wide shadow-2xl hover:bg-yellow-300 transition-all transform hover:shadow-yellow-500/50"
          >
            ¡Reclamar Cóctel y Participar!
          </motion.button>

          {message?.message && (
            <p className="mt-4 text-green-400 text-sm font-semibold">
              {message.message}
            </p>
          )}
        </form>

        <p className="mt-6 text-xs md:text-sm text-gray-300">
          *Al registrarte aceptas recibir promociones de Bululú Café Bar.
        </p>
      </motion.div>
    </section>
  )
}

export default CSRFormRegister
