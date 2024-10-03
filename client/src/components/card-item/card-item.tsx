import { Link } from "react-router-dom";
import { PlusCircledIcon as PlusIcon } from "@radix-ui/react-icons"

import ProductInterface from "@/interfaces/product";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from "@/components/ui/tooltip";



interface PropsInterface extends React.HTMLAttributes<HTMLDivElement> {
    product: ProductInterface;
    link: string;
}


export default function CardItem({ product, link, ...rest }: PropsInterface) {
    return (
        <Card className={rest.className + ' flex flex-col justify-between min-h-fit gap-4'} >
            <CardHeader className="p-1 w-full">
                <TooltipProvider>
                    <Tooltip>
                        <TooltipTrigger asChild>
                            <CardTitle className="max-sm: text-lg truncate">{product.name}</CardTitle>
                        </TooltipTrigger>
                        <TooltipContent className="max-w-100 mx-auto">
                            <p className="break-words">{product.name}</p>
                        </TooltipContent>
                    </Tooltip>
                </TooltipProvider>

                <CardDescription>
                    {/*In stock: {
                        (product.rating.count).toLocaleString('pt-BR', {
                            maximumFractionDigits: 2,
                            signDisplay: "auto",

                        })
                    }*/}
                </CardDescription>
            </CardHeader>
            <CardContent className="py-1 px-6 max-h-1/3">
                <img
                    src={product.images ? product.images[0] : 'placeholder.jpg'}
                    alt={`Photo ${product.name}`}
                    className="ratio-16x9 h-full w-full rounded-md object-cover"
                />
            </CardContent>
            <CardFooter className="flex justify-center gap-4 items-center">
                <Link to={link}>
                    <Button className="flex items-center gap-2">
                        <PlusIcon /> Ver mais
                    </Button>
                </Link>
            </CardFooter>
        </Card >
    )
}
