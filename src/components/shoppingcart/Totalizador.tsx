'use client';
import { useEffect, useState } from "react";
import { useSelector } from "react-redux";
import useWhatsAppOrder from "@/hooks/useWhatsAppOrder";
import { formatearMonto } from '@/utils/fuctions';
import InputField from "../fields/InputField";
import useFormData from "@/hooks/useFormDataNew";
import Cookies from 'js-cookie';


// Interfaz para definir el tipo de cada producto
interface Product {
    id: number;
    name: string;
    price: string;
    slug: string;
    image: string;
    cover: string;
    store_category_id: number;
    featured: string;
    status: string;
    resume: {
        variantItems: any[];
        rawItems: any[];
        category: string;
    };
}

const prefixed = "registro";

const Totalizador: React.FC = () => {
    const{handleRequest,backend}    =   useFormData()
    const total             =   useSelector((state: any) => state.shoppingCart.total);
    const products          =   useSelector((state: any) => state.shoppingCart.products);
    const quantities        =   useSelector((state: any) => state.shoppingCart.quantities);
    const { Buttom }        =   useWhatsAppOrder();    
    const [isUserLogged, setIsUserLogged] = useState(false);

    

    const [formData, setFormData] = useState({
        phone_number: "",
        email: "",
        name: "",
        password: ""
    });

    useEffect(() => {
        let quantity = 0;
        Object.entries(quantities).forEach(([key, quantity_]) => {
            const id = parseInt(key);
            const result = products.find((product: Product) => product.id === id);
            quantity += quantity_ as number;
            if (result && result.price) {
                const price = parseFloat(result.price.replace(",", "."));
            }
        });        
    }, [quantities, products]);

    useEffect(() => {
        const user = localStorage.getItem("user");
        setIsUserLogged(!!user);
    }, []);

    const handleFormSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        
        // Enviar solicitud de autenticación
        handleRequest(backend + "/shopping-auth", "post", { ...formData }).then((response: any) => {
            console.log(response)
            if (response && response.token) {
                const expires = new Date();
                expires.setMinutes(expires.getMinutes() + 25); // Expira en 25 minutos
        
                // Guardar el token en las cookies con una duración de 25 minutos
                Cookies.set('token', JSON.stringify({ value: response.token, expires }), {
                    expires: 1 / 288, // 5 minutos
                    sameSite: 'None',
                    secure: true,
                });
        
                // Almacenar el usuario en localStorage
                localStorage.setItem('user', JSON.stringify({ ...response.user, token: response.token }));
        
                console.log(4)
                setIsUserLogged(true);        

            } else {
                // Si no hay token en la respuesta, eliminar datos
                
                Cookies.remove('token');
            }            
        }).catch((error) => {
            console.error("Error en autenticación:", error);
            // Manejo de errores aquí
        });
    };

    return (
        <div className="bg-white w-[100%] border border-gray-200 rounded-md shadow-md hover:shadow-lg p-4 mb-3">
            <div className="text-lg text-center">Total a pagar: <b>${formatearMonto(total, 0)}</b></div>            
            {!isUserLogged && (
                <form onSubmit={handleFormSubmit} className="mt-2 space-y-3 grid grid-cols-2 gap-4">
                    <InputField
                        prefixed={prefixed}
                        id="phone_number"
                        name="phone_number"
                        variant="autenticación"
                        extra="mt-3"
                        label="Número de Teléfono"
                        placeholder="3112345678"
                        type="text"
                        defaultValue={formData.phone_number}
                        setInputs={setFormData}
                    />
                    <InputField
                        prefixed={prefixed}
                        id="email"
                        name="email"
                        variant="autenticación"
                        extra="mb-0"
                        label="Email"
                        placeholder="correo@dominio.com"
                        type="email"
                        defaultValue={formData.email}
                        setInputs={setFormData}
                    />
                    <InputField
                        prefixed={prefixed}
                        id="name"
                        name="name"
                        variant="autenticación"
                        extra="mb-0"
                        label="Nombre"
                        placeholder="Jorge Antonio"
                        type="text"
                        defaultValue={formData.name}
                        setInputs={setFormData}
                    />
                    <InputField
                        prefixed={prefixed}
                        id="password"
                        name="password"
                        variant="autenticación"
                        extra="mb-0"
                        label="Contraseña"
                        placeholder="*******"
                        type="password"
                        defaultValue={formData.password}
                        setInputs={setFormData}
                    />
                    <button type="submit" className="cursor-pointer flex justify-center items-center w-full px-4 linear mt-2 rounded-xl bg-brand-500 py-[12px] text-base font-medium text-white transition duration-200 hover:bg-brand-600 active:bg-brand-700 dark:bg-brand-400 dark:text-white dark:hover:bg-brand-300 dark:active:bg-brand-200 mt-3 col-span-2">
                        Procesar solicitud
                    </button>
                </form>
            )}
            
            {
                isUserLogged && (
                    <div className="mt-5">
                        <Buttom />
                    </div>
                )
            }
        </div>
    );
};

export default Totalizador;
