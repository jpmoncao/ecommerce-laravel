import { Skeleton } from "@/components/ui/skeleton"

export default function Loading() {
    return (
        <div className="flex flex-col pl-6 pr-4 py-6 mx-auto w-full max-w-[800px] space-y-8">
            <Skeleton className="h-8 w-60" />
            <Skeleton className="h-4 w-96" />

            <section className="flex justify-between gap-8 w-full">
                <Skeleton className="w-2/3 h-[400px]" />

                <div className="flex-1/3 flex flex-col gap-6">
                    <div className="space-y-2">
                        <Skeleton className="h-6 w-40" />
                        <Skeleton className="h-10 w-[180px]" />
                    </div>
                    <div className="space-y-2">
                        <Skeleton className="h-6 w-20" />
                        <Skeleton className="h-10 w-32" />
                    </div>
                    <div className="space-y-2">
                        <Skeleton className="h-6 w-32" />
                        <Skeleton className="h-10 w-20" />
                    </div>
                </div>
            </section>
        </div>
    )
}
