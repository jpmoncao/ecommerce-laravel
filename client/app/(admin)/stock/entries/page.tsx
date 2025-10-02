'use client'

import { useState } from "react";
import { toast } from "sonner";
import { motion } from "motion/react";
import { useSearchParams, useRouter } from "next/navigation"
import { ArrowRight, Check } from "lucide-react";

import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { DrawerDialog } from "@/components/ui/drawer-dialog";
import { Separator } from "@/components/ui/separator";
import { Badge } from "@/components/ui/badge";

import { postStockEntries } from "@/actions/post-stock-entries";

import { IProductVariationWithStock } from "@/interfaces/product-variations";
import { IProductStockEntry } from "@/interfaces/product-stock-entry";

import { cn } from "@/lib/utils";

interface VariationsToStockEntry extends IProductVariationWithStock {
    observation?: string;
}

export default function StockEntriesPage() {
    const router = useRouter();
    const searchParams = useSearchParams();
    const variations = searchParams.get("variations") ? JSON.parse(searchParams.get("variations")!) as VariationsToStockEntry[] : [];

    const [drawerOpen, setDrawerOpen] = useState(false);
    const [postSuccess, setPostSuccess] = useState(false);
    const [variationList, setVariationList] = useState<VariationsToStockEntry[]>(variations);

    const handlePostStockEntries = async () => {
        const payload = variationList
            .filter(v => v.stock.quantity > 0)
            .map((v): IProductStockEntry => ({
                product_variation_id: v.id_product_variation,
                quantity: v.stock.quantity,
                observation: v.observation || null,
            }));

        if (payload.length === 0) {
            toast.error("Nenhuma variação com quantidade maior que zero para lançar.");
            return;
        }

        try {
            await postStockEntries(payload);

            setPostSuccess(true);
            setTimeout(() => {
                router.push('/stock');
            }, 3000);
        } catch (error) {
            toast.error("Ocorreu um erro ao realizar o lançamento de estoque.");
            console.error(error);
        } finally {
            setDrawerOpen(false);
        }
    };


    function handleQuantityIncrementDecrement(index: number, delta: number) {
        setVariationList(prev => {
            const newList = [...prev];
            newList[index] = {
                ...newList[index],
                stock: {
                    ...newList[index].stock,
                    quantity: Math.max(0, newList[index].stock.quantity + delta)
                }
            };
            return newList;
        });
    }

    function handleQuantityChange(index: number, value: number) {
        setVariationList(prev => {
            const newList = [...prev];
            newList[index] = {
                ...newList[index],
                stock: {
                    ...newList[index].stock,
                    quantity: Math.max(0, value)
                }
            };
            return newList;
        });
    }

    if (postSuccess)
        return (
            <div className="flex items-center justify-center">
                <div className="p-2 sm:p-5 md:p-8 w-full rounded-md gap-2 border mx-12 mt-12">
                    <motion.div
                        initial={{ opacity: 0, y: -16 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.4, stiffness: 300, damping: 25 }}
                        className="h-full py-6 px-3"
                    >
                        <motion.div
                            initial={{ scale: 0.5 }}
                            animate={{ scale: 1 }}
                            transition={{
                                delay: 0.3,
                                type: "spring",
                                stiffness: 500,
                                damping: 15,
                            }}
                            className="mb-4 flex justify-center border rounded-full w-fit mx-auto p-2"
                        >
                            <Check className="size-8" />
                        </motion.div>
                        <h2 className="text-center text-2xl text-pretty font-bold mb-2">
                            Sucesso!
                        </h2>
                        <p className="text-center text-lg text-pretty text-muted-foreground">
                            Aguarde, você será redirecionado em breve...
                        </p>
                    </motion.div>
                </div>
            </div>
        );

    return (
        <div className="flex flex-col p-6 mx-auto w-full max-w-[800px]">
            <h1 className="text-2xl font-bold mb-4">Lançamento de Estoque</h1>
            {variations && variations.length === 0
                ? <p>Nenhuma variação selecionada para lançamento de estoque.</p>
                : (<div>
                    <div className="mb-4 flex justify-between md:items-center md:flex-row gap-y-2 flex-col">
                        <h2 className="text-xl font-semibold mb-2">Variações Selecionadas:</h2>

                        <div className="flex flex-col">
                            <label className="text-sm font-medium text-muted-foreground mb-1 ml-auto">Quantidade para todas:</label>
                            <Input
                                className="w-32 ml-auto"
                                type="number"
                                onChange={(e) => {
                                    const value = Number(e.target.value);
                                    setVariationList(prev => prev.map(v => ({
                                        ...v,
                                        stock: { ...v.stock, quantity: value }
                                    })));
                                }}
                            />
                        </div>

                    </div>
                    <ul className="list-disc list-inside flex flex-col gap-2">
                        {variations.map((variation, index) => (
                            <div key={index} className="p-4 border rounded-md flex gap-4 items-center justify-between md:flex-row flex-col">
                                <div>
                                    <p className="text-muted-foreground">#{variation.product_id}</p>
                                    <h2 className="font-medium">{variation.variation}<span className="text-muted-foreground font-normal"> (ID: {variation.id_product_variation})</span></h2>
                                    <p className="text-muted-foreground">Estoque atual: {variation.stock.quantity}</p>
                                    <Input
                                        type="text"
                                        className="w-full mt-1"
                                        placeholder="Observação (opcional)"
                                        value={variationList[index].observation || ""}
                                        onChange={(e) => {
                                            const value = e.target.value;
                                            setVariationList(prev => {
                                                const newList = [...prev];
                                                newList[index] = { ...newList[index], observation: value };
                                                return newList;
                                            });
                                        }}
                                    />
                                </div>
                                <div className="flex flex-col gap-1">
                                    <label htmlFor={`quantity-${index}`} className="block text-sm font-medium text-muted-foreground mb-1">Quantidade a adicionar:</label>
                                    <div className="ml-auto flex items-center gap-1">
                                        <Button variant="outline" className="mr-2" onClick={() => handleQuantityIncrementDecrement(index, -1)}>-</Button>
                                        <Input
                                            type="text"
                                            id={`quantity-${index}`}
                                            className="w-24"
                                            value={variationList[index].stock.quantity}
                                            onChange={(e) => handleQuantityChange(index, isNaN(Number(e.target.value)) ? 0 : Number(e.target.value))}
                                        />
                                        <Button variant="outline" className="ml-2" onClick={() => handleQuantityIncrementDecrement(index, 1)}>+</Button>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </ul>

                    <div className="mt-6 w-full">
                        <Button className="py-6 ml-auto text-lg flex items-center" onClick={() => setDrawerOpen(true)}>Confirmar Lançamento</Button>
                    </div>
                </div>)
            }

            <DrawerDialog
                title="Lançamentos de estoque"
                description='Produtos com quantidade igual a zero não serão lançados no estoque.'
                open={drawerOpen}
                setOpen={setDrawerOpen}
                onSubmit={handlePostStockEntries}
            >
                <div className="grid items-start gap-6 overflow-y-auto px-4 pb-4">
                    {variationList.length === 0 ? <p>Nenhuma variação selecionada.</p> : (
                        variationList.map((variation, index) => (
                            <div key={index} className="p-4 border rounded-md">
                                <div className="flex justify-between items-end">
                                    <p className="font-medium text-muted-foreground">#{variation.product_id}</p>

                                    {variation.stock.quantity === 0 && <Badge variant="destructive">Desconsiderado</Badge>}
                                </div>
                                <h2 className={cn("font-medium", variation.stock.quantity === 0 && "line-through text-muted-foreground")}>{variation.variation}</h2>
                                {variation.stock.quantity > 0
                                    ? (
                                        <p className="text-muted-foreground flex items-center gap-1">
                                            Estoque: {variations.find(v => v.id_product_variation === variation.id_product_variation)?.stock.quantity && ''}
                                            <ArrowRight size={12} />
                                            <span className="font-medium">{variation.stock.quantity}</span>
                                        </p>
                                    )
                                    : ''
                                }

                                {variation.observation && (
                                    <>
                                        <Separator className="my-2" />
                                        <p className="italic text-sm text-muted-foreground">Obs: {variation.observation}</p>
                                    </>
                                )}
                            </div>
                        )))}
                </div>
            </DrawerDialog>
        </div >
    )
}