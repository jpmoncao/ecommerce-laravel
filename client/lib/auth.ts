'use server'

import { cookies } from 'next/headers';

interface TokenCacheEntry {
    ok: boolean;
    id_user?: string;
    expires: number;
}

interface TokenInfo {
    id_user: string;
    token_expires_at: string;
}

const TOKEN_CACHE: Map<string, TokenCacheEntry> = new Map();
const CACHE_OFFSET = 60 * 1000;

export async function getToken(): Promise<string | null> {
    const token = (await cookies()).get('auth_token')?.value ?? null;
    return token;
}

export async function getTokenInfo(token?: string): Promise<TokenInfo | null> {
    try {
        const res = await fetch(`${process.env.API_URL}/api/me`, {
            headers: { Authorization: `Bearer ${token}` },
        });
        if (!res.ok) return null;

        const { data } = await res.json();
        return data;
    } catch {
        return null;
    }
}

export async function getValidToken(token?: string): Promise<{ ok: boolean, id_user?: string }> {
    if (!token) return { ok: false };

    const now = Date.now();
    const cached = TOKEN_CACHE.get(token);
    if (cached && cached.expires > now) return { ok: cached.ok, id_user: cached.id_user };

    try {
        const data = await getTokenInfo(token);
        if (!data) return { ok: false };

        const expiresAt = new Date(data.token_expires_at).getTime();
        const ttl = expiresAt - now - CACHE_OFFSET;

        if (ttl <= 0) return { ok: false };

        TOKEN_CACHE.set(token, { ok: true, id_user: data.id_user, expires: now + ttl });
        return { ok: true, id_user: data.id_user };
    } catch {
        return { ok: false };
    }
}
