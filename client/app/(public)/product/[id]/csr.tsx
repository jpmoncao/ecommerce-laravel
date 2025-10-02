"use client"

import { useState } from "react"
import Link from "next/link"
import { AlertCircle, ArrowLeft, Package2, PlusCircle, ShoppingCart } from "lucide-react"

import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Skeleton } from "@/components/ui/skeleton"
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert"
import { Button } from "@/components/ui/button"

import { IProduct } from "@/interfaces/products"
import { IProductVariation } from "@/interfaces/product-variations"
import { IProductStock } from "@/interfaces/product-stocks"

import { cn } from "@/lib/utils"

interface IProductData extends IProduct {
    variants: (IProductVariation & { stock: IProductStock })[];
}

export default function ProductPageClient({ productData }: { productData: IProductData }) {
    const [variant, setVariant] = useState(productData.variants.find(variantion => variantion.stock.quantity) ?? productData.variants[0])
    const [imgLoaded, setImgLoaded] = useState(false);

    const handleChangeVariant = async (value: string) => {
        const selected = productData.variants.find(v => v.id_product_variation.toString() === value)
        if (!selected) return
        setVariant(selected)
    }

    if (productData.variants.length === 0)
        return (
            <div className="flex flex-col items-center h-dvh w-full">
                <Alert className="max-w-[800px] w-full mt-20 space-y-2 px-4 py-6" variant='destructive'>
                    <AlertTitle className="flex gap-2 items-center"><AlertCircle /> O produto "{productData.id_product} - {productData.name}" está indisponível!</AlertTitle>
                    <AlertDescription>Contate o administrador para verificar a disponibildade do produto...</AlertDescription>
                    <div className="flex gap-4 mt-4">
                        <Link href={'/products'}>
                            <Button variant="outline"><ArrowLeft /> Voltar</Button></Link>
                        <Link href={'/create-variation?product_id=' + productData.id_product}><Button variant="destructive"><PlusCircle /> Cadastrar Variações</Button></Link>
                    </div>
                </Alert>
            </div>
        )

    return (
        <div className="flex flex-col pl-6 pr-4 py-6 mx-auto w-full max-w-[800px] space-y-8">
            <Link href={"/products"}>
                <Button>
                    <ArrowLeft /> Voltar
                </Button>
            </Link>

            <div className="flex flex-col gap-2">
                <h1 className="text-2xl font-bold">{productData.name}</h1>
                <p className="text-primary/80">{productData.description}</p>
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
                                {productData.variants.map((v) => (
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

                    <div className="flex flex-col gap-2">
                        <Button disabled={variant.stock.quantity == 0}><ShoppingCart /> Adicionar ao carrinho</Button>
                        <Link href={`/stock/entries?variations=${encodeURIComponent(JSON.stringify(productData.variants))}`}>
                            <Button className="w-full" variant='outline'><Package2 /> Lançar estoque</Button>
                        </Link>
                    </div>
                </div>
            </section>
        </div >
    )
}
