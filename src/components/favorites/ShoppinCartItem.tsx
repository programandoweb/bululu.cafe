'use client'

import Image from "next/image";
import React, { useState, useEffect } from "react";
import { updateQuantity, updateFavorites, setQuantity, removeProduct } from "@/store/Slices/shoppingCartSlice"; // Asegúrate de tener la acción removeProduct
import { useDispatch, useSelector } from "react-redux";
import Link from "next/link";
import { IoMdHeartEmpty, IoMdHeart } from "react-icons/io";

interface ProductsItemProps {
    product: any;
}

let st: any = {}
let quantity: any   =   {}
let dispatch: any   =   {}
let remove:any      =   false;

const ShoppinCartItem: React.FC<ProductsItemProps> = ({ product }) => {
    const [favorites, setFavorites] =   useState<{ [id: number]: boolean }>({});

    // Acceder al estado del carrito de compras
    const quantities = useSelector((state: any) => state.shoppingCart.quantities);

    // Obtener la función dispatch para enviar acciones al store
    dispatch = useDispatch();    

    useEffect(() => {
        if(remove&&quantities){
            remove=false;
            localStorage.setItem("quantities", JSON.stringify(quantities));
        }
    },[quantities])

    // Cargar favoritos desde localStorage al cargar el componente
    useEffect(() => {
        const storedFavorites = localStorage.getItem("favorites");
        if (storedFavorites) {
            st = JSON.parse(storedFavorites);
            dispatch(updateFavorites(st));
            setFavorites(JSON.parse(storedFavorites));
        }
        const storedQuantities = localStorage.getItem("quantities");
        if (storedQuantities) {
            quantity = JSON.parse(storedQuantities);
            dispatch(setQuantity(quantity));
        }
    }, []);

    // Guardar favoritos en localStorage cada vez que cambian
    useEffect(() => {
        st = { ...st, ...favorites }
        localStorage.setItem("favorites", JSON.stringify(st));
    }, [favorites]);


    // Función para agregar o eliminar un producto de los favoritos
    const handleToggleFavorite = () => {
        setFavorites((prevFavorites) => {
            const updatedFavorites          =   { ...prevFavorites };
            updatedFavorites[product.id]    =   !updatedFavorites[product.id];
            return updatedFavorites;
        });
    };

    const isFavorite = favorites[product.id];

    return (
        <div className=" bg-white w-[100%] border border-gray-200 rounded-md shadow-md hover:shadow-lg mb-3">
            <div className="w-full h-96 md:h-[22vh] overflow-hidden relative rounded-t-md">
                <div className="w-full">
                    <Link href={"../producto/"+product.slug}>
                        <Image
                            alt={String(process.env.NEXT_PUBLIC_NAME + " " + product.name)}
                            src={product.image}
                            className="rounded-t-md object-cover"
                            width={600}
                            height={400}
                        />
                    </Link>
                </div>
                {/* Botón de favoritos */}
                <div className="absolute top-3 right-3">
                    <button onClick={handleToggleFavorite} aria-label="Agregar a favoritos">
                        {isFavorite ? <IoMdHeart className="h-10 w-10"/> : <IoMdHeartEmpty className="h-10 w-10"/>}
                    </button>
                </div>
                
            </div>
            <div className="px-4 pt-2 flex justify-between items-center">
                <div className="text-sm md:text-lg font-semibold">{product.name}</div>
                <div className="font-bold">{product.price}</div>
            </div>
            <div className="px-4 mb-4">
                <p className="text-xs md:text-md text-gray-600">{product.excerpt}</p>
            </div>
        </div>
    );
};

export default ShoppinCartItem;
