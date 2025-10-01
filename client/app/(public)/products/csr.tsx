"use client"

import { useEffect } from "react";
import { toast } from "sonner";
import Link from "next/link";
import { useSearchParams } from "next/navigation";
import { Link2 } from "lucide-react";

import { Button } from "@/components/ui/button";

import { IProduct } from "@/interfaces/products";

export default function ProductsPageClient({ productsData }: { productsData: IProduct[] }) {
    const searchParams = useSearchParams();
    const message = searchParams.get("message");

    useEffect(() => {
        if (message) toast.warning(message);
    }, [message]);

    return (
        <div className="flex flex-col pl-6 pr-4 py-6 mx-auto w-full max-w-[800px] space-y-4">
            {productsData.map((product, index) => <div key={index}>{ProductItem(product)}</div>)}
        </div>
    )
}

function ProductItem(data: IProduct) {
    return (
        <Link href={"/product/" + data.id_product} className="w-full p-4 border rounded-md flex flex-col gap-1 min-h-32 active:bg-foreground/5">
            <h1 className="text-lg font-bold">{data.name}</h1>
            <p>{data.description ? 'Descrição:' : ''} <span className="text-primary/70">{data.description}</span></p>
            <Button className="mt-auto w-fit"><Link2 /> Ver Produto</Button>
        </Link>
    )
}