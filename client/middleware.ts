import { NextRequest, NextResponse } from 'next/server';

import { getToken, getValidToken } from '@/lib/auth';

// Rotas protegidas (requer login)
const protectedRoutes = ['/create-product', '/create-variation', '/logout', '/stock'];

// Rotas que não devem ser acessadas por usuários autenticados
const unprotectedRoutes = ['/login', '/register'];

export async function middleware(req: NextRequest) {
    // Extrai o pathname e search da URL da requisição
    const { pathname, search } = req.nextUrl;

    // Verifica se a rota atual é protegida ou não protegida
    const isProtected = protectedRoutes.some(route => pathname.startsWith(route));
    const isUnprotected = unprotectedRoutes.some(route => pathname.startsWith(route));

    // Obtém o token de autenticação e valida-o
    const token = await getToken();
    const authenticated = token ? (await getValidToken(token)).ok : false;

    if (isProtected && !authenticated) {
        const loginUrl = new URL(`/login`, req.url);
        loginUrl.searchParams.set('callbackUrl', `${pathname}${search}`);
        return NextResponse.redirect(loginUrl);
    }

    if (isUnprotected && authenticated) {
        return NextResponse.redirect(new URL('/', req.url));
    }

    return NextResponse.next();
}

export const config = {
    matcher: ['/((?!api|trpc|_next|_vercel|.*\\..*).*)'],
};