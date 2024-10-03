import api from "@/services/api";
import ProductInterface from "@/interfaces/product";

import { useState, useEffect } from "react";
import { useParams } from "react-router";

import { Link } from "react-router-dom";
import { ArrowLeftIcon } from "@radix-ui/react-icons";
import toast, { Toaster } from 'react-hot-toast';

import { Separator } from "@/components/ui/separator";
import { Button } from "@/components/ui/button";
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
} from "@/components/ui/carousel";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";


export default function Product() {
    const { product_id } = useParams();
    const [product, setProduct] = useState<ProductInterface | undefined>();

    useEffect(() => {
        const fetchProducts = async () => {
            try {
                let response = await api.get(`/products/${product_id}/variations`);
                response = JSON.parse(response.data);
                console.log(response);

                if (response.data)
                    setProduct(response.data);
                else
                    console.error("Unexpected API response format:", response.data);
            } catch (error) {
                console.error("Erro ao carregar os produtos", error);
            }
        };

        fetchProducts();
    }, []);

    const promise = async () => '';

    const addToCart = () => {
        toast.promise(
            promise(),
            {
                loading: 'Adding...',
                success: <p>Product <strong>added</strong> to your cart!</p>,
                error: <p>Could not save product in cart.</p>,
            }
        );
    }

    return (
        <main className="w-5/6 mx-auto pb-24">
            <header className="flex justify-between mt-8 gap-4">
                <article className="flex justify-center flex-col">
                    <h2>#{product?.id_product}</h2>
                    <h1 className="text-xl font-bold">{product?.name}</h1>
                </article>
                <Link to="/" className="flex items-center">
                    <Button className="flex items-center gap-2">
                        <ArrowLeftIcon /><p className="max-md:hidden">Voltar para home</p>
                    </Button>
                </Link>
            </header>

            <Separator className="my-8" />

            <section className="mx-auto w-5/6 flex flex-col lg:flex-row items-center gap-24">
                <Carousel className="mx-auto max-w-xs w-full">
                    <CarouselContent>
                        {
                            product?.images && product?.images.length > 0
                                ? product?.images.map((image, index) => ((
                                    <CarouselItem key={index}>
                                        <div className="p-1">
                                            <img src={image} alt={`Photo of ${product?.name}`} />
                                        </div>
                                    </CarouselItem>
                                )))
                                : (
                                    <CarouselItem key={1}>
                                        <div className="p-1">
                                            <img src='/placeholder.jpg' alt={`Photo of ${product?.name}`} />
                                        </div>
                                    </CarouselItem>
                                )
                        }
                    </CarouselContent>
                    <CarouselPrevious />
                    <CarouselNext />
                </Carousel>
                <article className="flex flex-col justify-center gap-8">
                    <p>{product?.description}</p>
                    <article className="flex items-center gap-8">
                        <Select>
                            <SelectTrigger className="w-[180px]">
                                <SelectValue placeholder="Product Variable..." />
                            </SelectTrigger>
                            <SelectContent>
                                {
                                    product?.variants ? (
                                        product.variants.map((variant) =>
                                            <SelectItem
                                                value={variant.id_product_variation}
                                                key={variant.id_product_variation}
                                            >{variant.variation}</SelectItem>)
                                    ) : ''
                                }
                            </SelectContent>
                        </Select>

                        {/* <h2>In stock: {product?.rating.count}</h2> */}
                    </article>
                    <Button onClick={addToCart} className="flex items-center gap-2"><img src="/shopping-cart.svg" /> Add to cart</Button>
                </article>
            </section>

            <Toaster
                position="top-center"
                reverseOrder={true}
            />
        </main >
    )
}