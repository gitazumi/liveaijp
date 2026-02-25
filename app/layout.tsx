import type { Metadata } from "next";
import { Inter } from "next/font/google";
import { Toaster } from "@/components/ui/sonner";
import "./globals.css";

const inter = Inter({
  variable: "--font-sans",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "LiveAI - 中小企業向けAIチャットボット｜FAQ登録だけで簡単導入",
  description:
    "中小企業・店舗向けのAIチャットボット作成サービス。FAQを登録するだけでChatGPTが24時間自動で顧客対応。プログラミング不要・初期費用0円で今すぐ導入できます。",
  keywords:
    "AIチャットボット,中小企業,顧客対応自動化,FAQ,ChatGPT,カスタマーサポート,無料,ノーコード",
  icons: {
    icon: "/icon.svg",
    apple: "/apple-icon.svg",
  },
  openGraph: {
    title: "LiveAI - 中小企業向けAIチャットボット",
    description:
      "FAQ登録だけでAIチャットボットを作成。24時間自動対応で問い合わせ業務を効率化。",
    type: "website",
    locale: "ja_JP",
    siteName: "LiveAI",
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="ja">
      <body className={`${inter.variable} font-sans antialiased`}>
        {children}
        <Toaster />
      </body>
    </html>
  );
}
