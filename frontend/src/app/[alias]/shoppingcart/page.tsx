import React from "react";
import Shoppingcart from "../(store)/cafe-bar/shoppingcart/page";
import Back from "@/components/back";

const ShoppingCartPage=(props:any)=>{
    return  <Back>
                <Shoppingcart {...props}/>
            </Back>
}
export default ShoppingCartPage;