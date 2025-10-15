export default function GoogleMaps() {
  return (
    <section className="py-10 bg-black text-center">
      <h2 className="text-3xl font-bold text-fuchsia-300 mb-6">Encuéntranos</h2>
      <div className="max-w-4xl mx-auto">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d248.4784323846278!2d-75.67618184866026!3d4.8291803229034675!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sco!4v1756507880154!5m2!1ses-419!2sco"
          width="100%"
          height="400"
          style={{ border: 0 }}
          loading="lazy"
          allowFullScreen
          referrerPolicy="no-referrer-when-downgrade"
        ></iframe>
      </div>
    </section>
  )
}
