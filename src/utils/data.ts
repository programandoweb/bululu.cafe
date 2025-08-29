const getRandomImageURL = (id:any, gender:any) => {
    let imageUrl;
    if (gender === 'mujer') {
        imageUrl = `/img/store/outfit/programandoweb-outfit-${id}.jpg`; // Genera una URL de imagen para mujer
    } else {
        imageUrl = `/img/store/outfit/programandoweb-outfit-${id}.jpg`; // Genera una URL de imagen para caballero
    }
    return imageUrl;
};

export const tendencia:any = [
    {
        id: 1,
        name: "Camisa de algodón",
        content: "Descripción del producto...",
        excerpt: "Camisa elegante y cómoda.",
        price: 100,
        slug: "camisa-de-algodon",
        image: getRandomImageURL(1, 'mujer'),
        cover: getRandomImageURL(1, 'mujer'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 2,
        name: "Pantalones vaqueros",
        content: "Descripción del producto...",
        excerpt: "Pantalones modernos y duraderos.",
        price: 120,
        slug: "pantalones-vaqueros",
        image: getRandomImageURL(2, 'hombre'),
        cover: getRandomImageURL(2, 'hombre'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 3,
        name: "Zapatos de cuero",
        content: "Descripción del producto...",
        excerpt: "Zapatos elegantes y cómodos.",
        price: 150,
        slug: "zapatos-de-cuero",
        image: getRandomImageURL(3, 'mujer'),
        cover: getRandomImageURL(3, 'mujer'),
        featured: true,
        store_category_id: 1
    },
    {
        id: 4,
        name: "Sombrero de paja",
        content: "Descripción del producto...",
        excerpt: "Sombrero ligero y fresco para el verano.",
        price: 50,
        slug: "sombrero-de-paja",
        image: getRandomImageURL(4, 'mujer'),
        cover: getRandomImageURL(4, 'mujer'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 5,
        name: "Reloj de pulsera",
        content: "Descripción del producto...",
        excerpt: "Reloj elegante y moderno.",
        price: 200,
        slug: "reloj-de-pulsera",
        image: getRandomImageURL(5, 'hombre'),
        cover: getRandomImageURL(5, 'hombre'),
        featured: true,
        store_category_id: 1
    },
]


export const products: any = [
    {
        id: 1,
        name: "Cupcake de Vainilla",
        content: "Delicioso cupcake de vainilla con glaseado de crema.",
        excerpt: "Cupcake suave y esponjoso.",
        price: 5,
        slug: "cupcake-de-vainilla",
        image: getRandomImageURL(1, 'dulce'),
        cover: getRandomImageURL(1, 'dulce'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 2,
        name: "Cupcake de Chocolate",
        content: "Exquisito cupcake de chocolate con cobertura de ganache.",
        excerpt: "Chocolate intenso y delicioso.",
        price: 4,
        slug: "cupcake-de-chocolate",
        image: getRandomImageURL(2, 'dulce'),
        cover: getRandomImageURL(2, 'dulce'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 3,
        name: "Macarons de Halloween",
        content: "Coloridos macarons con temática de Halloween.",
        excerpt: "Macarons suaves y llenos de sabor.",
        price: 2.5,
        slug: "macarons-de-halloween",
        image: getRandomImageURL(3, 'dulce'),
        cover: getRandomImageURL(3, 'dulce'),
        featured: true,
        store_category_id: 1
    },
    {
        id: 4,
        name: "Gomitas surtidas",
        content: "Selección de gomitas de diferentes sabores y formas.",
        excerpt: "Gomitas frescas y coloridas.",
        price: 3,
        slug: "gomitas-surtidas",
        image: getRandomImageURL(4, 'dulce'),
        cover: getRandomImageURL(4, 'dulce'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 5,
        name: "Pastel de cumpleaños",
        content: "Pastel de vainilla decorado con velas y confites.",
        excerpt: "Pastel perfecto para celebraciones.",
        price: 8,
        slug: "pastel-de-cumpleanos",
        image: getRandomImageURL(5, 'dulce'),
        cover: getRandomImageURL(5, 'dulce'),
        featured: true,
        store_category_id: 1
    },
    {
        id: 6,
        name: "Pastel Unicornio",
        content: "Hermoso pastel temático de unicornio con glaseado de colores.",
        excerpt: "Pastel especial para fiestas temáticas.",
        price: 10,
        slug: "pastel-unicornio",
        image: getRandomImageURL(6, 'dulce'),
        cover: getRandomImageURL(6, 'dulce'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 7,
        name: "Cupcake de Cereza",
        content: "Cupcake esponjoso de vainilla con glaseado de cereza.",
        excerpt: "Un toque dulce y frutal.",
        price: 9,
        slug: "cupcake-de-cereza",
        image: getRandomImageURL(7, 'dulce'),
        cover: getRandomImageURL(7, 'dulce'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 8,
        name: "Cupcake de Algodón de Azúcar",
        content: "Cupcake decorado con algodón de azúcar.",
        excerpt: "Un dulce perfecto para los amantes del azúcar.",
        price: 9,
        slug: "cupcake-de-algodon-de-azucar",
        image: getRandomImageURL(8, 'dulce'),
        cover: getRandomImageURL(8, 'dulce'),
        featured: true,
        store_category_id: 1
    },
    {
        id: 9,
        name: "Galleta Calavera",
        content: "Galleta decorada con glaseado de calavera para Halloween.",
        excerpt: "Galleta crujiente y divertida.",
        price: 12,
        slug: "galleta-calavera",
        image: getRandomImageURL(9, 'dulce'),
        cover: getRandomImageURL(9, 'dulce'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 10,
        name: "Cupcake de Lavanda",
        content: "Cupcake aromático con un toque de lavanda y glaseado morado.",
        excerpt: "Una experiencia dulce y relajante.",
        price: 3,
        slug: "cupcake-de-lavanda",
        image: getRandomImageURL(10, 'dulce'),
        cover: getRandomImageURL(10, 'dulce'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 11,
        name: "Calabaza de caramelos",
        content: "Calabaza decorativa llena de caramelos y dulces.",
        excerpt: "Ideal para Halloween o como regalo.",
        price: 2.5,
        slug: "calabaza-de-caramelos",
        image: getRandomImageURL(11, 'dulce'),
        cover: getRandomImageURL(11, 'dulce'),
        featured: true,
        store_category_id: 2
    },
    {
        id: 12,
        name: "Galletas de Navidad",
        content: "Deliciosas galletas navideñas decoradas.",
        excerpt: "Un clásico para las fiestas.",
        price: 10,
        slug: "galletas-de-navidad",
        image: getRandomImageURL(12, 'dulce'),
        cover: getRandomImageURL(12, 'dulce'),
        featured: false,
        store_category_id: 2
    }
];





export const hombre:any = [
    {
        id: 1,
        name: "Camisa de algodón",
        content: "Camisa de algodón de alta calidad, perfecta para el uso diario.",
        excerpt: "Camisa elegante y cómoda.",
        price: 100,
        slug: "camisa-de-algodon",
        image: getRandomImageURL(1, 'hombre'),
        cover: getRandomImageURL(1, 'hombre'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 2,
        name: "Pantalones vaqueros",
        content: "Pantalones vaqueros modernos y duraderos, ideales para cualquier ocasión.",
        excerpt: "Pantalones modernos y duraderos.",
        price: 120,
        slug: "pantalones-vaqueros",
        image: getRandomImageURL(2, 'hombre'),
        cover: getRandomImageURL(2, 'hombre'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 3,
        name: "Zapatos de cuero",
        content: "Zapatos de cuero genuino, elegantes y cómodos para el uso diario.",
        excerpt: "Zapatos elegantes y cómodos.",
        price: 150,
        slug: "zapatos-de-cuero",
        image: getRandomImageURL(3, 'hombre'),
        cover: getRandomImageURL(3, 'hombre'),
        featured: true,
        store_category_id: 1
    },
    {
        id: 4,
        name: "Chaqueta de cuero",
        content: "Chaqueta de cuero clásico, resistente y perfecta para el clima frío.",
        excerpt: "Chaqueta clásica y resistente.",
        price: 180,
        slug: "chaqueta-de-cuero",
        image: getRandomImageURL(4, 'hombre'),
        cover: getRandomImageURL(4, 'hombre'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 5,
        name: "Botines de ante",
        content: "Botines de ante cómodos y versátiles, perfectos para cualquier ocasión.",
        excerpt: "Botines cómodos y versátiles.",
        price: 90,
        slug: "botines-de-ante",
        image: getRandomImageURL(5, 'hombre'),
        cover: getRandomImageURL(5, 'hombre'),
        featured: true,
        store_category_id: 1
    },
    {
        id: 6,
        name: "Reloj de pulsera",
        content: "Reloj de pulsera elegante y moderno, ideal para cualquier atuendo.",
        excerpt: "Reloj elegante y moderno.",
        price: 200,
        slug: "reloj-de-pulsera",
        image: getRandomImageURL(6, 'hombre'),
        cover: getRandomImageURL(6, 'hombre'),
        featured: true,
        store_category_id: 1
    }
];



export const mujer:any = [
    {
        id: 1,
        name: "Blusa de seda",
        content: "Blusa de seda de alta calidad, perfecta para ocasiones especiales.",
        excerpt: "Blusa elegante y suave.",
        price: 80,
        slug: "blusa-de-seda",
        image: getRandomImageURL(1, 'mujer'),
        cover: getRandomImageURL(1, 'mujer'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 2,
        name: "Falda plisada",
        content: "Falda plisada moderna y cómoda, ideal para el uso diario o eventos formales.",
        excerpt: "Falda moderna y cómoda.",
        price: 60,
        slug: "falda-plisada",
        image: getRandomImageURL(2, 'mujer'),
        cover: getRandomImageURL(2, 'mujer'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 3,
        name: "Vestido de verano",
        content: "Vestido de verano ligero y fresco, perfecto para los días cálidos.",
        excerpt: "Vestido fresco y cómodo.",
        price: 90,
        slug: "vestido-de-verano",
        image: getRandomImageURL(3, 'mujer'),
        cover: getRandomImageURL(3, 'mujer'),
        featured: true,
        store_category_id: 1
    },
    {
        id: 4,
        name: "Chaqueta de mezclilla",
        content: "Chaqueta de mezclilla clásica, resistente y versátil para cualquier ocasión.",
        excerpt: "Chaqueta clásica y resistente.",
        price: 110,
        slug: "chaqueta-de-mezclilla",
        image: getRandomImageURL(4, 'mujer'),
        cover: getRandomImageURL(4, 'mujer'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 5,
        name: "Bolso de mano",
        content: "Bolso de mano elegante y espacioso, perfecto para llevar todos tus esenciales.",
        excerpt: "Bolso elegante y espacioso.",
        price: 70,
        slug: "bolso-de-mano",
        image: getRandomImageURL(5, 'mujer'),
        cover: getRandomImageURL(5, 'mujer'),
        featured: true,
        store_category_id: 1
    },
    {
        id: 6,
        name: "Zapatillas deportivas",
        content: "Zapatillas deportivas cómodas y duraderas, ideales para actividades físicas.",
        excerpt: "Zapatillas cómodas y duraderas.",
        price: 100,
        slug: "zapatillas-deportivas",
        image: getRandomImageURL(6, 'mujer'),
        cover: getRandomImageURL(6, 'mujer'),
        featured: true,
        store_category_id: 1
    }
];

export const descuentos:any = [
    {
        id: 1,
        name: "Blusa de seda",
        content: "Blusa de seda de alta calidad, perfecta para ocasiones especiales.",
        excerpt: "Blusa elegante y suave.",
        price: 80,
        slug: "blusa-de-seda",
        image: getRandomImageURL(1, 'mujer'),
        cover: getRandomImageURL(1, 'mujer'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 2,
        name: "Falda plisada",
        content: "Falda plisada moderna y cómoda, ideal para el uso diario o eventos formales.",
        excerpt: "Falda moderna y cómoda.",
        price: 60,
        slug: "falda-plisada",
        image: getRandomImageURL(2, 'mujer'),
        cover: getRandomImageURL(2, 'mujer'),
        featured: false,
        store_category_id: 1
    },
    {
        id: 3,
        name: "Vestido de verano",
        content: "Vestido de verano ligero y fresco, perfecto para los días cálidos.",
        excerpt: "Vestido fresco y cómodo.",
        price: 90,
        slug: "vestido-de-verano",
        image: getRandomImageURL(3, 'mujer'),
        cover: getRandomImageURL(3, 'mujer'),
        featured: true,
        store_category_id: 1
    },    
];

const return_data:any={
    products,
    hombre,
    mujer,
    tendencia,
    descuentos   
}

export default return_data;