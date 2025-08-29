'use client';
import React, { useEffect, useState } from "react";
import ShoppinCartItem from "./ShoppinCartItem";
import useFormData from "@/hooks/useFormDataNew";

let getInit:any;
let formData:any;

const FavoritesComponent = () => {
    
    formData    =   useFormData(false,false,false)

    const [products,setData]            =   useState([])
    const [favorities,setFavorities]    =   useState([])

    getInit = () => {
        formData.handleRequest(formData.backend + "/products/list?per_page=100").then((response: any) => {
            if (response&&response.products) {
                //console.log(response.products)
                setData(response.products);
            }
        });
    };    

    useEffect(getInit,[])
    
    // Cargar favoritos y cantidades desde localStorage al cargar el componente
    useEffect(() => {
        const storedQuantities = localStorage.getItem("favorites");
        if (storedQuantities) {
            const parsedQuantities = JSON.parse(storedQuantities);
            setFavorities(parsedQuantities)            
        }
    }, []);

    
    console.log(favorities)

    return (
        <div className="w-full mt-4">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div className="col-span-4">
                    <h2 className="text-md md:text-2xl font-bold uppercase">
                        Productos favoritos guardados por ti
                    </h2>
                </div>
                {   
                    Object.entries(favorities).map((product, key) => {

                        const result:any    =   products.find((search:any)=>search.id===parseInt(product[0]))
                        
                        if(!result||!product[1])return;

                        return  <div key={key}>
                                    <ShoppinCartItem product={result} />
                                </div>                
                    })
                }                
            </div>
        </div>
    );
};

export default FavoritesComponent;
