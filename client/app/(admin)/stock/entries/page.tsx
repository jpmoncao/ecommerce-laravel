'use client'

import { useSearchParams } from "next/navigation"

export default function StockEntriesPage() {
    const searchParams = useSearchParams();
    const variations = searchParams.get("variation[]")?.split(",") || [];

    return (
        <div className="p-4">
            <h1 className="text-2xl font-bold mb-4">Lançamento de Estoque</h1>
            {variations && variations.length === 0
                ? <p>Nenhuma variação selecionada para lançamento de estoque.</p>
                : (<div>
                    <h2 className="text-xl font-semibold mb-2">Variações Selecionadas:</h2>
                    <ul className="list-disc list-inside">
                        {variations.map((variationId, index) => (
                            <li key={index}>Variação ID: {variationId}</li>
                        ))}
                    </ul>
                </div>)
            }
        </div >
    )
}