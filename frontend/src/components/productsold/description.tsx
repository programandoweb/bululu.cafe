'use client';

import Image from "next/image";
import React, { useState, useEffect } from "react";
import { IoMdHeartEmpty, IoMdHeart } from "react-icons/io";
import { updateFavorites, setQuantity } from "@/store/Slices/shoppingCartSlice";
import { useDispatch, useSelector } from "react-redux";
import { MdShoppingCart } from "react-icons/md";
import Link from "next/link";
import Products from "./Related";
import ToggleCartButton from "@/components/productsold/ToggleCartButton"; // Importamos el botón toggle

interface Product {
    id: number;
    name: string;
    price: number;
    image: string;
    excerpt: string;
    content: string;
    slug: string;
    ingredients: any;
}

interface ProductDescriptionProps {
    product: Product;
}

const ProductDescription: React.FC<ProductDescriptionProps> = ({ product }) => {
    const [favorites, setFavorites] = useState<{ [id: number]: boolean }>({});
    const dispatch = useDispatch();

    // Acceder al estado de favoritos y cantidades
    const quantities = useSelector((state: any) => state.shoppingCart.quantities);

    // Cargar favoritos y cantidades desde localStorage al cargar el componente
    useEffect(() => {
        const storedFavorites = localStorage.getItem("favorites");
        const storedQuantities = localStorage.getItem("quantities");

        if (storedFavorites) {
            const parsedFavorites = JSON.parse(storedFavorites);
            dispatch(updateFavorites(parsedFavorites));
            setFavorites(parsedFavorites);
        }
        if (storedQuantities) {
            const parsedQuantities = JSON.parse(storedQuantities);
            dispatch(setQuantity(parsedQuantities));
        }
    }, [dispatch]);

    // Guardar favoritos en localStorage cada vez que cambian
    useEffect(() => {
        localStorage.setItem("favorites", JSON.stringify(favorites));
    }, [favorites]);

    const handleToggleFavorite = () => {
        setFavorites((prevFavorites) => {
            const updatedFavorites: any = { ...prevFavorites, [product.id]: !prevFavorites[product.id] };
            dispatch(updateFavorites(updatedFavorites));
            return updatedFavorites;
        });
    };

    const isFavorite = favorites[product.id];

    return (
        <div className="bg-white border border-gray-200 rounded-md shadow-md hover:shadow-lg p-4 mt-4">
            <div className="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div className="col-span-4 md:col-span-2 mb-3 md:mb-0 h-[50vh] md:h-[80vh] overflow-hidden relative">
                    <Image
                        alt={`${process.env.NEXT_PUBLIC_NAME} ${product.name}`}
                        src={product.image}
                        className="rounded-t-md object-cover"
                        fill
                    />
                    <div className="absolute top-6 right-6">
                        <button onClick={handleToggleFavorite}>
                            {isFavorite ? (
                                <IoMdHeart className="h-12 w-12 text-brand-500" />
                            ) : (
                                <IoMdHeartEmpty className="h-12 w-12" />
                            )}
                        </button>
                    </div>
                </div>
                <div className="col-span-4 md:col-span-2 relative min-h-[20vh] md:min-h-[40vh]">
                    <div>
                        <div className="px-4 pt-2 flex justify-between items-center">
                            <h1 className="text-sm md:text-3xl font-semibold">{product.name}</h1>
                            <div className="text-sm md:text-5xl font-semibold">${product.price}</div>
                        </div>
                        <div className="px-4 mb-4">
                            <p className="text-xl md:text-md text-gray-600">{product.excerpt}</p>
                            <div dangerouslySetInnerHTML={{ __html: product.content }} />
                        </div>
                    </div>

                    {product && product.ingredients && product.ingredients.length > 0 && (
                        <div>
                            <div className="px-4 pt-2 flex justify-between items-center">
                                <h3 className="text-sm md:text-xl font-semibold">Características</h3>
                            </div>
                            <div className="px-4 pt-2 flex justify-between items-center">
                                <ul>
                                    {product.ingredients.map((row: any, key: number) => (
                                        <li key={key}>
                                            <b>{row?.raw?.label}</b> {row?.raw?.description}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </div>
                    )}

                    <div className="px-4 py-4 flex justify-end items-center space-x-4">
                        {/* Botón Toggle para agregar o quitar del carrito */}
                        <ToggleCartButton productId={product.id} />
                        
                        {/* Botón de Procesar pago */}
                        <Link href="../shoppingcart" className="flex justify-center items-center px-4 linear rounded-xl bg-brand-500 py-[12px] text-base font-medium text-white transition duration-200 hover:bg-brand-600 active:bg-brand-700 dark:bg-brand-400 dark:text-white dark:hover:bg-brand-300 dark:active:bg-brand-200">
                            <MdShoppingCart className="h-6 w-6 text-white mr-2" />
                            <span>Procesar pago</span>
                        </Link>
                    </div>

                    <div className="px-4 mt-4">
                        <h2 className="text-md md:text-xl font-bold uppercase">Relacionados</h2>
                    </div>
                    <Products />
                </div>
            </div>
        </div>
    );
};

export default ProductDescription;
