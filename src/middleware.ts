/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular 3115000926
 *  website: Programandoweb.net
 *  Proyecto: Ivoolve *  
 * ---------------------------------------------------
 */

import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

export function middleware(request: NextRequest) {
    const { pathname } = request.nextUrl;

    // Verifica si el usuario está en la raíz del dominio
    if (pathname === '/') {
        // Redirige a /valencia/tienda
        return NextResponse.redirect(new URL('/bululu-cafe-bar-dosquebradas-que-hacer-en-dosquebras-pereira/cafe-bar', request.url));
    }

    // Permite el acceso a otras rutas
    return NextResponse.next();
}

export const config = {
    matcher: '/',
};
