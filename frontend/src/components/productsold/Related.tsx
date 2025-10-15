'use client'
import React, { useEffect, useState } from "react";
import ProductsItem from "./RelatedItem";
import useFormData from "@/hooks/useFormDataNew";

const Products = () => {
    const [data,setData]    =   useState([])
    const formData:any      =   useFormData(false,false,false)

    const getInit = () => {
        formData.handleRequest(formData.backend + "/products/related").then((response: any) => {
            if (response&&response.products) {
            //console.log(response.products)
            setData(response.products);
            }
        });
    };      
    
    useEffect(getInit, []);

    //console.log(data)

    return (
        <div className="w-full mt-4">
            <div className="grid grid-cols-2 md:grid-cols-2 xlg:grid-cols-3 gap-3">            
                {
                    data.map((product: any, key: number) => { // Define el tipo de 'product' como 'any' y 'key' como 'number'
                        if(key > 5) return;
                        return (
                            <div className="mb-4" key={key}>
                                <ProductsItem product={product} />
                            </div>
                        );
                    })
                }
            </div>
        </div>
    );
};

export default Products;
