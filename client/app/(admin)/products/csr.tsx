"use client"

import { useEffect } from "react";
import { toast } from "sonner";
import Link from "next/link";
import { useSearchParams } from "next/navigation";
import { Link2 } from "lucide-react";

import { IProduct } from "@/interfaces/products";

export default function ProductsPageClient({ productsData }: { productsData: IProduct[] }) {
    const searchParams = useSearchParams();
    const message = searchParams.get("message");

    useEffect(() => {
        if (message) toast.warning(message);
    }, [message]);

    return (
        <div className="flex flex-col pl-6 pr-4 py-6 mx-auto w-full max-w-[800px]">
            {productsData.map((product, index) => <div key={index}>{ProductItem(product)}</div>)}
        </div>
    )
}

function ProductItem(data: IProduct) {
    return (
        <div className="w-full px-2 py-4 border rounded flex flex-col gap-1">
            <h1 className="text-lg font-bold">{data.name}</h1>
            <p>Descrição: <span className="text-primary/70">{data.description}</span></p>
            <Link href={"/product/" + data.id_product} className="bg-primary text-primary-foreground rounded px-2 py-1 flex w-fit gap-2 hover:bg-primary/90 mt-2"><Link2 /> Ver Produto</Link>
        </div>
    )
}