import Link from "next/link";
import { Link2 } from "lucide-react";

import { CreateProductVariationForm } from "./form";

import { IProduct } from "@/interfaces/products";

const fetchProducts = async () => {
    return await fetch('http://localhost:8000/api/products')
        .then(response => response.ok ? response.json() : null)
        .then(json => json.data)
        .catch(err => console.error(err));
}

export default async function ProductsPage() {
    const products: IProduct[] = await fetchProducts();

    return (
        <div className="flex flex-col pl-6 pr-4 py-6 mx-auto w-full max-w-[800px]">
            <CreateProductVariationForm products={products} />
        </div>
    )
}
