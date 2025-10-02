import ProductsPageClient from "./csr";

import { IProduct } from "@/interfaces/products";

const fetchProducts = async () => {
    return await fetch(process.env.API_URL + '/api/products')
        .then(response => response.ok ? response.json() : null)
        .then(json => json.data)
        .catch(err => console.error(err));
}

export default async function ProductsPage() {
    const products: IProduct[] = await fetchProducts();
    return <ProductsPageClient productsData={products} />
}