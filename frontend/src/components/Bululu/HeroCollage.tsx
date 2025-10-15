import Image from 'next/image'

export default function HeroCollage() {
  return (
    <div className="mt-12 grid grid-cols-3 gap-4 max-w-lg mx-auto">
      <Image
        src="/img/desing/bululu/hero-10.png"
        alt="Michelada fría"
        width={200}
        height={200}
        className="rounded-2xl shadow-glow border-2 border-bululu.orange object-cover"
      />
      <Image
        src="/img/desing/bululu/hero-20.png"
        alt="Amigos en Bululú"
        width={200}
        height={200}
        className="rounded-2xl shadow-glow border-2 border-bululu.orange object-cover"
      />
      <Image
        src="/img/desing/bululu/hero-30.png"
        alt="Karaoke"
        width={200}
        height={200}
        className="rounded-2xl shadow-glow border-2 border-bululu.orange object-cover"
      />
    </div>
  )
}
