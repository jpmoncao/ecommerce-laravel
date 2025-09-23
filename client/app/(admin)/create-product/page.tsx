import { CreateProductForm } from "./form";

export default async function ProductsPage() {
    return (
        <div className="flex flex-col pl-6 pr-4 py-6 mx-auto w-full max-w-[800px]">
            <CreateProductForm />
        </div>
    )
}
