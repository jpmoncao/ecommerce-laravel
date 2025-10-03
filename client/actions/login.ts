"use server";

import { cookies } from 'next/headers'
import { createSafeActionClient } from "next-safe-action";

import { loginSchema } from "@/schemas/login";
import { UserUnauthorizedError } from "@/exceptions/users";

const actionClient = createSafeActionClient({
  handleServerError: (e) => ({ name: e?.name, message: e?.message, stack: e?.stack }),
});

const SECONDS_IN_A_DAY = 60 * 60 * 24;

export const serverAction = actionClient
  .inputSchema(loginSchema)
  .action(async ({ parsedInput }) => {
    const res = await fetch(process.env.API_URL + "/api/login", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(parsedInput),
    });

    if (res.status === 401)
      throw new UserUnauthorizedError("Credenciais inválidas!");

    if (!res.ok)
      throw new Error("Erro ao efetuar login!");


    const json = await res.json();

    (await cookies()).set("auth_token", json.token, {
      httpOnly: true,
      secure: process.env.NODE_ENV === "production",
      path: "/",
      maxAge: SECONDS_IN_A_DAY * 7,
    });

    return;
  });

