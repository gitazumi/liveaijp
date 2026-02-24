import { Header } from "@/components/landing/header";
import { Footer } from "@/components/landing/footer";
import { Card, CardContent } from "@/components/ui/card";

export default function CompanyPage() {
  return (
    <>
      <Header />
      <main className="mx-auto max-w-3xl px-4 py-16">
        <h1 className="text-3xl font-bold">会社概要</h1>
        <Card className="mt-8">
          <CardContent className="pt-6">
            <table className="w-full text-sm">
              <tbody className="divide-y">
                {[
                  ["会社名", "Sound Graffiti 株式会社"],
                  ["所在地", "〒160-0004 東京都新宿区四谷3-4-3 SCビル B1"],
                  ["電話番号", "03-5315-4781"],
                  ["設立", "2013年"],
                  ["代表取締役", "澤 亜澄"],
                  ["取締役", "田中 茂之"],
                  [
                    "事業内容",
                    "AIチャットボットサービス開発・運営、ライブハウス運営、レコーディングスタジオ運営",
                  ],
                ].map(([label, value]) => (
                  <tr key={label}>
                    <th className="w-1/3 py-4 text-left font-medium text-muted-foreground">
                      {label}
                    </th>
                    <td className="py-4">{value}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </CardContent>
        </Card>
      </main>
      <Footer />
    </>
  );
}
