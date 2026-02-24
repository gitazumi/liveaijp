import Link from "next/link";
import { MessageSquare } from "lucide-react";

export function Footer() {
  return (
    <footer className="border-t bg-muted/30 py-12">
      <div className="mx-auto max-w-6xl px-4">
        <div className="grid gap-8 md:grid-cols-4">
          <div className="md:col-span-2">
            <Link href="/" className="flex items-center gap-2 font-bold text-lg">
              <MessageSquare className="h-5 w-5 text-primary" />
              LiveAI
            </Link>
            <p className="mt-3 max-w-sm text-sm text-muted-foreground">
              中小企業・店舗向けのAIチャットボット作成サービス。
              FAQ登録だけで導入でき、24時間自動でお客様対応を行います。
            </p>
          </div>
          <div>
            <h4 className="mb-3 font-semibold">サービス</h4>
            <ul className="space-y-2 text-sm text-muted-foreground">
              <li>
                <a href="#features" className="transition-colors hover:text-foreground">
                  サービス概要
                </a>
              </li>
              <li>
                <a href="#pricing" className="transition-colors hover:text-foreground">
                  料金プラン
                </a>
              </li>
              <li>
                <a href="#faq" className="transition-colors hover:text-foreground">
                  よくある質問
                </a>
              </li>
            </ul>
          </div>
          <div>
            <h4 className="mb-3 font-semibold">その他</h4>
            <ul className="space-y-2 text-sm text-muted-foreground">
              <li>
                <Link href="/company" className="transition-colors hover:text-foreground">
                  会社概要
                </Link>
              </li>
              <li>
                <Link href="/policy" className="transition-colors hover:text-foreground">
                  利用規約
                </Link>
              </li>
              <li>
                <Link
                  href="/privacy-policy"
                  className="transition-colors hover:text-foreground"
                >
                  プライバシーポリシー
                </Link>
              </li>
              <li>
                <Link href="/contact" className="transition-colors hover:text-foreground">
                  お問い合わせ
                </Link>
              </li>
            </ul>
          </div>
        </div>
        <div className="mt-10 border-t pt-6 text-center text-xs text-muted-foreground">
          © {new Date().getFullYear()} Sound Graffiti 株式会社. All rights reserved.
        </div>
      </div>
    </footer>
  );
}
