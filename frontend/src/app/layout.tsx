import { ReduxProvider } from "@/providers/ReduxProvider";
import "./globals.css";
import type { Metadata } from "next";
import { Inter } from "next/font/google";
import AuthProvider from "@/providers/AuthProvider";

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: "CW Test Case",
  description: "Test case for CW",
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en" className="dark:bg-gray-900">
      <body className={inter.className}>
        <ReduxProvider>
          <AuthProvider>{children}</AuthProvider>
        </ReduxProvider>
      </body>
    </html>
  );
}
