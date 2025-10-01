import StockPageClient from "./csr";

const fetchProducts = async () => {
    return await fetch(process.env.API_URL + '/api/products/variations')
        .then(response => response.ok ? response.json() : null)
        .then(json => json.data)
        .catch(err => console.error(err));
}

export default async function StockPage() {
    const products = await fetchProducts();
    return <StockPageClient productsData={products} />

}