/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular 3115000926
 *  website: Programandoweb.net
 *  Proyecto: Ivoovle *  
 * ---------------------------------------------------
 */

'use client'

import React, { ReactNode } from 'react';
import { useRouter } from 'next/navigation';
import { MdArrowBackIos } from "react-icons/md";

interface BackProps {
  children: ReactNode; // Define que el componente aceptará cualquier nodo de React como children
}

const Back: React.FC<BackProps> = ({ children }) => {
  const router = useRouter();

  const handleBack = () => {
    if (window.history.length > 1) {
      router.back();
    } else {
      router.push('/'); // Redirige a la página de inicio si no hay historial
    }
  };
  return (
    <div className="p-5 bg-gray-100 rounded-lg">
      <div className='flex items-center cursor-pointer' onClick={handleBack}>
        <MdArrowBackIos className='h-6 w-6 mr-2' /> Volver atrás
      </div>
      {children}
    </div>
  );
};

export default Back;
