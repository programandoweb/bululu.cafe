'use client'
import React, { useState } from 'react';
import {
  Dialog,
  DialogPanel,
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  Popover,
  PopoverButton,
  PopoverGroup,
  PopoverPanel,
  Transition,
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
  { name: 'Hombres', description: 'Cat´logo para clientes vendedores', href: 'hombre', icon: MdOutlineMan },
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

function classNames(...classes:any) {
  return classes.filter(Boolean).join(' ');
}

interface HeaderProps {
  name: string;
}

const HeaderComponent: React.FC<HeaderProps> = ({ name }) => {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const params  = useParams();

  //console.log(mobileMenuOpen)

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
          <Popover className="relative">
            { ({ open }) => (
                <>
                    <PopoverButton  className="flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900">
                        Categorías
                        <ChevronDownIcon className="h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
                    </PopoverButton>
                    <Transition
                      enter="transition ease-out duration-200"
                      enterFrom="opacity-0 translate-y-1"
                      enterTo="opacity-100 translate-y-0"
                      leave="transition ease-in duration-150"
                      leaveFrom="opacity-100 translate-y-0"
                      leaveTo="opacity-0 translate-y-1"
                    >
                      <PopoverPanel className="absolute -left-8 top-full z-10 mt-3 w-screen max-w-md overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5">
                        {
                          ({ close }) => (
                            <>
                              <div className="p-4">
                                {products.map((item) => (
                                  <div
                                    key={item.name}
                                    className="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm leading-6 hover:bg-gray-50"
                                  >
                                    <div className="flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                      <item.icon className="h-6 w-6 text-gray-600 group-hover:text-indigo-600" aria-hidden="true" />
                                    </div>
                                    <div className="flex-auto">
                                      <Link onClick={() => close()} href={"/"+params.alias+"/tienda/"+item.href} className="block font-semibold text-gray-900">
                                        {item.name}
                                        <span className="absolute inset-0" />
                                      </Link>
                                      <p className="mt-1 text-gray-600">{item.description}</p>
                                    </div>
                                  </div>
                                ))}
                              </div>
                              <div className="grid grid-cols-2 divide-x divide-gray-900/5 bg-gray-50">
                                {callsToAction.map((item) => (
                                  <Link
                                    key={item.name}
                                    href={"/"+params.alias+"/tienda/"+item.href}
                                    className="flex items-center justify-center gap-x-2.5 p-3 text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-100"
                                  >
                                    <item.icon className="h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
                                    {item.name}
                                  </Link>
                                ))}
                              </div>
                            </>
                          )
                        }
                        
                      </PopoverPanel>
                    </Transition>      
                </>
              )
            }
            
          </Popover>

          {
            menuNotDropdown&&
            menuNotDropdown.map((item,key)=>{
              return  <Link href={"/"+params.alias+"/tienda/"+item.href} className="flex text-sm font-semibold leading-6 text-gray-900" key={key}>
                        {item.icon&&(<span><item.icon className="h-5 w-5 text-gray-400 mr-1" aria-hidden="true" /></span>)} <span>{item.name}</span>
                      </Link>
            })
          }

        </PopoverGroup>
        <div className="hidden lg:flex lg:flex-1 lg:justify-end">
          <Link href={"/"+params.alias+"/tienda/"+"#"} className="text-sm font-semibold leading-6 text-gray-900">
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
                      <DisclosureButton className="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                        Product
                        <ChevronDownIcon
                          className={classNames(open ? 'rotate-180' : '', 'h-5 w-5 flex-none')}
                          aria-hidden="true"
                        />
                      </DisclosureButton>
                      <DisclosurePanel className="mt-2 space-y-2">
                        {[...products, ...callsToAction].map((item) => (
                          <DisclosureButton
                            key={item.name}
                            as="a"
                            href={"/"+params.alias+"/tienda/"+item.href}
                            className="block rounded-lg py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                          >
                            {item.name}
                          </DisclosureButton>
                        ))}
                      </DisclosurePanel>
                    </>
                  )}
                </Disclosure>
                {
                  menuNotDropdown&&
                  menuNotDropdown.map((item,key)=>{
                    return  <Link href={"/"+params.alias+"/tienda/"+item.href} className="-mx-3  block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50" key={key}>
                              {item.icon&&(<span><item.icon className="h-5 w-5 flex-none text-gray-400" aria-hidden="true" /></span>)} <span>{item.name}</span>
                            </Link>
                  })
                }                
              </div>
              <div className="py-6">
                <Link
                  href={"/"+params.alias+"/tienda/#"}
                  className="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                >
                  Sesión de usuario
                </Link>
              </div>
            </div>
          </div>
        </DialogPanel>
      </Dialog>
    </header>
  );
}

export default HeaderComponent;
