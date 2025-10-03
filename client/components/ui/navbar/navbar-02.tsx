'use client'

import Link from "next/link";

import { Button } from "@/components/ui/button";
import { Logo } from "@/components/logo";
import { NavMenu } from "@/components/ui/navbar/nav-menu";
import { NavigationSheet } from "@/components/ui/navbar/navigation-sheet";

import { useUserToken } from "@/hooks/use-user-token";
import { Loader2 } from "lucide-react";

const Navbar = () => {
  const { loading, isValid } = useUserToken();

  return (
    <nav className="h-16 bg-background border-b">
      <div className="h-full flex items-center justify-between max-w-(--breakpoint-xl) mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center gap-8">
          <Logo />

          {/* Desktop Menu */}
          <NavMenu className="hidden md:block" />
        </div>

        <div className="flex items-center gap-3">
          {!loading
            ?
            !isValid ? (
              <>
                <Link href="/login" className="text-sm font-medium">
                  <Button variant="outline" className="hidden sm:inline-flex">Login</Button>
                </Link>
                <Link href="/register" className="text-sm font-medium">
                  <Button>Faça uma conta</Button>
                </Link>
              </>
            ) : (
              <Link href="/logout" className="text-sm font-medium">
                <Button variant="outline" className="hidden sm:inline-flex">Sair</Button>
              </Link>
            )
            : <Loader2 className="animate-spin text-foreground/30" />}

          {/* Mobile Menu */}
          <div className="md:hidden">
            <NavigationSheet />
          </div>
        </div>
      </div>
    </nav >
  );
};

export default Navbar;
