// ProductsItem.tsx
'use client';

import Image from "next/image";
import React, { useState, useEffect } from "react";
import { IoMdHeartEmpty, IoMdHeart } from "react-icons/io";
import { updateFavorites, setQuantity } from "@/store/Slices/shoppingCartSlice";
import { useDispatch, useSelector } from "react-redux";
import Link from "next/link";
import { usePathname } from 'next/navigation';
import ToggleCartButton from "@/components/productsold/ToggleCartButton"; // Importamos el nuevo botón

interface ProductsItemProps {
    product: any;
}

const ProductsItem: React.FC<ProductsItemProps> = ({ product }) => {
    const dispatch = useDispatch();
    const [favorites, setFavorites] = useState<{ [id: number]: boolean }>({});

    // Cargar favoritos y cantidades desde localStorage al cargar el componente
    useEffect(() => {
        const storedFavorites = localStorage.getItem("favorites");
        const storedQuantities = localStorage.getItem("quantities");

        if (storedFavorites) {
            setFavorites(JSON.parse(storedFavorites));
            dispatch(updateFavorites(JSON.parse(storedFavorites)));
        }
        if (storedQuantities) {
            dispatch(setQuantity(JSON.parse(storedQuantities)));
        }
    }, [dispatch]);

    // Guardar favoritos en localStorage cada vez que cambian
    useEffect(() => {
        localStorage.setItem("favorites", JSON.stringify(favorites));
    }, [favorites]);

    // Función para agregar o eliminar un producto de los favoritos
    const handleToggleFavorite = () => {
        setFavorites((prevFavorites) => ({
            ...prevFavorites,
            [product.id]: !prevFavorites[product.id],
        }));
    };

    const isFavorite = favorites[product.id];

    return (
        <div className="border border-gray-200 rounded-md shadow-md hover:shadow-lg">
            <div className="w-full h-44 md:h-[26vh] overflow-hidden relative rounded-t-md">
                <Link href={usePathname() + "/producto/" + product.slug}>                    
                    <Image
                        alt={String(process.env.NEXT_PUBLIC_NAME + " " + product.Producto)}
                        src={product.image}
                        className="rounded-t-md object-cover h-64"
                        width={400}
                        height={400}
                    />                    
                </Link>
                {/* Botones de favoritos y agregar al carrito */}
                <div className="absolute top-3 right-3 flex gap-2">
                    <button onClick={handleToggleFavorite} aria-label="Agregar a favoritos">
                        {isFavorite ? (
                            <IoMdHeart className="h-10 w-10 text-red-500" />
                        ) : (
                            <IoMdHeartEmpty className="h-10 w-10 text-gray-500" />
                        )}
                    </button>
                    {/* Usamos el componente ToggleCartButton */}
                    <ToggleCartButton productId={product.id} />
                </div>
            </div>
            <div className="px-4 pt-2 flex justify-between items-center">
                <div className="text-sm md:text-lg font-semibold">{product.name}</div>
                <div className="font-bold">${product.price}</div>
            </div>
            <div className="px-4 mb-4">
                <p className="text-xs md:text-md text-gray-600">{product.resume?.category}</p>
            </div>
        </div>
    );
};

export default ProductsItem;
