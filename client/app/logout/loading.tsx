import { Loader2 } from "lucide-react";

export default function LogoutLoading() {
    return (
        <div className="w-full min-h-screen bg-background flex justify-center items-center">
            <div className="flex flex-col justify-center items-center gap-4">
                <h1 className="font-medium text-2xl">Aguarde...</h1>
                <Loader2 size={48} className="animate-spin" />
            </div>
        </div>
    )
}