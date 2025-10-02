import "./globals.css";

import type { Metadata } from "next";
import { Commissioner, Geist_Mono } from "next/font/google";
import { PageTracker } from "react-page-tracker";

import { Toaster } from "@/components/ui/sonner"

const commissioner = Commissioner({
  variable: "--font-commissioner",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "Monção's Shop",
  description: "Um e-commerce completo feito com Laravel e Next.js",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
      <body
        className={`${commissioner.variable} ${geistMono.variable} antialiased font-sans`}
      >
        <PageTracker />
        <Toaster position="top-center" richColors closeButton />

        {children}
      </body>
    </html>
  );
}
