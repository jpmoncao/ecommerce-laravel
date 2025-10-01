"use client";

import * as React from "react";
import { ArrowLeft, ArrowRight, ArrowUpDown, CheckIcon, MoreHorizontal } from "lucide-react";
import { ColumnDef, ColumnFiltersState, SortingState, VisibilityState, PaginationState, flexRender, getCoreRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, useReactTable, RowSelectionState } from "@tanstack/react-table";

import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { Input } from "@/components/ui/input";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, } from "@/components/ui/table";

import { IProductVariationWithStock } from "@/interfaces/product-variations";
import { IProductStock } from "@/interfaces/product-stocks";


type Props = {
    data: IProductVariationWithStock[],
    onRowClick?: (variation: IProductVariationWithStock) => void,
    onActionClick?: (productId: number, variations: IProductVariationWithStock[]) => void;
};

export const columns: ColumnDef<IProductVariationWithStock>[] = [
    {
        id: "select",
        header: ({ table }) => (
            <Checkbox
                checked={
                    table.getIsAllPageRowsSelected() ||
                    (table.getIsSomePageRowsSelected() && "indeterminate")
                }
                onCheckedChange={(value) => table.toggleAllPageRowsSelected(!!value)}
                aria-label="Select all"
            />
        ),
        cell: ({ row }) => (
            <Checkbox
                checked={row.getIsSelected()}
                onCheckedChange={(value) => row.toggleSelected(!!value)}
                aria-label="Select row"
            />
        ),
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: "variation",
        header: ({ column }) => {
            return (
                <Button
                    type="button"
                    variant="ghost"
                    onClick={() => column.toggleSorting(column.getIsSorted() === "asc")}
                >
                    Variação <ArrowUpDown />
                </Button>
            );
        },
        cell: ({ row }) => <div>{row.getValue("variation")}</div>,
    },
    {
        accessorKey: "amount",
        header: ({ column }) => {
            return (
                <Button
                    type="button"
                    variant="ghost"
                    onClick={() => column.toggleSorting(column.getIsSorted() === "asc")}
                >
                    Preço (R$) <ArrowUpDown />
                </Button>
            );
        },
        cell: ({ row }) => <div>{(row.getValue("amount") as number)?.toLocaleString("pt-br", { minimumFractionDigits: 2 })}</div>
    },
    {
        accessorKey: "stock",
        header: ({ column }) => {
            return (
                <Button
                    type="button"
                    variant="ghost"
                    onClick={() => column.toggleSorting(column.getIsSorted() === "asc")}
                >
                    Qtde Estoque <ArrowUpDown />
                </Button>
            );
        },
        cell: ({ row }) => <div>{(row.getValue("stock") as IProductStock)?.quantity}</div>
    },
];

export default function TableVariations({ data, onRowClick, onActionClick }: Props) {
    const [sorting, setSorting] = React.useState<SortingState>([]);
    const [columnFilters, setColumnFilters] = React.useState<ColumnFiltersState>([]);
    const [columnVisibility, setColumnVisibility] = React.useState<VisibilityState>({});
    const [pagination, setPagination] = React.useState<PaginationState>({ pageIndex: 0, pageSize: 5 });
    const [rowSelection, setRowSelection] = React.useState<RowSelectionState>({});
    const [tableExpanded, setTableExpanded] = React.useState(false);

    const table = useReactTable({
        data,
        columns,
        onSortingChange: setSorting,
        onColumnFiltersChange: setColumnFilters,
        getCoreRowModel: getCoreRowModel(),
        getPaginationRowModel: getPaginationRowModel(),
        getSortedRowModel: getSortedRowModel(),
        getFilteredRowModel: getFilteredRowModel(),
        onColumnVisibilityChange: setColumnVisibility,
        onRowSelectionChange: setRowSelection,
        onPaginationChange: setPagination,
        state: {
            sorting,
            columnFilters,
            columnVisibility,
            rowSelection,
            pagination
        },
    });

    React.useEffect(() => {
        if (!onActionClick) return;
        const selectedRows = table.getFilteredSelectedRowModel().rows.map(r => r.original);
        onActionClick(data[0].product_id, selectedRows);
    }, [rowSelection]);


    function handleToggleTableExpand() {
        setTableExpanded(prev => {
            const expanded = !prev;
            table.setPageSize(expanded ? data.length : 5);
            return expanded;
        });
    }


    return (
        <div className="w-full overflow-hidden">
            <div className="flex items-center gap-2 py-4">
                <Input
                    placeholder="Filtrar produtos..."
                    value={(table.getColumn("variation")?.getFilterValue() as string) ?? ""}
                    onChange={(event) => table.getColumn("variation")?.setFilterValue(event.target.value)}
                    className="max-w-sm"
                />
            </div>
            <div className="rounded-md border">
                <Table className="w-full">
                    <TableHeader>
                        {table.getHeaderGroups().map((headerGroup) => (
                            <TableRow key={headerGroup.id}>
                                {headerGroup.headers.map((header) => {
                                    return (
                                        <TableHead key={header.id}>
                                            {header.isPlaceholder
                                                ? null
                                                : flexRender(
                                                    header.column.columnDef.header,
                                                    header.getContext()
                                                )}
                                        </TableHead>
                                    );
                                })}
                            </TableRow>
                        ))}
                    </TableHeader>
                    <TableBody>
                        {table.getRowModel().rows.length
                            ? (table.getRowModel().rows.map((row) => (
                                <TableRow
                                    key={row.id}
                                    data-state={row.getIsSelected() && "selected"}
                                    className="cursor-pointer"
                                    onClick={() => onRowClick ? onRowClick(row.original) : row.toggleSelected()}
                                >
                                    {row.getVisibleCells().map((cell) => (
                                        <TableCell key={cell.id}>
                                            {flexRender(cell.column.columnDef.cell, cell.getContext())}
                                        </TableCell>
                                    ))}
                                </TableRow>
                            ))
                            ) : (
                                <TableRow>
                                    <TableCell colSpan={columns.length} className="h-24 text-center">
                                        Nenhum produto encontrado.
                                    </TableCell>
                                </TableRow>
                            )}
                    </TableBody>
                </Table>
            </div>
            <div className="flex items-center justify-end space-x-2 py-4">
                <div className="flex-1 text-sm text-muted-foreground">
                    {table.getFilteredSelectedRowModel().rows.length} de{" "}
                    {table.getFilteredRowModel().rows.length} linha(s) selecionadas.
                </div>
                <div className="space-x-2 flex items-center">
                    <Button
                        onClick={handleToggleTableExpand}
                        size="sm"
                        variant="outline"
                        disabled={data.length <= 5}
                    >
                        <MoreHorizontal /> {tableExpanded ? "Ver menos" : "Ver todas"}
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        onClick={() => table.previousPage()}
                        disabled={!table.getCanPreviousPage() || tableExpanded}
                    >
                        <ArrowLeft /> Anterior
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        onClick={() => table.nextPage()}
                        disabled={!table.getCanNextPage() || tableExpanded}
                    >
                        <ArrowRight /> Próximo
                    </Button>
                </div>
            </div>
        </div>
    );
}
