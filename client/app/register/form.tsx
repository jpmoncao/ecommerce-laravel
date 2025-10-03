"use client";
import * as z from "zod";

import { zodResolver } from "@hookform/resolvers/zod";
import { useForm } from "react-hook-form";
import { useAction } from "next-safe-action/hooks";
import { useRouter } from "next/navigation";
import { motion } from "motion/react";
import { Check, ChevronLeft, ChevronRight, Calendar as CalendarIcon } from "lucide-react";
import { format } from "date-fns";
import { toast } from "sonner";

import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from "@/components/ui/form";
import { MultiStepFormContent, FormHeader, StepFields, FormFooter, PreviousButton, NextButton, SubmitButton } from "@/components/multi-step-viewer";
import { Input } from "@/components/ui/input";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { Calendar } from "@/components/ui/calendar";
import { Button } from "@/components/ui/button";

import { MultiStepFormProvider } from "@/hooks/use-multi-step-viewer";

import { registerSchema } from "@/schemas/register";
import { serverAction } from "@/actions/register";

import { cn } from "@/lib/utils";


type Schema = z.infer<typeof registerSchema>;

export default function RegisterForm() {
    const router = useRouter();
    
    const form = useForm<Schema>({
        resolver: zodResolver(registerSchema as any),
    });

    const formAction = useAction(
        serverAction,
        {
            onSuccess: () => {
                form.reset();

                setTimeout(() => {
                    router.push("/login");
                }, 1500);
            },
            onError: (error) => {
                toast.error('Ocorreu um erro ao tentar criar o usuário!');
                console.error(error);
            },
        }
    );

    const handleSubmit = form.handleSubmit(async (data: Schema) => {
        formAction.execute(data);
    });

    const { isExecuting, hasSucceeded } = formAction;
    const stepsFields = [
        {
            fields: ["name", "born_date", "address", "cpf_cnpj"],
            component: (
                <div className="space-y-4">
                    <h2 className="mt-4 mb-1 font-bold text-2xl tracking-tight">
                        Dados Pessoais
                    </h2>
                    <p className="tracking-wide text-muted-foreground mb-5 text-wrap text-sm">
                        Preencha seu dados pessoais abaixo
                    </p>
                    <FormField
                        control={form.control}
                        name="name"
                        rules={{ required: true }}
                        render={({ field }) => (
                            <FormItem className="w-full">
                                <FormLabel>Nome * </FormLabel>
                                <FormControl>
                                    <Input
                                        type="text"
                                        value={field.value}
                                        onChange={(e) => {
                                            const val = e.target.value;
                                            field.onChange(val);
                                        }}
                                        placeholder="Nome"
                                    />
                                </FormControl>

                                <FormMessage />
                            </FormItem>
                        )}
                    />

                    <FormField
                        control={form.control}
                        rules={{ required: true }}
                        name="born_date"
                        render={({ field }) => (
                            <FormItem className="flex flex-col">
                                <FormLabel>Data de Nascimento *</FormLabel>
                                <Popover>
                                    <PopoverTrigger asChild>
                                        <FormControl>
                                            <Button
                                                variant="outline"
                                                className={cn(
                                                    "w-[240px] pl-3 text-start font-normal",
                                                    !field.value && "text-muted-foreground",
                                                )}
                                            >
                                                {field.value ? (
                                                    format(field.value, "dd/MM/yyyy")
                                                ) : (
                                                    <span>Data de Nascimento</span>
                                                )}
                                                <CalendarIcon className="ml-auto h-4 w-4 opacity-50" />
                                            </Button>
                                        </FormControl>
                                    </PopoverTrigger>
                                    <PopoverContent className="w-auto p-0" align="start">
                                        <Calendar
                                            mode="single"
                                            captionLayout="dropdown"
                                            selected={field.value}
                                            onSelect={field.onChange}
                                        />
                                    </PopoverContent>
                                </Popover>

                                <FormMessage />
                            </FormItem>
                        )}
                    />
                    <FormField
                        control={form.control}
                        name="address"
                        rules={{ required: true }}
                        render={({ field }) => (
                            <FormItem className="w-full">
                                <FormLabel>Endereço * </FormLabel>
                                <FormControl>
                                    <Input
                                        type="text"
                                        value={field.value}
                                        onChange={(e) => {
                                            const val = e.target.value;
                                            field.onChange(val);
                                        }}
                                        placeholder="Endereço"
                                    />
                                </FormControl>

                                <FormMessage />
                            </FormItem>
                        )}
                    />
                    <FormField
                        control={form.control}
                        name="cpf_cnpj"
                        rules={{ required: true }}
                        render={({ field }) => (
                            <FormItem className="w-full">
                                <FormLabel>CPF/CNPJ * </FormLabel>
                                <FormControl>
                                    <Input
                                        type="text"
                                        value={field.value}
                                        onChange={(e) => {
                                            const val = e.target.value;
                                            field.onChange(val);
                                        }}
                                        placeholder="CPF/CNPJ"
                                    />
                                </FormControl>

                                <FormMessage />
                            </FormItem>
                        )}
                    />
                </div>
            ),
        },
        {
            fields: ["email", "password", "c-password"],
            component: (
                <div className="space-y-4">
                    <h2 className="mt-4 mb-1 font-bold text-2xl tracking-tight">
                        Dados de Login
                    </h2>
                    <p className="tracking-wide text-muted-foreground mb-5 text-wrap text-sm">
                        Preencha os dados com um email válido e uma senha forte
                    </p>
                    <FormField
                        control={form.control}
                        name="email"
                        rules={{ required: true }}
                        render={({ field }) => (
                            <FormItem className="w-full">
                                <FormLabel>Email * </FormLabel>
                                <FormControl>
                                    <Input
                                        type="email"
                                        value={field.value}
                                        onChange={(e) => {
                                            const val = e.target.value;
                                            field.onChange(val);
                                        }}
                                        placeholder="Email"
                                    />
                                </FormControl>

                                <FormMessage />
                            </FormItem>
                        )}
                    />

                    <div className="flex items-center justify-between flex-wrap sm:flex-nowrap w-full gap-2">
                        <FormField
                            control={form.control}
                            name="password"
                            rules={{ required: true }}
                            render={({ field }) => (
                                <FormItem className="w-full">
                                    <FormLabel>Senha * </FormLabel>
                                    <FormControl>
                                        <Input
                                            type="password"
                                            value={field.value}
                                            onChange={(e) => {
                                                const val = e.target.value;
                                                field.onChange(val);
                                            }}
                                            placeholder="Senha"
                                        />
                                    </FormControl>

                                    <FormMessage />
                                </FormItem>
                            )}
                        />
                        <FormField
                            control={form.control}
                            name="c-password"
                            rules={{ required: true }}
                            render={({ field }) => (
                                <FormItem className="w-full">
                                    <FormLabel>Confirme a senha * </FormLabel>
                                    <FormControl>
                                        <Input
                                            type="password"
                                            value={field.value}
                                            onChange={(e) => {
                                                const val = e.target.value;
                                                field.onChange(val);
                                            }}
                                            placeholder="Confirme a senha"
                                        />
                                    </FormControl>

                                    <FormMessage />
                                </FormItem>
                            )}
                        />
                    </div>
                </div>
            ),
        },
    ];

    if (hasSucceeded) {
        return (
            <div className="absolute top-0 left-0 w-full min-h-screen bg-background p-2 sm:p-5 md:p-8 rounded-md gap-2 border flex justify-center items-center">
                <motion.div
                    initial={{ opacity: 0, y: -16 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.4, stiffness: 300, damping: 25 }}
                    className="h-full py-6 px-3"
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
                        Obrigado!
                    </h2>
                    <p className="text-center text-lg text-pretty text-muted-foreground">
                        Seu dados foram enviados, estamos felizes de tê-lo conosco <br /> Você fará seu login em instantes...
                    </p>
                </motion.div>
            </div>
        );
    }
    return (
        <div className="w-full">
            <Form {...form}>
                <form
                    onSubmit={handleSubmit}
                    className="flex flex-col p-2 md:p-5 w-full mx-auto rounded-md gap-2"
                >
                    <MultiStepFormProvider
                        stepsFields={stepsFields}
                        onStepValidation={async (step) => {
                            const isValid = await form.trigger(step.fields);
                            return isValid;
                        }}
                    >
                        <MultiStepFormContent>
                            <FormHeader />
                            <StepFields />
                            <FormFooter>
                                <PreviousButton>
                                    <ChevronLeft />
                                    Voltar
                                </PreviousButton>
                                <NextButton>
                                    Avançar <ChevronRight />
                                </NextButton>
                                <SubmitButton type="submit" disabled={isExecuting}>
                                    {isExecuting ? "Enviando..." : "Enviar"}
                                </SubmitButton>
                            </FormFooter>
                        </MultiStepFormContent>
                    </MultiStepFormProvider>
                </form>
            </Form>
        </div>
    );
}
