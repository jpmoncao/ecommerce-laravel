"use client"

import * as React from "react"

import { Button } from "@/components/ui/button"
import {
    Drawer,
    DrawerClose,
    DrawerContent,
    DrawerDescription,
    DrawerFooter,
    DrawerHeader,
    DrawerTitle,
} from "@/components/ui/drawer"

import { useMediaQuery } from "@/hooks/use-media-query"

type Props = {
    title: string;
    description?: string;
    children?: React.ReactNode;
    open: boolean;
    setOpen?: (open: boolean) => void;
    onSubmit?: () => void;
    onClose?: () => void;
}

export function DrawerDialog(props: Props) {
    const isDesktop = useMediaQuery("(min-width: 768px)")

    return (
        <Drawer open={props.open} onOpenChange={props.setOpen} direction={isDesktop ? "right" : "bottom"}>
            <DrawerContent className="flex flex-col justify-center">
                <DrawerHeader className="text-left">
                    <DrawerTitle>{props.title}</DrawerTitle>
                    <DrawerDescription>{props.description}</DrawerDescription>
                </DrawerHeader>

                {props.children}

                <DrawerFooter className="pt-2">
                    <Button type="button" className="w-full" onClick={props.onSubmit}>Confirmar</Button>
                    <DrawerClose asChild>
                        <Button variant="outline" onClick={props.onClose}>Cancelar</Button>
                    </DrawerClose>
                </DrawerFooter>
            </DrawerContent>
        </Drawer>
    )
}
