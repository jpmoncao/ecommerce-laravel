"use server";

import { format } from "date-fns";
import { createSafeActionClient } from "next-safe-action";

import { registerSchema } from "@/schemas/register";

const actionClient = createSafeActionClient();

export const serverAction = actionClient
    .inputSchema(registerSchema)
    .action(async ({ parsedInput }) => {
        const data = { ...parsedInput, born_date: format(parsedInput.born_date, 'yyyy-MM-dd') }

        const res = await fetch(process.env.API_URL + "/api/users", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
        });

        if (!res.ok)
            throw new Error("Erro ao efetuar registro de usuário!");

        return;
    });
