'use client'
import Image from "next/image";
import React, { useState, useEffect } from "react";
import { IoMdHeartEmpty, IoMdHeart } from "react-icons/io";
import { updateQuantity , updateFavorites, setQuantity} from "@/store/Slices/shoppingCartSlice";
import { useDispatch , useSelector} from "react-redux";
import Link from "next/link";
import { MdOutlineAddCircleOutline } from "react-icons/md";
import { MdOutlineRemoveCircleOutline } from "react-icons/md";
import ToggleCartButton from "@/components/productsold/ToggleCartButton"; // Importamos el botón toggle

interface ProductsItemProps {
    product: any;
}

let st:any={}
let quantity:any={}
let dispatch:any={}

const ProductsItem: React.FC<ProductsItemProps> = ({ product }) => {

    const [favorites, setFavorites] = useState<{ [id: number]: boolean }>({});

    // Obtener la función dispatch para enviar acciones al store
    dispatch = useDispatch();    

    // Cargar favoritos desde localStorage al cargar el componente
    useEffect(() => {
        const storedFavorites = localStorage.getItem("favorites");
        if (storedFavorites) {
            st      =   JSON.parse(storedFavorites);
            dispatch(updateFavorites(st));
            setFavorites(JSON.parse(storedFavorites));
        }
        const storedQuantities = localStorage.getItem("quantities");
        if (storedQuantities) {
            quantity      =   JSON.parse(storedQuantities);
            dispatch(setQuantity(quantity));
            setFavorites(JSON.parse(storedQuantities));
        }
    }, []);

    // Guardar favoritos en localStorage cada vez que cambian
    useEffect(() => {
        st  =   {...st,...favorites}        
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

    // Verificar si el producto está marcado como favorito
    const isFavorite = favorites[product.id];

    return (
        <div className="border border-gray-200 rounded-md shadow-md hover:shadow-lg">
            <div className="w-full h-66 md:h-[20vh] overflow-hidden relative rounded-t-md">
                <div className="w-full">
                    <Link href={"../producto/"+product.slug}>
                        <Image
                            alt={String(process.env.NEXT_PUBLIC_NAME + " " + product.name)}
                            src={product.image}
                            className="rounded-t-md object-cover"
                            width={400}
                            height={250}
                        />
                    </Link>
                </div>
                {/* Botón de favoritos */}
                <div className="absolute top-3 right-3 flex gap-2">
                    <button onClick={handleToggleFavorite} aria-label="Agregar a favoritos">
                        {isFavorite ? <IoMdHeart className="h-10 w-10 text-red-500" /> : <IoMdHeartEmpty className="h-10 w-10 text-gray-500" />}
                    </button>
                    <ToggleCartButton productId={product.id} />
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

export default ProductsItem;

