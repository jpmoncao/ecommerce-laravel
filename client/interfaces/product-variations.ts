import { IProductStock } from "./product-stocks";

export interface IProductVariation {
    id_product_variation: string;
    variation: string;
    amount: number;
    product_id: number;
    created_at?: string;
    updated_at?: string;
}

export interface IProductVariationWithStock extends IProductVariation {
    stock: IProductStock
}