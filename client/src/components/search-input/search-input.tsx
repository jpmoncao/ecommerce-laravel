import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"

import { MagnifyingGlassIcon as SearchIcon } from "@radix-ui/react-icons"

export default function SearchInput(props: React.HTMLAttributes<HTMLInputElement>) {
    return (
        <div className="flex justify-center gap-4">
            <Input type="text" placeholder="Search" {...props} />
            <Button type="submit"><SearchIcon /></Button>
        </div>
    )
}
