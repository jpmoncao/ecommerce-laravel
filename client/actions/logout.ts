"use server";

import { createSafeActionClient } from "next-safe-action";
import { cookies } from "next/headers";

const actionClient = createSafeActionClient();

export const logoutAction = actionClient.action(async () => {
    try {
        (await cookies()).set("auth_token", "", {
            httpOnly: true,
            secure: process.env.NODE_ENV === "production",
            path: "/",
            expires: new Date(0),
        });

        return { success: true };
    } catch (err) {
        return { success: false, error: (err as Error).message };
    }
});
