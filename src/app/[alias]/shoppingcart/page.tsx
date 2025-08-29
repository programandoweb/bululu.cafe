import React from "react";
import Shoppingcart from "../(store)/tienda/shoppingcart/page";
import Back from "@/components/back";

const ShoppingCartPage=(props:any)=>{
    return  <Back>
                <Shoppingcart {...props}/>
            </Back>
}
export default ShoppingCartPage;