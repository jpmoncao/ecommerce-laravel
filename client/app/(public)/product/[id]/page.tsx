import { redirect } from "next/navigation";
import ProductPageClient from "./csr";

async function fetchProduct(id: number) {
    const res = await fetch(`${process.env.API_URL}/api/products/${id}/variations`, {
        cache: "no-store",
    });

    if (!res.ok)
        switch (res.status) {
            case 404:
                redirect(`/products?message=Produto+não+encontrado`);
            default:
                redirect(`/products`);
        }

    const json = await res.json();
    return json.data;
}

export default async function ProductPage({ params }: { params: { id: string } }) {
    const { id } = await params;
    const product = await fetchProduct(Number(id))
    return <ProductPageClient productData={product} />
}
