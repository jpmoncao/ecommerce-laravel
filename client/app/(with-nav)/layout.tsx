import Navbar from "@/components/ui/navbar/navbar-02";

export default function NavLayout({
    children,
}: Readonly<{
    children: React.ReactNode;
}>) {
    return (
        <>
            <Navbar />
            {children}
        </>
    );
}
