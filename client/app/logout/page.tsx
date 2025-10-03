"use client";

import React from "react";
import { useAction } from "next-safe-action/hooks";
import { useRouter } from "next/navigation";

import LogoutLoading from "./loading";

import { logoutAction } from "@/actions/logout";

export default function LogoutPage() {
    const router = useRouter();

    const { execute } = useAction(logoutAction, {
        onSuccess: ({ data }) => {
            if (data.success) {
                if (window.history.length > 1) router.back();
                else router.push("/login");
            } else {
                console.error(data.error);
                router.push("/login");
            }
        },
        onError: (error) => {
            router.back();
            console.log(error);
        },
    });

    React.useEffect(() => {
        execute();
    }, [execute]);

    return <LogoutLoading />;
}
