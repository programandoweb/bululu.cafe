'use client'
import {  useEffect, useState } from "react"
import {  useSelector } from "react-redux";
import {  products  } from "@/utils/data";
import useWhatsAppOrder from "@/hooks/useWhatsAppOrder";

const Totalizador=()=>{
    const {Buttom}          =   useWhatsAppOrder();
    const quantities        =   useSelector((state:any) => state.shoppingCart.quantities);
    const [totalPrice,setTotalPrice]        =   useState(0);
    const [totalQuantity,setTotalQuantity]  =   useState(0);

    useEffect(()=>{
        let subTotal:any    =   0;
        let quantity        =   0;
        Object.entries(quantities).map((row)=>{
            const id            =   parseInt(row[0]);
            const quantity_:any =   row[1];
            const result:any    =   products.find((search:any)=>search.id===id)
            quantity            +=  quantity_;
            subTotal            +=  quantity_*result.price;
        })
        setTotalPrice(subTotal)
        setTotalQuantity(quantity)
    },[quantities])

    return  <div className=" bg-white w-[100%] border border-gray-200 rounded-md shadow-md hover:shadow-lg p-4 mb-3 ">
                <h3 className="text-xl md:text-lg font-semibold">Totalización:</h3>
                <div className="text-lg">Cant. productos <b>{totalQuantity} Und.</b> </div>
                <div className="text-lg">Total a pagar <b>${totalPrice}</b> </div>
                <div className="mt-5">
                    <Buttom/>                    
                </div>
            </div>
}
export default Totalizador
