"use server";

import { createSafeActionClient } from "next-safe-action";
import { productSchema, productResponseSchema } from "@/schemas/create-product";

const actionClient = createSafeActionClient();

export const serverAction = actionClient
  .inputSchema(productSchema)   
  .outputSchema(productResponseSchema)
  .action(async ({ parsedInput }) => {
    const res = await fetch("http://localhost:8000/api/products", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(parsedInput),
    });

    if (!res.ok) throw new Error("Erro ao criar produto");

    const json = await res.json();
    return productResponseSchema.parse(json.data);
  });

