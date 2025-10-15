import ProductDescription from "@/components/productsold/description";
import React from "react";

// Interfaz para un producto individual
interface Product {
    id: number;
    name: string;
    content: string;
    excerpt: string;
    price: number;
    slug: string;
    image: string;
    cover: string;
    featured: boolean;
    store_category_id: number;
}

// Interfaz para la respuesta del backend con SEO
interface SEO {
    title: string;
    description: string;
    openGraph: {
        title: string;
        description: string;
        image: string;
    };
}

// Interfaz para el dataset de un producto
interface Dataset {
    product: Product;
    seo: SEO;
}

// Función para obtener la URL base del backend
const getBackendUrl = () => process.env.NEXT_PUBLIC_VERSION || '';

// Función para obtener los detalles del producto
const getProductDetail = async (slug: string): Promise<Dataset | null> => {
    try {
        const endpoint  =   `${getBackendUrl()}/products/detail/${slug}`;
        console.log("este es el slug", endpoint)
        const res       =   await fetch(endpoint,{
                                        method: 'GET',
                                        headers: {
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json',
                                        },
                                        cache: "no-store"
                                    });

        if (!res.ok) {
            throw new Error(`Error en la petición: ${res.statusText}`);
        }

        const dataset: Dataset = await res.json(); // Se espera que el backend retorne un dataset con product y SEO

        //console.log(dataset)

        return dataset;

    } catch (error) {
        console.error("Error al obtener el producto:", error);
        return null; // Devolvemos null en caso de error
    }
};

// Componente de detalle de producto
const ProductDetail = async ({ params }: any) => {
    try {
        
        const { slug }      =   params;
        const dataset:any   =   await getProductDetail(slug);       

        if (!dataset) {
            return (
                <div className="text-center mt-4">
                    <h1 className="font-poppins text-[26px] font-bold uppercase text-navy-700 dark:text-white">
                        Error al cargar el producto
                    </h1>
                    <p>Por favor, intenta de nuevo más tarde.</p>
                </div>
            );
        }

        const { product }   =   dataset?.data;

        //console.log("AQUI",product)

        return <ProductDescription product={product} />;

    } catch (error) {
        console.error("Error en el componente ProductDetail:", error);
        return (
            <div className="text-center mt-4">
                <h1 className="font-poppins text-[26px] font-bold uppercase text-navy-700 dark:text-white">
                    Error inesperado al cargar el producto
                </h1>
                <p>Por favor, intenta de nuevo más tarde.</p>
            </div>
        );
    }
};

export default ProductDetail;

// Función para generar los metadatos dinámicamente
export async function generateMetadata(props: any) {
    try {
        const { slug }      =   props.params;
        const dataset:any   =   await getProductDetail(slug);

        

        

        if (!dataset || !dataset.data || !dataset.data.product) {
            return {
                title: "Producto no encontrado",
                description: "Este producto no está disponible en este momento",
                openGraph: {
                    title: "Producto no encontrado",
                    description: "Este producto no está disponible en este momento",
                    image: "/default-image.jpg"
                }
            };
        }

        const { seo } = dataset?.data?.product;

        let response: any = {
            title: "",
            description: "",
            openGraph: {
              title: "",
              description: "",
              images: [
                {
                  url: "", // Corrección: asegurarse de que `image` es un string y no un objeto
                  width: 1200,
                  height: 630,
                  alt: `Open Graph Image`,
                },
              ],
              image: "",
            },
            twitter: {
              card: 'summary_large_image',
              title: "",
              description: "",
              images: [],
            }
          };

        if (dataset && dataset.data && dataset.data.product) {        
            response = {
                title: seo.title,
                description: seo.description,
                openGraph: {
                  title: seo.title,
                  description: seo.description,
                  images: [
                    {
                      url: seo.openGraph.image, // Corrección: asegurarse de que `image` es un string y no un objeto
                      width: 1200,
                      height: 630,
                      alt: `Open Graph Image`,
                    },
                  ],
                  image: seo.openGraph.image,
                },
                twitter: {
                  card: 'summary_large_image',
                  title: seo.title,
                  description: seo.description,
                  images: [seo.openGraph.image],
                }
            };
        }

        return response;
    } catch (error) {
        console.error("Error al generar los metadatos:", error);
        return {
            title: "Error al generar metadatos",
            description: "No se pudo generar la metadata para esta página",
            openGraph: {
                title: "Error al generar metadatos",
                description: "No se pudo generar la metadata para esta página",
                image: "/default-image.jpg"
            }
        };
    }
}
