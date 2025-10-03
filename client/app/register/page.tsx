"use client";

import { Logo } from "@/components/logo";
import Link from "next/link";
import RegisterForm from "./form";

const RegisterPage = () => {

  return (
    <div className="min-h-screen flex items-center justify-center">
      <div className="max-w-3xl w-full flex flex-col items-center">
        <Logo />
        <p className="mt-4 text-xl font-semibold tracking-tight">Faça sua conta</p>

        <RegisterForm />

        <div className="mt-5 space-y-5">
          <p className="text-sm text-center">
            Já possui uma conta?
            <Link href="/login" className="ml-1 underline text-muted-foreground">
              Entre agora!
            </Link>
          </p>
        </div>
      </div>
    </div>
  );
};

export default RegisterPage;
