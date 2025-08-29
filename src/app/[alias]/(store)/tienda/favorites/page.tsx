import FavoritesComponent from "@/components/favorites";
import React from "react";

const FavoritesPage=()=>{
    return  <FavoritesComponent/>
}

export default FavoritesPage;

export async function generateMetadata(props:any) {
    return {
        title: "Productos favoritos - "+process.env.NEXT_PUBLIC_NAME,
        description: process.env.NEXT_PUBLIC_NAME+' Compra fácil y segura. Disfruta de una experiencia de compra fluida en Ivoolve Store. Nuestro carrito de compras avanzado te permite agregar, eliminar y gestionar tus productos favoritos de manera sencilla. Encuentra las mejores ofertas en tecnología, moda, hogar y más. Compra ahora y aprovecha nuestras promociones exclusivas y envíos rápidos. Ivoolve Store: calidad y confianza en cada compra.',
        applicationName: process.env.NEXT_PUBLIC_NAME,  
        openGraph:{
            title: "Productos favoritos - "+process.env.NEXT_PUBLIC_NAME,
            description: process.env.NEXT_PUBLIC_NAME+' Compra fácil y segura. Disfruta de una experiencia de compra fluida en Ivoolve Store. Nuestro carrito de compras avanzado te permite agregar, eliminar y gestionar tus productos favoritos de manera sencilla. Encuentra las mejores ofertas en tecnología, moda, hogar y más. Compra ahora y aprovecha nuestras promociones exclusivas y envíos rápidos. Ivoolve Store: calidad y confianza en cada compra.',
        }
    }
}