"use client";

import { useEffect, useState } from "react";
import { toast } from "sonner";
import { useRouter, useSearchParams } from "next/navigation";
import Link from "next/link";
import { zodResolver } from "@hookform/resolvers/zod";
import { useForm } from "react-hook-form";
import { useAction } from "next-safe-action/hooks";
import { motion } from "motion/react";
import { Check, MoreHorizontal, PlusCircle } from "lucide-react";

import ProductSelectorModal from "./components/product-selector-modal";
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from "@/components/ui/form";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Separator } from "@/components/ui/separator";

import { IProduct } from "@/interfaces/products";
import { ProductVariationSchema, productVariationSchema } from "@/schemas/create-variation";
import { serverAction } from "@/actions/create-variation";


export function CreateProductVariationForm({ products }: { products: IProduct[] }) {
    const searchParams = useSearchParams();
    const router = useRouter();

    const [productId, setProductId] = useState<number | null>(null);
    const [productSelected, setProductSelected] = useState<IProduct | null>(null);

    useEffect(() => {
        if (!productId)
            return;

        const product = products.find(product => product.id_product === productId);

        setProductSelected(product ?? null);

        form.setValue("product_id", Number(product?.id_product));
    }, [productId])

    useEffect(() => {
        const productId = Number(searchParams.get('product_id'));

        if (productId > 0)
            setProductId(productId);
    }, [searchParams]);

    const form = useForm<ProductVariationSchema>({
        resolver: zodResolver(productVariationSchema),
    });

    const formAction = useAction(
        serverAction,
        {
            onSuccess: (data) => {
                form.reset();
            },
            onError: (error) => {
                toast.error("Ocorreu um erro ao criar a variação.");
                console.error(error);
            },
        },
    );

    const handleSubmit = form.handleSubmit(async (data: ProductVariationSchema) => {
        console.log(data)
        formAction.execute(data);
    });

    const { isExecuting, hasSucceeded } = formAction;
    if (hasSucceeded) {
        return (
            <div className="p-2 sm:p-5 md:p-8 w-full rounded-md gap-2 border">
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
                    {/* <p className="text-center text-lg text-pretty text-muted-foreground">
                        Clique aqui para cadastrar as variações do produto
                    </p> */}
                    <div className="flex flex-col justify-center items-center gap-2 w-full">
                        <Button
                            variant="default"
                            onClick={() => {
                                form.reset({
                                    product_id: productSelected?.id_product,
                                    variation: "",
                                    amount: undefined,
                                });

                                formAction.reset();
                            }}
                        >
                            <PlusCircle /> Cadastrar mais
                        </Button>

                        <Link href={`/products`}>
                            <Button variant="outline">
                                <MoreHorizontal /> Ver produtos
                            </Button>
                        </Link>
                    </div>
                </motion.div >
            </div >
        );
    }

    const selectProduct = (id: number) => router.replace('?product_id=' + id);

    return (
        <Form {...form}>
            <form
                onSubmit={handleSubmit}
                className="flex flex-col p-2 sm:p-5 md:p-8 w-full rounded-md gap-2 border"
            >
                <ProductSelectorModal
                    products={products}
                    onSelect={selectProduct}
                />

                <Separator className="my-8" />

                {productSelected && (
                    <div>
                        <FormItem className="w-full">
                            <FormLabel>Produto </FormLabel>
                            <FormControl>
                                <Input
                                    required
                                    placeholder="Nome do produto"
                                    readOnly
                                    value={productSelected.id_product + '-' + productSelected.name}
                                />
                            </FormControl>

                            <FormMessage />
                        </FormItem>
                    </div>
                )}

                <FormField
                    control={form.control}
                    name="variation"
                    render={({ field }) => (
                        <FormItem className="w-full">
                            <FormLabel>Variação *</FormLabel>
                            <FormControl>
                                <Input
                                    {...field}
                                    required
                                    type="text"
                                    placeholder="Descrição da variação"
                                    disabled={!productSelected}
                                />
                            </FormControl>

                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="amount"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Valor *</FormLabel>
                            <FormControl>
                                <Input
                                    {...field}
                                    type="number"
                                    placeholder="Valor da variação"
                                    className="resize-none"
                                    disabled={!productSelected}
                                />
                            </FormControl>

                            <FormMessage />
                        </FormItem>
                    )}
                />
                <div className="flex justify-end items-center w-full pt-3">
                    <Button
                        className="rounded-lg"
                        disabled={!productSelected}
                        size="sm"
                    >
                        {isExecuting ? "Salvando..." : "Salvar"}
                    </Button>
                </div>
            </form>
        </Form>
    );
}
