import * as z from "zod";

export const productVariationSchema = z.object({
    product_id: z.coerce.number<number>('ID do produto não foi definido'),
    variation: z.string().min(3, "A descrição deve ter pelo menos 3 caracteres"),
    amount: z.coerce.number<number>().min(0.01, 'O valor deve ser maior ou igual à R$0,01.'),
});

export type ProductVariationSchema = z.infer<typeof productVariationSchema>;

export const productVariationResponseSchema = z.object({
    variation: z.string(),
    amount: z.number(),
    product_id: z.number(),
    id_product_variation: z.string(),
    updated_at: z.string(),
    created_at: z.string(),
});

export type ProductVariationResponse = z.infer<typeof productVariationResponseSchema>;
