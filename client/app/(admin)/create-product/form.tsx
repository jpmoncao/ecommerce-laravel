"use client";
import { useState } from "react";
import * as z from "zod";
import { toast } from "sonner";
import Link from "next/link";
import { zodResolver } from "@hookform/resolvers/zod";
import { useForm } from "react-hook-form";
import { useAction } from "next-safe-action/hooks";
import { motion } from "motion/react";
import { Check, PlusCircle } from "lucide-react";

import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from "@/components/ui/form";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";

import { productSchema, ProductSchema } from "@/schemas/create-product";
import { serverAction } from "@/actions/create-product";

export function CreateProductForm() {
    const [idProduct, setIdProduct] = useState<number | null>(null);

    const form = useForm<ProductSchema>({
        resolver: zodResolver(productSchema),
    });

    const formAction = useAction(
        serverAction,
        {
            onSuccess: (data) => {
                setIdProduct(data.data.id_product);
                form.reset();
            },
            onError: (error) => {
                toast.error("Ocorreu um erro ao criar o produto.");
                console.error(error);
            },
        },
    );


    const handleSubmit = form.handleSubmit(async (data: ProductSchema) => {
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
                    <p className="text-center text-lg text-pretty text-muted-foreground">
                        Clique aqui para cadastrar as variações do produto
                    </p>
                    {idProduct && (
                        <Link href={`/create-variation?product_id=${idProduct}`} className="flex justify-center mt-6">
                            <Button>
                                <PlusCircle /> Cadastrar Variações
                            </Button>
                        </Link>
                    )}
                </motion.div>
            </div>
        );
    }
    return (
        <Form {...form}>
            <form
                onSubmit={handleSubmit}
                className="flex flex-col p-2 sm:p-5 md:p-8 w-full rounded-md gap-2 border"
            >
                <FormField
                    control={form.control}
                    name="name"
                    render={({ field }) => (
                        <FormItem className="w-full">
                            <FormLabel>Nome * </FormLabel>
                            <FormControl>
                                <Input
                                    {...field}
                                    required
                                    placeholder="Nome do produto"
                                />
                            </FormControl>

                            <FormMessage />
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="description"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Descrição </FormLabel>
                            <FormControl>
                                <Textarea
                                    {...field}
                                    placeholder="Descrição do produto"
                                    className="resize-none"
                                />
                            </FormControl>

                            <FormMessage />
                        </FormItem>
                    )}
                />
                <div className="flex justify-end items-center w-full pt-3">
                    <Button className="rounded-lg" size="sm">
                        {isExecuting ? "Salvando..." : "Salvar"}
                    </Button>
                </div>
            </form>
        </Form>
    );
}
