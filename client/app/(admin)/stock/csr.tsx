'use client'

import { useState } from "react"
import Link from "next/link"
import { PackagePlus, PlusCircle } from "lucide-react"

import { Button } from "@/components/ui/button"
import TableVariations from "./components/table-variations"
import { DrawerSelectedVariations } from "./components/drawer-selected-variations"

import { IProduct } from "@/interfaces/products"
import { IProductVariationWithStock } from "@/interfaces/product-variations"
import { toast } from "sonner"
import { Badge } from "@/components/ui/badge"

interface IProductData extends IProduct {
    variants: IProductVariationWithStock[];
}

export default function StockPageClient({ productsData }: { productsData: IProductData[] }) {
    const [variationsSelected, setVariationsSelected] = useState<IProductVariationWithStock[]>([]);
    const [drawerOpen, setDrawerOpen] = useState(false);

    const handleVariationsSelected = (productId: number, variations: IProductVariationWithStock[]) => {
        setVariationsSelected((prev) => {
            const filtered = prev.filter(v => v.product_id !== productId);
            return [...filtered, ...variations];
        });
    };



    const handleGoToStockEntries = () => {
        if (variationsSelected.length === 0) {
            toast.warning('Selecione as variações para alterar o estoque.', { duration: 4000 });
            return;
        }

        setDrawerOpen(true);
    }


    function ProductItem(data: IProductData) {
        return (
            <div className="w-full p-4 border rounded-md flex flex-col gap-1 min-h-32">
                <h1 className="text-lg font-bold">{data.name}</h1>
                <p>{data.description ? 'Descrição:' : ''} <span className="text-primary/70">{data.description}</span></p>
                {
                    data.variants.length > 0
                        ? <TableVariations
                            data={data.variants}
                            onActionClick={handleVariationsSelected}
                        />
                        : <Link href={'/create-variation?product_id=' + data.id_product}>
                            <Button variant="destructive"><PlusCircle /> Cadastrar Variações</Button>
                        </Link>
                }
            </div>
        )
    }

    return (
        <div className="flex flex-col p-6 mx-auto w-full max-w-[800px]">
            <header className="flex justify-between items-center mb-6">
                <h1 className="text-2xl font-bold mb-4">Estoque</h1>
                <Button
                    onClick={handleGoToStockEntries}>
                    <PackagePlus />
                    <span>Alterar Estoque</span>
                    {variationsSelected.length > 0
                        && <Badge variant="secondary">{variationsSelected.length}</Badge>}
                </Button>
            </header>
            <main className="flex flex-col w-full space-y-4">
                {productsData.map((product, index) => <div key={index}>{ProductItem(product)}</div>)}
            </main>

            <DrawerSelectedVariations
                variations={variationsSelected}
                open={drawerOpen}
                setOpen={setDrawerOpen}
            />
        </div>
    )
}