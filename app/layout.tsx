import type { Metadata } from "next";
import { Inter } from "next/font/google";
import { Toaster } from "@/components/ui/sonner";
import { Analytics } from "@vercel/analytics/next";
import "./globals.css";

const inter = Inter({
  variable: "--font-sans",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "LiveAI - 中小企業向けAIチャットボット｜FAQ登録だけで簡単導入",
  description:
    "中小企業・店舗向けのAIチャットボット作成サービス。FAQを登録するだけでAIが24時間自動で顧客対応。分析レポートで改善も簡単。プログラミング不要・初期費用0円で今すぐ導入できます。",
  keywords:
    "AIチャットボット,中小企業,顧客対応自動化,FAQ,ChatGPT,カスタマーサポート,無料,ノーコード,分析レポート",
  icons: {
    icon: "/icon.svg",
    apple: "/apple-icon.svg",
  },
  metadataBase: new URL("https://liveai.jp"),
  openGraph: {
    title: "LiveAI - FAQ登録だけでAIチャットボットを作成",
    description:
      "中小企業・店舗向けAIチャットボット。24時間自動対応・分析レポート・多言語対応。初期費用0円で今すぐ導入。",
    type: "website",
    locale: "ja_JP",
    siteName: "LiveAI",
    url: "https://liveai.jp",
  },
  twitter: {
    card: "summary_large_image",
    title: "LiveAI - FAQ登録だけでAIチャットボットを作成",
    description:
      "中小企業・店舗向けAIチャットボット。24時間自動対応・分析レポート・多言語対応。初期費用0円で今すぐ導入。",
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
        <Analytics />
      </body>
    </html>
  );
}
