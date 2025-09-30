"use server";

import { createSafeActionClient } from "next-safe-action";
import { productVariationSchema, productVariationResponseSchema } from "@/schemas/create-variation";

const actionClient = createSafeActionClient();

export const serverAction = actionClient
  .inputSchema(productVariationSchema)
  .outputSchema(productVariationResponseSchema)
  .action(async ({ parsedInput }) => {
    console.log(parsedInput)

    const res = await fetch(process.env.API_URL + "/api/variations", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(parsedInput),
    });

    if (!res.ok) throw new Error("Erro ao criar variação");

    const json = await res.json();
    return productVariationResponseSchema.parse(json.data);
  });

