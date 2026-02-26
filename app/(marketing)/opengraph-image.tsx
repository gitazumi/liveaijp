import { ImageResponse } from "next/og";

export const runtime = "edge";
export const alt = "LiveAI - 中小企業向けAIチャットボット";
export const size = { width: 1200, height: 630 };
export const contentType = "image/png";

export default async function OGImage() {
  return new ImageResponse(
    (
      <div
        style={{
          display: "flex",
          flexDirection: "column",
          alignItems: "center",
          justifyContent: "center",
          width: "100%",
          height: "100%",
          background: "linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%)",
          fontFamily: "sans-serif",
        }}
      >
        {/* ロゴ風テキスト */}
        <div
          style={{
            display: "flex",
            alignItems: "center",
            gap: "16px",
            marginBottom: "32px",
          }}
        >
          <div
            style={{
              width: "64px",
              height: "64px",
              borderRadius: "16px",
              background: "linear-gradient(135deg, #3b82f6, #2563eb)",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              color: "white",
              fontSize: "32px",
              fontWeight: 700,
            }}
          >
            AI
          </div>
          <span
            style={{
              fontSize: "56px",
              fontWeight: 700,
              color: "#1e293b",
            }}
          >
            LiveAI
          </span>
        </div>

        {/* タイトル */}
        <div
          style={{
            fontSize: "40px",
            fontWeight: 700,
            color: "#0f172a",
            textAlign: "center",
            lineHeight: 1.3,
            maxWidth: "800px",
            marginBottom: "20px",
          }}
        >
          FAQ登録だけでAIチャットボットを作成
        </div>

        {/* サブタイトル */}
        <div
          style={{
            fontSize: "22px",
            color: "#475569",
            textAlign: "center",
            maxWidth: "700px",
            lineHeight: 1.5,
          }}
        >
          24時間自動対応 ・ 初期費用0円 ・ プログラミング不要
        </div>

        {/* フッター */}
        <div
          style={{
            position: "absolute",
            bottom: "32px",
            fontSize: "18px",
            color: "#94a3b8",
          }}
        >
          liveai.jp
        </div>
      </div>
    ),
    {
      ...size,
    }
  );
}
