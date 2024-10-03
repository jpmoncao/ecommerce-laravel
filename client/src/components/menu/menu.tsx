import {
    Menubar,
    MenubarContent,
    MenubarItem,
    MenubarMenu,
    MenubarShortcut,
    MenubarTrigger,
} from "@/components/ui/menubar";
import { Separator } from "@/components/ui/separator"

export default function Menu() {
    return (
        <Menubar className="rounded-none h-12 px-10 gap-4">
            <h1 className="mr-4 font-bold text-xl">Monção's Shop</h1>

            <Separator orientation="vertical" className="h-3/4" />

            <MenubarMenu>
                <MenubarTrigger className="w-20 flex justify-center">File</MenubarTrigger>
                <MenubarContent>
                    <MenubarItem>
                        New Tab <MenubarShortcut>⌘T</MenubarShortcut>
                    </MenubarItem>
                    <MenubarItem>New Window</MenubarItem>
                </MenubarContent>
            </MenubarMenu>

            <MenubarMenu>
                <MenubarTrigger className="w-20 flex justify-center">File</MenubarTrigger>
                <MenubarContent>
                    <MenubarItem>
                        New Tab <MenubarShortcut>⌘T</MenubarShortcut>
                    </MenubarItem>
                    <MenubarItem>New Window</MenubarItem>
                </MenubarContent>
            </MenubarMenu>

            <MenubarMenu>
                <MenubarTrigger className="w-20 flex justify-center">File</MenubarTrigger>
                <MenubarContent>
                    <MenubarItem>
                        New Tab <MenubarShortcut>⌘T</MenubarShortcut>
                    </MenubarItem>
                    <MenubarItem>New Window</MenubarItem>
                </MenubarContent>
            </MenubarMenu>

        </Menubar>
    )
}