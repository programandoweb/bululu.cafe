import React from 'react';
import { MdOutlineAddCircleOutline, MdOutlineRemoveCircleOutline } from "react-icons/md";

interface Product {
    id: string;
}

interface ButtonsProps {
    product: Product;
    quantities: { [key: string]: number };
    handleDecreaseQuantity: () => void;
    handleIncreaseQuantity: () => void;
}

const Buttons: React.FC<ButtonsProps> = ({
    product,
    quantities,
    handleDecreaseQuantity,
    handleIncreaseQuantity
}) => {
    return (
        <div className="absolute bottom-3 right-3">
            <div className="flex items-center mt-3 w-full">
                <button
                    aria-label="Reducir cantidad de productos"
                    onClick={handleDecreaseQuantity}
                    className="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-l"
                >
                    <MdOutlineRemoveCircleOutline className="h-7 w-7" />
                </button>
                <div className="bg-gray-200 text-gray-700 text-xl font-bold py-2 px-4">
                    {quantities[product.id] || 0}
                </div>
                <button
                    aria-label="Aumentar cantidad de productos"
                    onClick={handleIncreaseQuantity}
                    className="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-r"
                >
                    <MdOutlineAddCircleOutline className="h-7 w-7" />
                </button>
            </div>
        </div>
    );
};

export default Buttons;
