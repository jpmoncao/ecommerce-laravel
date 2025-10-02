'use server';

import { IProductStockEntry } from '@/interfaces/product-stock-entry';

export async function postStockEntries(payload: IProductStockEntry[]) {
    const res = await fetch(`${process.env.API_URL}/api/entries/stock/batch`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ variations: payload }),
    });

    if (!res.ok) throw new Error('Erro ao lançar estoque');

    return res.json();
}
