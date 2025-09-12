'use client'

const googleMapEmbedCode =
  '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d248.4784323846278!2d-75.67618184866026!3d4.8291803229034675!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sco!4v1756507880154!5m2!1ses-419!2sco" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';

export default function LandingAmorAmistad() {
  return (
    <main className="font-[Montserrat] text-gray-900">
      {/* Hero principal */}
      <section
        className="
          relative h-screen flex items-center justify-center bg-center bg-no-repeat bg-cover
          bg-[url('/img/love/hero-mobile.png')]
          md:bg-[url('/img/love/hero-desktop.png')]
        "
      >
        <div className="absolute inset-0 bg-gradient-to-b from-pink-700/70 to-red-900/70" />
        <div className="relative max-w-3xl mx-auto px-6 text-center text-white">
          <h1 className="font-['Playfair_Display'] text-5xl md:text-7xl font-bold mb-6 leading-tight">
            Celebra el Amor y la Amistad ❤️
          </h1>
          <p className="text-xl md:text-2xl mb-6">
            Sorprende a esa persona especial con un momento inolvidable en Bululú Café Bar.
          </p>
          <a
            href="#reservar"
            className="inline-block bg-pink-500 hover:bg-pink-600 transition text-white font-semibold px-8 py-4 rounded-full shadow-lg"
          >
            Reserva Ahora
          </a>
        </div>
      </section>

      {/* Beneficios */}
      <section className="py-20 bg-pink-50">
        <div className="max-w-5xl mx-auto px-6 text-center">
          <h2 className="font-['Playfair_Display'] text-4xl mb-10 text-pink-800">
            ¿Por qué elegirnos este San Valentín?
          </h2>
          <div className="grid md:grid-cols-3 gap-10">
            <div className="p-6 rounded-xl bg-white shadow-md">
              <h3 className="text-xl font-semibold mb-3">Ambiente Romántico</h3>
              <p className="text-gray-600">
                Luces cálidas, decoración especial y música en vivo para una noche mágica.
              </p>
            </div>
            <div className="p-6 rounded-xl bg-white shadow-md">
              <h3 className="text-xl font-semibold mb-3">Menú Exclusivo</h3>
              <p className="text-gray-600">
                Platos y cócteles diseñados especialmente para compartir en pareja o con amigos.
              </p>
            </div>
            <div className="p-6 rounded-xl bg-white shadow-md">
              <h3 className="text-xl font-semibold mb-3">Momentos Inolvidables</h3>
              <p className="text-gray-600">
                Un espacio para celebrar el cariño y la amistad con recuerdos únicos.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Galería / Productos */}
      <section className="py-20">
        <div className="max-w-6xl mx-auto px-6 text-center">
          <h2 className="font-['Playfair_Display'] text-4xl mb-10 text-pink-800">
            Experiencias Especiales
          </h2>
          <div className="grid md:grid-cols-3 gap-6">
            <img
              src="/img/love/gallery1.png"
              alt="Cena romántica"
              className="rounded-xl shadow-lg object-cover w-full h-80"
            />
            <img
              src="/img/love/gallery2.png"
              alt="Cócteles temáticos"
              className="rounded-xl shadow-lg object-cover w-full h-80"
            />
            <img
              src="/img/love/gallery3.png"
              alt="Decoración romántica"
              className="rounded-xl shadow-lg object-cover w-full h-80"
            />
          </div>
        </div>
      </section>

      {/* Testimonios */}
      <section className="py-20 bg-pink-50">
        <div className="max-w-4xl mx-auto px-6 text-center">
          <h2 className="font-['Playfair_Display'] text-4xl mb-10 text-pink-800">
            Lo que dicen nuestros clientes
          </h2>
          <blockquote className="italic text-lg text-gray-700 mb-6">
            “Una experiencia maravillosa, la decoración y la música hicieron nuestra noche
            inolvidable. ¡Volveremos cada año!”
          </blockquote>
          <p className="font-semibold">— Laura & Andrés</p>
        </div>
      </section>

      {/* Ubicación */}
      <section id="reservar" className="py-20 px-6">
        <div className="max-w-5xl mx-auto grid md:grid-cols-2 gap-10 items-center">
          <div className="space-y-4">
            <h3 className="text-3xl font-semibold text-pink-800">Nuestra ubicación</h3>
            <p className="text-lg text-gray-700">
              Bululú Café Bar Millán Dosquebradas
              <br />
              Dirección: Dg. 25f #17T-129 local 3, Dosquebradas, Risaralda
            </p>
            <a
              href="tel:3115000926"
              className="inline-block mt-4 bg-pink-500 hover:bg-pink-600 text-white font-semibold px-6 py-3 rounded-full"
            >
              📞 Llamar ahora
            </a>
          </div>
          <div
            className="rounded-xl overflow-hidden shadow-lg"
            dangerouslySetInnerHTML={{ __html: googleMapEmbedCode }}
          />
        </div>
      </section>

      {/* CTA final */}
      <section className="py-20 bg-gradient-to-r from-pink-600 to-red-600 text-white text-center">
        <h2 className="text-4xl font-bold mb-6">Haz tu reserva hoy</h2>
        <p className="text-lg mb-8">
          Celebra con nosotros este Día del Amor y la Amistad y crea recuerdos eternos.
        </p>
        <a
          href="tel:3115000926"
          className="bg-white text-pink-700 font-semibold px-10 py-4 rounded-full shadow-lg hover:bg-gray-100"
        >
          Reserva por Teléfono
        </a>
      </section>

      {/* Footer */}
      <footer className="py-10 bg-pink-900 text-center text-pink-100">
        <p>&copy; 2025 Bululú Café Bar — Todos los derechos reservados</p>
      </footer>
    </main>
  );
}
