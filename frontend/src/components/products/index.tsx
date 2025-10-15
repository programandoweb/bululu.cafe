import React from "react";
import ProductsItem from "./item";

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
    dataset: Product[];
}

const Related: React.FC<RelatedProps> = ({ title, dataset }) => {
    return (
        <div className="w-full mt-4">
            <div className="grid grid-cols-4 gap-4">
                <div className="col-span-4">
                    <h2 className="text-md md:text-2xl font-bold uppercase">
                        {title}
                    </h2>
                </div>
                {dataset.map((product, key) => (
                    <div className="col-span-2 md:col-span-1 mb-4" key={key}>
                        <ProductsItem product={product} />
                    </div>
                ))}
            </div>
        </div>
    );
};

export default Related;
