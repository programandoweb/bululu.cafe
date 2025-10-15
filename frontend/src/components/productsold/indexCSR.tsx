'use client'
import React, { useEffect, useState } from "react";
import ProductsItem from "./item";
import useFormData from "@/hooks/useFormDataNew";

interface Product {
    id: number;
    name: string;
    content: string;
    excerpt: string;
    price: number;
    slug: string;
    image: string;
    cover: string;
    featured: boolean;
    store_category_id: number;
}

interface RelatedProps {
    title: string;
    dataset?: Product[];
}

const Related: React.FC<RelatedProps> = ({ title, dataset }) => {


    const [data,setData]    =   useState([])
    const formData:any      =   useFormData(false,false,false)

    const getInit = () => {
        formData.handleRequest(formData.backend + "/products/list").then((response: any) => {
            if (response&&response.products) {
            //console.log(response.products)
            setData(response.products);
            }
        });
    };      
    
    useEffect(getInit, []);    

    return (
        <div className="w-full mt-4">
            <div className="grid grid-cols-1 gap-4 md:grid-cols-3 xl:grid-cols-4">
                <div className="col-span-1 md:col-span-3 xl:col-span-4">
                    <h2 className="text-md md:text-2xl font-bold uppercase">
                        {title}
                    </h2>
                </div>
                {data.map((product, key) => (
                    <div className="mb-4" key={key}>
                        <ProductsItem product={product} />
                    </div>
                ))}
            </div>
        </div>
    );
};

export default Related;
