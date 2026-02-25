import { createClient } from "@/lib/supabase/server";
import { createClient as createAdminClient } from "@supabase/supabase-js";
import { NextResponse } from "next/server";

export async function DELETE() {
  // 認証ユーザー確認
  const supabase = await createClient();
  const {
    data: { user },
  } = await supabase.auth.getUser();

  if (!user) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  // service_roleクライアント（RLSバイパス）
  const adminClient = createAdminClient(
    process.env.NEXT_PUBLIC_SUPABASE_URL!,
    process.env.SUPABASE_SERVICE_ROLE_KEY!
  );

  // プロフィール削除（CASCADE → chatbots → faqs, conversations → messages 全削除）
  const { error: profileError } = await adminClient
    .from("profiles")
    .delete()
    .eq("id", user.id);

  if (profileError) {
    console.error("Profile delete error:", profileError.message);
    return NextResponse.json(
      { error: "データの削除に失敗しました" },
      { status: 500 }
    );
  }

  // 認証ユーザー削除
  const { error: authError } = await adminClient.auth.admin.deleteUser(
    user.id
  );

  if (authError) {
    console.error("Auth user delete error:", authError.message);
    return NextResponse.json(
      { error: "アカウントの削除に失敗しました" },
      { status: 500 }
    );
  }

  return NextResponse.json({ success: true });
}
