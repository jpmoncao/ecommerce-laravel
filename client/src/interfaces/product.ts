import ProductVariationsInterface from "./product-variations";

export default interface ProductInterface {
    id_product: number;
    name: string;
    description: string;
    images: [string] | undefined;
    variants: [ProductVariationsInterface] | undefined;
    created_at: Date;
    updated_at: Date;
}
