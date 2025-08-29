import React from "react";
import ProductDetail from "../../(store)/tienda/producto/[slug]/page";
import Back from "@/components/back";
const ProductoPage=(props:any)=>{
    return  <Back>
                <ProductDetail {...props}/>
            </Back>
}
export default ProductoPage;