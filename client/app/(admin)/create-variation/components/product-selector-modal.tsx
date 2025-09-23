import * as React from "react";
import { IProduct } from "@/interfaces/products";
import ProductsTable from "./products-table";
import { Button } from "@/components/ui/button";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger, } from "@/components/ui/dialog";

export default function ProductSelectorModal({ products, onSelect }: { products: IProduct[], onSelect: (id: number) => void }) {
    const [open, setOpen] = React.useState(false);

    const handleSelect = (id: number) => {
        onSelect(id);
        setOpen(false);
    };

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button>Selecionar Produto</Button>
            </DialogTrigger>
            <DialogContent className="w-[600px] max-w-full">
                <DialogHeader>
                    <DialogTitle>Escolha um Produto</DialogTitle>
                </DialogHeader>

                <ProductsTable
                    data={products}
                    onRowClick={(product) => handleSelect(product.id_product)}
                    onActionClick={(product) => handleSelect(product.id_product)}
                />
            </DialogContent>
        </Dialog>
    );
}
