'use client'

import { useState, useEffect } from "react";
import { getToken, getValidToken } from "@/lib/auth";

interface UserTokenStatus {
    idUser: string | null;
    isValid: boolean;
    loading: boolean;
}

export function useUserToken(): UserTokenStatus {
    const [status, setStatus] = useState<UserTokenStatus>({
        idUser: null,
        isValid: false,
        loading: true,
    });

    useEffect(() => {
        let mounted = true;

        async function checkToken() {
            try {
                const token = await getToken();

                if (!token) {
                    if (mounted) setStatus({ idUser: null, isValid: false, loading: false });
                    return;
                }

                const validToken = await getValidToken(token);
                if (!validToken.ok) {
                    if (mounted) setStatus({ idUser: null, isValid: false, loading: false });
                    return;
                }

                if (mounted) setStatus({ idUser: validToken.id_user ?? null, isValid: true, loading: false });
            } catch {
                if (mounted) setStatus({ idUser: null, isValid: false, loading: false });
            }
        }

        checkToken();
        return () => { mounted = false };
    }, []);

    return status;
}
