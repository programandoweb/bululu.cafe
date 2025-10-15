/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular 3115000926
 *  website: Programandoweb.net
 *  Proyecto: Ivoovle *  
 * ---------------------------------------------------
 */

import React from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { increaseVariantQuantity, decreaseVariantQuantity, removeProduct } from '@/store/Slices/shoppingCartSlice';

interface ButtonsProps {
    variantId: string;
}

const ButtonsCounterShoppingCart: React.FC<ButtonsProps> = ({ variantId }) => {
    const dispatch = useDispatch();

    // Obtenemos la cantidad actual del slice usando useSelector
    const variantQuantities = useSelector((state: any) => state.shoppingCart.variantQuantities);
    const quantity = variantQuantities[variantId] || 0;

    // Extrae el número del variantId como se necesita en el store
    const productId = parseInt(variantId.split('-')[0], 10);

    const handleIncreaseVariantQuantity = () => {
        dispatch(increaseVariantQuantity({ variantId }));
    };

    const handleDecreaseVariantQuantity = () => {
        if (quantity > 1) {
            dispatch(decreaseVariantQuantity({ variantId }));
        } else {
            dispatch(removeProduct({ id: productId }));
        }
    };

    return (
        <div className="flex items-center">
            <button
                onClick={handleDecreaseVariantQuantity}
                className="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-1 px-3 rounded-l"
            >
                -
            </button>
            <div className="bg-gray-200 text-gray-700 font-bold py-1 px-3">
                {quantity}
            </div>
            <button
                onClick={handleIncreaseVariantQuantity}
                className="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-1 px-3 rounded-r"
            >
                +
            </button>
        </div>
    );
};

export default ButtonsCounterShoppingCart;
