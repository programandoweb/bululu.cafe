'use client'

import Hero from '@/components/Bululu/Hero'
import MenuSlider from '@/components/Bululu/MenuSlider'
import AboutSection from '@/components/Bululu/AboutSection'
import GallerySection from '@/components/Bululu/GallerySection'
import GoogleMaps from '@/components/Bululu/GoogleMaps'
import Footer from '@/components/Bululu/Footer'
import ReserveSection from '@/components/Bululu/ReserveSection'

export default function BululuLanding() {
  return (
    <main className="bg-black text-white overflow-hidden">
      <Hero />
      <MenuSlider />
      <AboutSection />
      <GallerySection />
      <ReserveSection/>
      <GoogleMaps />
      <Footer />
    </main>
  )
}
