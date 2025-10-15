import Link from "next/link";

export default function HeroText() {
  return (
    <div className="text-center">
      <h1 className="text-5xl md:text-7xl font-extrabold text-bululu.orange mb-4 tracking-wide drop-shadow-lg">
        Bululú Café Bar
      </h1>
      <p className="text-lg text-white/80 max-w-2xl mx-auto mb-8">
        Donde la buena vibra, las micheladas frías y el karaoke se unen para crear noches inolvidables en Milán, Dosquebradas.
      </p>
      <a
        href="#menu"
        className="bg-bululu.orange text-bululu.black font-semibold px-8 py-3 rounded-full shadow-glow hover:bg-white transition-all duration-300"
      >
        Ver menú 🍺
      </a>
      <Link
        href="./registro"
        className="bg-bululu.orange text-bululu.black font-semibold px-8 py-3 rounded-full shadow-glow hover:bg-black transition-all duration-300"
      >
        Regístrate 🍺
      </Link>
    </div>
  )
}
