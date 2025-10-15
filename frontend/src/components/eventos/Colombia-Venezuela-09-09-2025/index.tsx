'use client'

const googleMapEmbedCode =
  '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d248.4784323846278!2d-75.67618184866026!3d4.8291803229034675!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sco!4v1756507880154!5m2!1ses-419!2sco" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';

export default function LandingPartidoColombiaVenezuela() {
  return (
    <main className="font-sans text-gray-900">
      {/* Hero vh-100 con imagen responsive (9:16 móvil, landscape en md+) */}
      <section
        className="
          relative h-screen flex items-center bg-center bg-no-repeat bg-cover
          bg-[url('/img/desing/partido-colombia-venezuela/colombia-venezuela-9x16-hd.jpg')]
          md:bg-[url('/img/desing/partido-colombia-venezuela/esvdm3vfdk72t9vpnhr5abpxw1757201432.jpg.webp')]
        "
      >
        <div className="absolute inset-0 bg-black/50" />
        <div className="relative max-w-4xl mx-auto px-6 text-center text-white">
          <h1 className="text-4xl md:text-6xl font-bold mb-4">
            ¡Vive Colombia vs Venezuela en Bululú Café Bar!
          </h1>
          <p className="text-xl md:text-2xl">
            Emoción, ambiente y pasión desde las 4 p.m. — acompáñanos en Dosquebradas
          </p>
        </div>
      </section>

      {/* Información del partido */}
      <section className="py-16 bg-gray-100">
        <div className="max-w-3xl mx-auto text-center space-y-4">
          <h2 className="text-3xl font-semibold">Partido Eliminatorio</h2>
          <p className="text-lg">
            <strong>Colombia vs Venezuela</strong>
            <br />
            Fecha: martes 9 de septiembre de 2025 — 6:30 p.m. (hora colombiana)
            <br />
            Estadio: Monumental de Maturín, Venezuela
            <br />
            Transmisión: Caracol TV y RCN TV
          </p>
        </div>
      </section>

      {/* Ubicación */}
      <section className="py-16 px-4">
        <div className="max-w-4xl mx-auto grid md:grid-cols-2 gap-8 items-center">
          <div className="space-y-4">
            <h3 className="text-2xl font-semibold">Nuestra ubicación</h3>
            <p className="text-lg">
              Bululú Café Bar Millán Dosquebradas
              <br />
              Dirección: Dg. 25f #17T-129 local 3, Dosquebradas, Risaralda
            </p>
          </div>
          <div
            className="rounded-lg overflow-hidden shadow-lg"
            dangerouslySetInnerHTML={{ __html: googleMapEmbedCode }}
          />
        </div>
      </section>

      {/* Contacto */}
      <section className="py-16 bg-gray-100">
        <div className="max-w-3xl mx-auto text-center space-y-4">
          <h3 className="text-2xl font-semibold">Contáctanos</h3>
          <p className="text-lg">
            Teléfono:{' '}
            <a href="tel:3115000926" className="text-blue-600 hover:underline">
              311 500 0926
            </a>
          </p>
          <p className="text-lg">¿Tienes dudas? ¡Llámanos!</p>
        </div>
      </section>
    </main>
  );
}
