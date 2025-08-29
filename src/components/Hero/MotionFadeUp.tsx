'use client'

import { motion, easeOut } from 'framer-motion'
import { PropsWithChildren } from 'react'

const fadeUp = {
  initial: { opacity: 0, y: 18 },
  animate: { opacity: 1, y: 0 },
  transition: { duration: 0.6, ease: easeOut },
}

export default function MotionFadeUp({ children }: PropsWithChildren) {
  return (
    <motion.div
      initial={fadeUp.initial}
      animate={fadeUp.animate}
      transition={fadeUp.transition}
    >
      {children}
    </motion.div>
  )
}
