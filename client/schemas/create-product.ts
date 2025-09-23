import * as z from "zod";

export const productSchema = z.object({
    name: z.string().min(3, "Nome deve ter pelo menos 3 caracteres"),
    description: z.string().optional(),
});

export type ProductSchema = z.infer<typeof productSchema>;

export const productResponseSchema = z.object({
    data: z.object({
        id_product: z.number(),
        name: z.string(),
        description: z.string(),
        updated_at: z.string(),
        created_at: z.string()
    })
});

export type ProductResponse = z.infer<typeof productResponseSchema>;
