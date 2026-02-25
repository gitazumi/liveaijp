import { createClient } from "@/lib/supabase/server";
import { createClient as createAdminClient } from "@supabase/supabase-js";
import { NextResponse } from "next/server";

export async function GET() {
  const supabase = await createClient();
  const {
    data: { user },
  } = await supabase.auth.getUser();
  if (!user) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  const { data: profile } = await supabase
    .from("profiles")
    .select("role")
    .eq("id", user.id)
    .single();
  if (profile?.role !== "admin") {
    return NextResponse.json({ error: "Forbidden" }, { status: 403 });
  }

  const adminClient = createAdminClient(
    process.env.NEXT_PUBLIC_SUPABASE_URL!,
    process.env.SUPABASE_SERVICE_ROLE_KEY!
  );

  const { data: profiles } = await adminClient
    .from("profiles")
    .select("id, company_name, role, created_at")
    .order("created_at", { ascending: false });

  const {
    data: { users: authUsers },
  } = await adminClient.auth.admin.listUsers();

  const { data: chatbots } = await adminClient
    .from("chatbots")
    .select("id, user_id, name, token");

  const { data: faqs } = await adminClient
    .from("faqs")
    .select("id, chatbot_id");

  const { data: conversations } = await adminClient
    .from("conversations")
    .select("id, chatbot_id");

  const enrichedUsers = profiles?.map((p) => {
    const authUser = authUsers?.find((u) => u.id === p.id);
    const userChatbot = chatbots?.find((c) => c.user_id === p.id);
    const faqCount = faqs?.filter((f) => f.chatbot_id === userChatbot?.id).length ?? 0;
    const convCount = conversations?.filter((c) => c.chatbot_id === userChatbot?.id).length ?? 0;
    return {
      ...p,
      email: authUser?.email,
      banned: authUser?.banned_until ? new Date(authUser.banned_until) > new Date() : false,
      chatbot: userChatbot
        ? { ...userChatbot, faqCount, conversationCount: convCount }
        : null,
    };
  });

  return NextResponse.json(enrichedUsers);
}
