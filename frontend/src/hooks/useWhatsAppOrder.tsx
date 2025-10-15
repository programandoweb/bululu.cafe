'use client';

import { formatearMonto } from "@/utils/fuctions";
import { MdWhatsapp } from "react-icons/md";
import { useSelector, useDispatch } from 'react-redux';
import useFormData from "./useFormDataNew";
import { clearCart } from "@/store/Slices/shoppingCartSlice";
import { useRouter } from 'next/navigation';
let formData:any;
let dispatch:any;
let router:any;
const useWhatsAppOrder = () => {
    router          =       useRouter();
    dispatch        =       useDispatch();
    formData        =       useFormData(false,false,false)
    const dataset   =       useSelector((state: any) => state.shoppingCart);
    const { whatsapp, products, variantQuantities } = dataset;

    const handleClick = async () => {
        let _html = `Hola, he realizado un pedido en la plataforma ${process.env.NEXT_PUBLIC_NAME}:\n\n`;
        let totalGeneral = 0;

        // Creamos el array con los productos y variantes para enviar al backend
        const orderItems = Object.entries(variantQuantities).map(([variantKey, quantity]) => {
            const [productId, variantId] = variantKey.split('-').map(Number);
            const product = products.find((p: any) => p.id === productId);
            if (product) {
                const variant = product.resume.variantItems.find((v: any) => v.id === variantId);
                if (variant) {
                    const variantPrice = parseFloat(variant.variant_price.replace(",", "."));
                    const quantityNumber = Number(quantity); // Convertimos quantity a número
                    const total = quantityNumber * variantPrice;
                    totalGeneral += total;

                    // Agregamos al mensaje de WhatsApp
                    _html += `*${product.name} - ${variant.variant_name}* x ${quantityNumber} - Total: $${formatearMonto(total.toFixed(2))}\n`;

                    // Retornamos el objeto para el backend
                    return {
                        product_id: productId,
                        product_name: product.name,
                        variant_id: variantId,
                        variant_name: variant.variant_name,
                        quantity: quantityNumber,
                        unit_price: variantPrice,
                        total_price: total
                    };
                }
            }
            return null;
        }).filter(Boolean); // Filtra los valores nulos si algún producto o variante no existe

        // Añadir monto total general al final del mensaje
        _html += `\n----------------------------------\n`;
        _html += `*Monto Total a Pagar: $${formatearMonto(totalGeneral.toFixed(2))}*\n`;
        _html += `----------------------------------\n`;

        sendOrder({ orderItems, totalGeneral })
        

        // Codificar el mensaje con encodeURIComponent para asegurar que los saltos de línea se respeten
        const whatsAppURL = `https://wa.me/${whatsapp}?text=${encodeURIComponent(_html)}&app_absent=0`;
        window.open(whatsAppURL);

        localStorage.removeItem("user");
        localStorage.removeItem("quantities");
        router.replace(`/catalogo-el-meta-programandoweb/tienda`);
    };

    const sendOrder = (data:any) => {
        formData.handleRequest(formData.backend + "/products/addOrderOpen","post",{...data}).then((response: any) => {
            if(response&&response.process&&response.process===true){
                dispatch(clearCart());
                router.replace(`/catalogo-el-meta-programandoweb/tienda`);        
            }
        });
    };   

    // Tipo para el botón
    const Buttom: React.FC = () => (
        <div
            className="cursor-pointer flex justify-center items-center w-full px-4 linear mt-2 rounded-xl bg-brand-500 py-[12px] text-base font-medium text-white transition duration-200 hover:bg-brand-600 active:bg-brand-700 dark:bg-brand-400 dark:text-white dark:hover:bg-brand-300 dark:active:bg-brand-200"
            onClick={handleClick}
        >
            <MdWhatsapp className="w-8 h-8 mr-2" /> Procesar pedido por whatsapp
        </div>
    );

    return {
        Buttom,
    };
};

export default useWhatsAppOrder;
