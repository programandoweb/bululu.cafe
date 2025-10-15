'use client'
import React, { useState } from 'react';
import {
  Dialog,
  DialogPanel,
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  PopoverGroup,  
} from '@headlessui/react';
import {
  Bars3Icon,
  ChartPieIcon,
  XMarkIcon,
} from '@heroicons/react/24/outline';
import { MdOutlineGirl } from "react-icons/md";
import { MdFavoriteBorder } from "react-icons/md";
import { MdCrisisAlert } from "react-icons/md";
import { ChevronDownIcon, PhoneIcon, PlayCircleIcon } from '@heroicons/react/20/solid';
import TextLogo from '../logo/Texto';
import { encodeStringToUrl } from "@/utils/fuctions";
import { useParams } from 'next/navigation';
import Link from 'next/link';
import { MdShoppingCart } from "react-icons/md";
import { MdOutlineMan } from "react-icons/md";


const html:string = "Hola, deseo contactar con ustedes bajo mi interés de comercializar sus productos."

const products    = [
  { name: 'Tendencia', description: 'Colores vibrantes, cortes audaces, y sostenibilidad destacada', href: 'tendencia', icon: ChartPieIcon },
  { name: 'Descuentos', description: 'Descuentos atractivos que no debes dejar pasar', href: 'descuentos', icon: MdCrisisAlert },
  { name: 'Mujer', description: 'Obtén crédito con sólo tu documento', href: 'mujer', icon: MdOutlineGirl },
  { name: 'Hombres', description: 'Catálogo para clientes vendedores', href: 'hombre', icon: MdOutlineMan },
];

const callsToAction = [
  { name: 'Videos', href: '#', icon: PlayCircleIcon },
  { name: 'Contacto Ventas', href: "https://wa.me/"+process.env.NEXT_PUBLIC_WHATSAPP+"?text="+encodeStringToUrl(html), icon: PhoneIcon },
];

const IconSC:any      =   MdShoppingCart;

const menuNotDropdown = [
  { name: 'Favoritos', href: 'favorites', icon: MdFavoriteBorder },
  { name: 'Carro de compra', href: "shoppingcart", icon: IconSC },
];

interface HeaderProps {
  name: string;
}

const HeaderComponent: React.FC<HeaderProps> = ({ name }) => {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const params  = useParams();

  return (
    <header className="fixed top-0 left-0 w-full bg-white shadow-sm z-50">
      <nav className="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
        <div className="flex lg:flex-1">
          <Link href={"/"+params.alias+"/tienda"} className="-m-1.5 p-1.5">
            <TextLogo name={name}/>            
          </Link>
        </div>
        <div className="flex lg:hidden">
          <button
            type="button"
            className="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
            onClick={() => setMobileMenuOpen(true)}
          >
            <span className="sr-only">Open main menu</span>
            <Bars3Icon className="h-6 w-6" aria-hidden="true" />
          </button>
        </div>
        <PopoverGroup className="hidden lg:flex lg:gap-x-12">
          {
            menuNotDropdown&&
            menuNotDropdown.map((item,key)=>(
              <Link href={"/"+params.alias+"/tienda/"+item.href} className="flex items-center text-sm font-semibold leading-6 text-gray-900" key={key}>
                {item.icon&&(<item.icon className="h-5 w-5 text-gray-400 mr-2" aria-hidden="true" />)} 
                <span>{item.name}</span>
              </Link>
            ))
          }
        </PopoverGroup>
        <div className="hidden lg:flex lg:flex-1 lg:justify-end">
          <Link href={"/"+params.alias+"/tienda/#"} className="text-sm font-semibold leading-6 text-gray-900">
            Mi cuenta <span aria-hidden="true">&rarr;</span>
          </Link>
        </div>
      </nav>
      <Dialog className="lg:hidden" open={mobileMenuOpen} onClose={setMobileMenuOpen}>
        <div className="fixed inset-0 z-10" />
        <DialogPanel className="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
          <div className="flex items-center justify-between">
            <Link href={"/"+params.alias+"/tienda"} className="-m-1.5 p-1.5">
              <TextLogo name={name}/> 
            </Link>
            <button
              type="button"
              className="-m-2.5 rounded-md p-2.5 text-gray-700"
              onClick={() => setMobileMenuOpen(false)}
            >
              <span className="sr-only">Close menu</span>
              <XMarkIcon className="h-6 w-6" aria-hidden="true" />
            </button>
          </div>
          <div className="mt-6 flow-root">
            <div className="-my-6 divide-y divide-gray-500/10">
              <div className="space-y-2 py-6">
                <Disclosure as="div" className="-mx-3">
                  {({ open }) => (
                    <>
                      <DisclosurePanel className="mt-2 space-y-2">
                        {[...products, ...callsToAction].map((item) => (
                          <DisclosureButton
                            key={item.name}
                            as="a"
                            href={"/"+params.alias+"/tienda/"+item.href}
                            className="flex items-center rounded-lg py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                          >
                            <item.icon className="h-5 w-5 flex-none text-gray-400 mr-2" aria-hidden="true" />
                            <span>{item.name}</span>
                          </DisclosureButton>
                        ))}
                      </DisclosurePanel>
                    </>
                  )}
                </Disclosure>
                {
                  menuNotDropdown&&
                  menuNotDropdown.map((item,key)=>(
                    <Link href={"/"+params.alias+"/tienda/"+item.href} className="flex items-center -mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50" key={key}>
                      {item.icon&&(<item.icon className="h-5 w-5 flex-none text-gray-400 mr-2" aria-hidden="true" />)} 
                      <span>{item.name}</span>
                    </Link>
                  ))
                }                
              </div>
              {
                /*
                  <div className="py-6">
                    <Link
                      href={"/"+params.alias+"/tienda/#"}
                      className="flex items-center -mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                    >
                      Sesión de usuario
                    </Link>
                  </div> 
                */
              }              
            </div>
          </div>
        </DialogPanel>
      </Dialog>
    </header>
  );
}

export default HeaderComponent;
