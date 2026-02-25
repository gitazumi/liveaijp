import { createClient } from "@/lib/supabase/server";
import { NextRequest, NextResponse } from "next/server";
import { canUsePlanFeature, getPlanLimits, type PlanType } from "@/lib/stripe";

function parseCSV(text: string): { question: string; answer: string }[] {
  const lines = text.split(/\r?\n/).filter((line) => line.trim());
  const results: { question: string; answer: string }[] = [];

  for (let i = 0; i < lines.length; i++) {
    const line = lines[i];
    // ヘッダー行をスキップ
    if (i === 0 && line.toLowerCase().includes("question")) continue;

    // 簡易CSVパーサ（ダブルクォート対応）
    const values: string[] = [];
    let current = "";
    let inQuotes = false;

    for (let j = 0; j < line.length; j++) {
      const char = line[j];
      if (char === '"') {
        if (inQuotes && line[j + 1] === '"') {
          current += '"';
          j++;
        } else {
          inQuotes = !inQuotes;
        }
      } else if (char === "," && !inQuotes) {
        values.push(current.trim());
        current = "";
      } else {
        current += char;
      }
    }
    values.push(current.trim());

    if (values.length >= 2 && values[0] && values[1]) {
      results.push({ question: values[0], answer: values[1] });
    }
  }

  return results;
}

export async function POST(req: NextRequest) {
  const supabase = await createClient();
  const {
    data: { user },
  } = await supabase.auth.getUser();
  if (!user) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  // プラン確認
  const { data: subscription } = await supabase
    .from("subscriptions")
    .select("plan")
    .eq("user_id", user.id)
    .single();

  const plan = (subscription?.plan as PlanType) ?? "free";
  if (!canUsePlanFeature(plan, "csvExport")) {
    return NextResponse.json(
      { error: "この機能はスタンダードプラン以上でご利用いただけます" },
      { status: 403 }
    );
  }

  // チャットボット取得
  const { data: chatbot } = await supabase
    .from("chatbots")
    .select("id")
    .eq("user_id", user.id)
    .single();

  if (!chatbot) {
    return NextResponse.json({ error: "Chatbot not found" }, { status: 404 });
  }

  // 既存FAQ数を取得
  const { count: existingCount } = await supabase
    .from("faqs")
    .select("*", { count: "exact", head: true })
    .eq("chatbot_id", chatbot.id);

  const limits = getPlanLimits(plan);
  const currentCount = existingCount ?? 0;

  // CSVファイル読み込み
  const formData = await req.formData();
  const file = formData.get("file") as File | null;
  if (!file) {
    return NextResponse.json({ error: "ファイルが必要です" }, { status: 400 });
  }

  const text = await file.text();
  // BOM除去
  const cleanText = text.replace(/^\uFEFF/, "");
  const rows = parseCSV(cleanText);

  if (rows.length === 0) {
    return NextResponse.json(
      { error: "有効なデータが見つかりませんでした" },
      { status: 400 }
    );
  }

  // 上限チェック
  const available =
    limits.faqLimit === Infinity
      ? rows.length
      : Math.max(0, limits.faqLimit - currentCount);

  const toInsert = rows.slice(0, available);
  const skipped = rows.length - toInsert.length;

  if (toInsert.length === 0) {
    return NextResponse.json(
      { error: "FAQ上限に達しているため追加できません。アップグレードしてください。" },
      { status: 400 }
    );
  }

  // 一括挿入
  const insertData = toInsert.map((row, i) => ({
    chatbot_id: chatbot.id,
    question: row.question,
    answer: row.answer,
    sort_order: currentCount + i,
  }));

  const { error } = await supabase.from("faqs").insert(insertData);

  if (error) {
    return NextResponse.json(
      { error: "インポートに失敗しました" },
      { status: 500 }
    );
  }

  return NextResponse.json({
    imported: toInsert.length,
    skipped,
    message:
      skipped > 0
        ? `${toInsert.length}件をインポートしました（${skipped}件はプラン上限のためスキップ）`
        : `${toInsert.length}件をインポートしました`,
  });
}
