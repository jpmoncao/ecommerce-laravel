import { toast } from "sonner";
import { useForm } from "react-hook-form";
import { Check, Loader2 } from "lucide-react";
import { motion } from "motion/react";
import { useRouter } from "next/navigation";
import { useAction } from "next-safe-action/hooks";
import { zodResolver } from "@hookform/resolvers/zod";

import { Button } from "@/components/ui/button";
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";

import { serverAction } from "@/actions/login";
import { loginSchema, LoginSchema } from "@/schemas/login";

export default function LoginForm() {
    const router = useRouter();
    const form = useForm<LoginSchema>({
        resolver: zodResolver(loginSchema),
    });

    const formAction = useAction(
        serverAction,
        {
            onSuccess: () => {
                form.reset();

                setTimeout(() => {
                    router.push("/products");
                }, 1500);
            },
            onError: (error: any) => {
                if (error?.error.serverError.name === "UserUnauthorizedError") {
                    toast.error("Credenciais inválidas!");
                    return;
                }

                toast.error("Ocorreu um erro ao efetuar login.");
                console.error(error?.error.serverError);
            },
        },
    );

    const handleSubmit = form.handleSubmit(async (data: LoginSchema) => {
        formAction.execute(data);
    });

    const { isExecuting, hasSucceeded } = formAction;
    if (hasSucceeded) {
        return (
            <div className="absolute top-0 left-0 w-full min-h-screen bg-background p-2 sm:p-5 md:p-8 rounded-md gap-2 border flex justify-center items-center">
                <motion.div
                    initial={{ opacity: 0, y: -16 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.4, stiffness: 300, damping: 25 }}
                    className="h-screen py-6 px-3"
                >
                    <motion.div
                        initial={{ scale: 0.5 }}
                        animate={{ scale: 1 }}
                        transition={{
                            delay: 0.3,
                            type: "spring",
                            stiffness: 500,
                            damping: 15,
                        }}
                        className="mb-4 flex justify-center border rounded-full w-fit mx-auto p-2"
                    >
                        <Check className="size-8" />
                    </motion.div>
                    <h2 className="text-center text-2xl text-pretty font-bold mb-2">
                        Login efetuado com sucesso!
                    </h2>
                    <p className="text-center text-lg text-pretty text-muted-foreground">
                        Aguarde enquanto redirecionamos você...
                    </p>
                </motion.div>
            </div>
        );
    }

    return (
        <Form {...form}>
            <form
                className="w-full space-y-4 mt-8"
                onSubmit={handleSubmit}
            >
                <FormField
                    control={form.control}
                    name="email"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Email</FormLabel>
                            <FormControl>
                                <Input
                                    type="email"
                                    placeholder="Email"
                                    className="w-full"
                                    {...field}
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />
                <FormField
                    control={form.control}
                    name="password"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Senha</FormLabel>
                            <FormControl>
                                <Input
                                    type="password"
                                    placeholder="Senha"
                                    className="w-full"
                                    {...field}
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    )}
                />
                <Button type="submit" className="mt-4 w-full">
                    Continuar {isExecuting && <Loader2 className="ml-2 h-4 w-4 animate-spin" />}
                </Button>
            </form>
        </Form>
    )
}