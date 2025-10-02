'use client';

import { MagicBackButton } from "@/components/ui/magic-back-button";

export default function AdminLayout({ children }: { children: React.ReactNode }) {
    return (
        <div className="flex min-h-screen flex-col p-6 max-w-[800px] mx-auto">
            <MagicBackButton className="mb-6"/>
            {children}
        </div>
    )
}