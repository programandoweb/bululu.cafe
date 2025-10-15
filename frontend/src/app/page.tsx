import Link from "next/link";
import React from "react";

export default function Home() {
  return  <main className="min-h-screen">
             <div className="flex flex-col items-center justify-center h-screen bg-white-100 6">        
              <div className={`mx-[56px] mt-[0px] flex items-center`}>
                  <div className="text-center w-full mb-4 h-2.5 font-poppins text-[36px] font-bold uppercase text-navy-700 dark:text-white">
                    {String(process.env.NEXT_PUBLIC_NAME)}
                  </div>
              </div>          
              <div className="text-center text-gray-600 mt-4">
                  Escoge tu experiencia
                  <div className={`mt-[0px] flex`}>
                    {/**
                    <Link href="/demo/food" className=" mr-1 px-10 linear mt-16 rounded-xl bg-brand-500 py-[12px] text-base font-medium text-white transition duration-200 hover:bg-brand-600 active:bg-brand-700 dark:bg-brand-400 dark:text-white dark:hover:bg-brand-300 dark:active:bg-brand-200">
                        Food
                    </Link>        
                    */
                    }
                    <Link rel="preload" href="/catalogo-el-meta-programandoweb/tienda" className="w-full px-10 linear mt-16 rounded-xl bg-brand-200 py-[12px] text-base font-medium text-white transition duration-200 hover:bg-brand-600 active:bg-brand-700 dark:bg-brand-400 dark:text-white dark:hover:bg-brand-300 dark:active:bg-brand-200">
                        Store
                    </Link>        
                  </div>
              </div>          
            </div>
          </main>  
}
