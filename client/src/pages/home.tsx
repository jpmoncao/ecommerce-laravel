import api from "@/services/api";
import ProductInterface from "@/interfaces/product";

import { Suspense, useEffect, useState } from "react";

import CardItem from "@/components/card-item/card-item";
import SearchInput from "@/components/search-input/search-input";
import { Separator } from "@/components/ui/separator";

export default function Home() {
    const [products, setProducts] = useState<ProductInterface[]>([]);
    const [searchTerm, setSearchTerm] = useState('');

    useEffect(() => {
        const fetchProducts = async () => {
            try {
                let response = await api.get('/products');
                response = JSON.parse(response.data);
                console.log(response);

                if (Array.isArray(response.data))
                    setProducts(response.data);
                else
                    console.error("Unexpected API response format:", response.data);
            } catch (error) {
                console.error("Erro ao carregar os produtos", error);
            }
        };

        fetchProducts();
    }, []);

    return (
        <>
            <main className="w-full flex flex-col justify-center align-center mt-8">
                <section className="mx-auto flex flex-col justify-center align-center gap-8 w-1/2 max-md:w-3/4">
                    <article className="mx-auto flex flex-col justify-center align-center gap-1">
                        <h1 className="text-3xl text-center font-bold">Encontre um produto</h1>
                        <p className="text-s text-center text-zinc-400 font-light w-3/4 mx-auto">
                            Digite no campo abaixo a descrição do produto e clique no botão com ícone de lupa!
                        </p>
                    </article>

                    <SearchInput
                        onChange={(e) => setSearchTerm((e.target as HTMLInputElement).value)}
                    />
                </section>

                <Separator className="w-5/6 mx-auto my-8" />

                <section className="mx-auto max-w-[95vw] sm:max-w-[90vw] grid md:grid-cols-4 sm:grid-cols-3 grid-cols-2 justify-center align-center gap-2 lg:gap-8 px-4">
                    <Suspense fallback={<div>Erro ao buscar!</div>}>
                        {products.length > 0 ? (
                            products
                                .filter(product =>
                                    searchTerm.trim() === '' || product.name.toLowerCase().includes(searchTerm.toLowerCase())
                                )
                                .map(product => (
                                    <CardItem
                                        key={product.id_product}
                                        className="px-1"
                                        product={product}
                                        link={`/product/${product.id_product}`}
                                    />
                                ))
                        ) : (
                            <p>Nenhum produto encontrado.</p>
                        )}

                    </Suspense>
                </section>
            </main>
        </>
    );
}
