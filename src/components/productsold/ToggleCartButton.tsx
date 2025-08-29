// components/ToggleCartButton.tsx
'use client';

import React from "react";
import { MdOutlineAddShoppingCart } from "react-icons/md";
import { useDispatch, useSelector } from "react-redux";
import { updateQuantity, removeProduct } from "@/store/Slices/shoppingCartSlice";

interface ToggleCartButtonProps {
    productId: number;
}

const ToggleCartButton: React.FC<ToggleCartButtonProps> = ({ productId }) => {
    const dispatch = useDispatch();

    // Acceder al estado del carrito de compras
    const quantities = useSelector((state: any) => state.shoppingCart.quantities);
    const isInCart = quantities[productId] > 0;

    // Función toggle para agregar o eliminar el producto del carrito
    const handleToggleCart = () => {
        if (isInCart) {
            // Eliminar del carrito
            dispatch(removeProduct({ id: productId }));
            const updatedQuantities = { ...quantities };
            delete updatedQuantities[productId];
            localStorage.setItem("quantities", JSON.stringify(updatedQuantities));
        } else {
            // Agregar al carrito
            dispatch(updateQuantity({ id: productId, quantity: 1 }));
            localStorage.setItem("quantities", JSON.stringify({ ...quantities, [productId]: 1 }));
        }
    };

    return (
        <button
            onClick={handleToggleCart}
            aria-label="Agregar o quitar del carrito"
            className={`h-10 w-10 ${isInCart ? 'bg-red-500' : 'bg-blue-500'} hover:bg-blue-600 text-white font-bold rounded-full flex items-center justify-center`}
        >
            <MdOutlineAddShoppingCart className="h-6 w-6" />
        </button>
    );
};

export default ToggleCartButton;
