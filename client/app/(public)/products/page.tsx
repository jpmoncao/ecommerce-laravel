import ProductsPageClient from "./csr";

import { IProduct } from "@/interfaces/products";

const fetchProducts = async () => {
    return await fetch(process.env.API_URL + '/api/products')
        .then(response => response.ok ? response.json() : null)
        .then(json => json.data)
        .catch(err => console.error(err));
}

async function fetchProduct(id: number): Promise<IProduct[]> {
    const res = await fetch(process.env.API_URL + "/api/products", {
        cache: "no-store",
    });

    if (!res.ok)
        throw new Error();

    const json = await res.json();
    return json.data;
}

export default async function ProductsPage() {
    const products: IProduct[] = await fetchProducts();
    return <ProductsPageClient productsData={products} />
}