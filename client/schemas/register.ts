import * as z from "zod";

export interface RegisterResponse<T = any> {
    success: boolean;
    message: string;
    errors?: {
        [K in keyof T]?: string[];
    };
    inputs?: T;
}

export const registerSchema = z.object({
    name: z.string({ error: "Esse campo é obrigatório" }),
    born_date: z.date({ error: "Esse campo é obrigatório" }),
    address: z.string({ error: "Esse campo é obrigatório" }),
    cpf_cnpj: z.string({ error: "Esse campo é obrigatório" }),
    email: z.email({ error: "Preencha com um email válido!" }),
    password: z.string({ error: "Esse campo é obrigatório" }),
    "c-password": z.string({ error: "Esse campo é obrigatório" }),
});
