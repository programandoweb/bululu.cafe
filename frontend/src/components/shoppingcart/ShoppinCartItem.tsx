import React, { useState, useEffect } from "react";
import Image from "next/image";
import { useDispatch, useSelector } from "react-redux";
import { removeProduct, increaseVariantQuantity, decreaseVariantQuantity } from "@/store/Slices/shoppingCartSlice";
import Link from "next/link";
import { usePathname } from 'next/navigation';
import StringAndLabel from '@/components/fields/StringAndLabel';
import ButtonsCounterShoppingCart from "./ButtonsCounterShoppingCart";
import { formatearMonto } from '@/utils/fuctions';

interface ProductsItemProps {
    product: any;
    setTotalizacion:any;
}

const ShoppinCartItem: React.FC<ProductsItemProps> = ({ product, setTotalizacion }) => {
    const dispatch = useDispatch();
    const variantQuantities = useSelector((state: any) => state.shoppingCart.variantQuantities);

    // Calcular el total específico para este producto
    const total = product.resume.variantItems.reduce((sum: number, variant: any) => {
        const variantId = `${product.id}-${variant.id}`;
        const quantity = variantQuantities[variantId] || 0;        
        return sum + quantity * variant.variant_price;        
    }, 0);
    

    const handleRemoveProduct = () => {
        dispatch(removeProduct({ id: product.id }));

        // Actualizar localStorage para eliminar las variantes asociadas
        const updatedVariantQuantities = { ...variantQuantities };
        Object.keys(updatedVariantQuantities).forEach((variantId) => {
            if (variantId.startsWith(`${product.id}-`)) {
                delete updatedVariantQuantities[variantId];
            }
        });
        localStorage.setItem("variantQuantities", JSON.stringify(updatedVariantQuantities));

        const quantities        = JSON.parse(localStorage.getItem("quantities") || "{}");
        delete quantities[product.id];
        localStorage.setItem("quantities", JSON.stringify(quantities));
        
    };

    useEffect(() => {
        if (total > 0) {
            setTotalizacion((prevState:any) => ({
                ...prevState,
                [product.id]: total
            }));
        }
    }, [total, product.id, setTotalizacion]);
    
    
    return (
        <div className="bg-white w-[100%] border border-gray-200 rounded-md shadow-md hover:shadow-lg pr-4 mb-3">
            <div className="flex flex-col md:flex-row">
                <div className="md:w-1/5 flex-shrink-0">
                    <Link href={`${usePathname()}/producto/${product.slug}`}>
                        <Image
                            alt={`${process.env.NEXT_PUBLIC_NAME} ${product.name}`}
                            src={product.image}
                            className="rounded-md object-cover w-full h-full"
                            width={180}
                            height={400}
                        />
                    </Link>
                </div>
                <div className="md:w-3/5 p-4">
                    <div className="flex flex-col">
                        <div className="text-sm md:text-lg font-semibold">{product.name}</div>
                        <div className="text-sm md:text-xs font-semibold">{product.resume.category}</div>

                        <div className="w-full mt-4">
                            <div className="flex flex-wrap gap-2">
                                {product.resume.variantItems &&
                                    product.resume.variantItems.map((variant: any, key: number) => {
                                        const variantId = `${product.id}-${variant.id}`;
                                        return (
                                            <StringAndLabel label={variant.variant_name} key={key} className="text-center">
                                                <div className="text-sm md:text-xs font-semibold text-center">
                                                    ${formatearMonto(variant.variant_price, 0)}
                                                </div>
                                                <ButtonsCounterShoppingCart variantId={variantId} />
                                            </StringAndLabel>
                                        );
                                    })}
                            </div>
                        </div>
                    </div>
                </div>
                <div className="md:w-1/5 flex flex-col items-end pt-4">
                    <div className="font-bold text-2xl mb-2 pt-2">
                        ${formatearMonto(total, 0)}
                    </div>
                    <button
                        onClick={handleRemoveProduct}
                        className="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded mt-2"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    );
};

export default ShoppinCartItem;
