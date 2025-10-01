"use client"

import * as React from "react"
import Link from "next/link"

import { Button } from "@/components/ui/button"
import {
    Drawer,
    DrawerClose,
    DrawerContent,
    DrawerDescription,
    DrawerFooter,
    DrawerHeader,
    DrawerTitle,
} from "@/components/ui/drawer"

import { IProductVariationWithStock } from "@/interfaces/product-variations"

import { cn } from "@/lib/utils"
import { useMediaQuery } from "@/hooks/use-media-query"

type Props = {
    variations: IProductVariationWithStock[];
    open: boolean;
    setOpen?: (open: boolean) => void;
}

export function DrawerSelectedVariations(props: Props) {
    const isDesktop = useMediaQuery("(min-width: 768px)")

    function VariationsList({ className }: { className?: string }) {
        return (
            <div className={cn("grid items-start gap-6 overflow-y-auto", className)}>
                {props.variations.length === 0 ? <p>Nenhuma variação selecionada.</p> : (
                    props.variations.map((variation, index) => (
                        <div key={index} className="p-4 border rounded-md">
                            <p className="font-medium text-muted-foreground">#{variation.product_id}</p>
                            <h2 className="font-medium">{variation.variation}</h2>
                            <p className="text-muted-foreground">Estoque atual: {variation.stock.quantity}</p>
                        </div>
                    )))}
            </div>
        )
    }

    return (
        <Drawer open={props.open} onOpenChange={props.setOpen} direction={isDesktop ? "right" : "bottom"}>
            <DrawerContent>
                <DrawerHeader className="text-left">
                    <DrawerTitle>Variações Selecionadas</DrawerTitle>
                    <DrawerDescription>Clique em "Prosseguir" para continuar com o lançamento de estoque.</DrawerDescription>
                </DrawerHeader>

                <VariationsList className="px-4 pb-4" />

                <DrawerFooter className="pt-2">
                    <Link href={`/stock/entries?variation[]=${props.variations.map(v => `${v.id_product_variation}`).join(",")}`} className="w-full">
                        <Button type="submit" className="w-full">Prosseguir</Button>
                    </Link>
                    <DrawerClose asChild>
                        <Button variant="outline">Cancelar</Button>
                    </DrawerClose>
                </DrawerFooter>
            </DrawerContent>
        </Drawer>
    )
}
