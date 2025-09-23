"use client"

import { useState } from "react"
import Link from "next/link"
import { AlertCircle, ArrowLeft, PlusCircle, ShoppingCart } from "lucide-react"

import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Skeleton } from "@/components/ui/skeleton"
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert"

import { IProduct } from "@/interfaces/products"
import { IProductVariation } from "@/interfaces/product-variations"
import { IProductStock } from "@/interfaces/product-stocks"
import { Button } from "@/components/ui/button"
import { cn } from "@/lib/utils"

interface IProductData extends IProduct {
    variants: (IProductVariation & { stock: IProductStock })[];
}

export default function ProductPageClient({ productData }: { productData: IProductData }) {
    const [product] = useState<IProductData>(productData)
    const [variant, setVariant] = useState(product.variants.find(variantion => variantion.stock.quantity) ?? product.variants[0])
    const [imgLoaded, setImgLoaded] = useState(false);

    const handleChangeVariant = async (value: string) => {
        const selected = product.variants.find(v => v.id_product_variation.toString() === value)
        if (!selected) return
        setVariant(selected)
    }

    if (product.variants.length === 0)
        return (
            <div className="flex flex-col items-center h-dvh w-full">
                <Alert className="max-w-[800px] w-full mt-20 space-y-2 px-4 py-6" variant='destructive'>
                    <AlertTitle className="flex gap-2 items-center"><AlertCircle /> O produto "{product.id_product} - {product.name}" está indisponível!</AlertTitle>
                    <AlertDescription>Contate o administrador para verificar a disponibildade do produto...</AlertDescription>
                    <div className="flex gap-4 mt-4">
                        <Link href={'/products'}>
                            <Button variant="outline"><ArrowLeft /> Voltar</Button></Link>
                        <Link href={'/create-variation?product_id=' + product.id_product}><Button variant="destructive"><PlusCircle /> Cadastrar Variações</Button></Link>
                    </div>
                </Alert>
            </div>
        )

    return (
        <div className="flex flex-col pl-6 pr-4 py-6 mx-auto w-full max-w-[800px] space-y-8">
            <Link href={"/products"} className="bg-primary text-primary-foreground rounded px-2 py-1 flex w-fit gap-2 hover:bg-primary/90 mt-2"><ArrowLeft /> Voltar</Link>

            <div className="flex flex-col gap-2">
                <h1 className="text-2xl font-bold">{product.name}</h1>
                <p className="text-primary/80">{product.description}</p>
            </div>

            <section className="flex justify-between gap-8 w-full">
                <div className="flex-2/3">
                    <div className="relative w-full h-[400px]">
                        {!imgLoaded && <Skeleton className="absolute inset-0" />}
                        <img
                            src="https://picsum.photos/1920"
                            alt="Foto de exemplo"
                            loading="lazy"
                            className={`${imgLoaded ? 'opacity-100' : 'opacity-0'} transition-opacity duration-300`}
                            onLoad={() => setImgLoaded(true)}
                        />
                    </div>
                </div>

                <div className="flex-1/3 flex flex-col gap-6">
                    <div className="flex flex-col">
                        <h1 className="text-lg">Escolha a variação:</h1>
                        <Select value={variant.id_product_variation.toString()} onValueChange={handleChangeVariant}>
                            <SelectTrigger className="w-full">
                                <SelectValue placeholder="Selecione uma variação" />
                            </SelectTrigger>
                            <SelectContent>
                                {product.variants.map((v) => (
                                    <SelectItem key={v.id_product_variation} value={v.id_product_variation.toString()} className={cn(v.stock.quantity == 0 ? 'text-foreground/50' : '')}>
                                        {v.variation}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>

                    <div className="flex flex-col">
                        <h1 className="text-lg">Preço:</h1>
                        <p className="flex items-end">
                            <span className="text-xl text-primary/80">R$</span>{" "}
                            <span className="text-4xl">
                                {variant.amount.toLocaleString("pt-br", { minimumFractionDigits: 2 })}
                            </span>
                        </p>
                    </div>

                    <div className="flex flex-col">
                        <h1 className="text-lg">Em Estoque:</h1>
                        {variant.stock.quantity > 0
                            ? (
                                <p>
                                    <span className="text-4xl">{variant.stock.quantity}</span>{" "}
                                    <span className="text-xl text-primary/80">disponíveis</span>
                                </p>
                            )
                            : (
                                <p className="text-xl text-destructive/80">Produto indisponível</p>
                            )
                        }
                    </div>

                    <Button disabled={variant.stock.quantity == 0}><ShoppingCart /> Adicionar ao carrinho</Button>
                </div>
            </section>
        </div>
    )
}
