/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular 3115000926
 *  website: Programandoweb.net
 *  Proyecto: Ivoovle *  
 * ---------------------------------------------------
 */

'use client';

import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { setProducts, setQuantity,setTotal } from "@/store/Slices/shoppingCartSlice";
import ShoppinCartItem from "./ShoppinCartItem";
import Totalizador from "./Totalizador";
import useFormData from "@/hooks/useFormDataNew";

const ShoppingcartComponent = () => {
    const formData = useFormData(false, false, false);
    const dispatch = useDispatch();
    const [totalizacion,setTotalizacion]    =   useState({})

    // Seleccionar los productos y el número de WhatsApp del estado de Redux
    const { products, quantities }  =   useSelector((state: any) => state.shoppingCart);

    // Función para cargar los productos y el número de WhatsApp desde la API y almacenarlos en Redux
    const getInit = () => {
        formData.handleRequest(formData.backend + "/products/list?per_page=100").then((response: any) => {
            if (response && response.products) {
                dispatch(setProducts({ products: response.products, whatsapp: response.whatsapp }));
            }            
        });
    };

    useEffect(getInit, [dispatch]);

    // Cargar cantidades desde localStorage al cargar el componente
    useEffect(() => {
        const storedQuantities = localStorage.getItem("quantities");
        if (storedQuantities) {
            const parsedQuantities = JSON.parse(storedQuantities);
            dispatch(setQuantity(parsedQuantities));
            
        }
    }, [dispatch]);


    useEffect(()=>{

        let totalToRedux:number    =   0
        Object.entries(totalizacion).map((row:any)=>{
            return totalToRedux+=row[1]            
        })

        if(totalToRedux>0){
            dispatch(setTotal(totalToRedux))
        }        

    },[totalizacion])

    return (
        <div className="w-full mt-4">
            <div className="grid grid-cols-8 gap-4">
                <div className="col-span-8">
                    <h2 className="text-md md:text-2xl font-bold uppercase">
                        Carro de compras
                    </h2>
                </div>
                <div className="col-span-8 md:col-span-5">
                    {Object.entries(quantities).map((product, key) => {
                        const productData = products.find((search: any) => search.id === parseInt(product[0]));
                        if (!productData) return null;
                        return <ShoppinCartItem product={productData} key={key} setTotalizacion={setTotalizacion}/>;
                    })}
                </div>
                <div className="col-span-8 md:col-span-3">
                    <Totalizador/>
                </div>
            </div>
        </div>
    );
};

export default ShoppingcartComponent;
