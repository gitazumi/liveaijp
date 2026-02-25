import { createClient } from "@/lib/supabase/server";
import { createClient as createAdminClient } from "@supabase/supabase-js";
import { NextRequest, NextResponse } from "next/server";

async function verifyAdmin() {
  const supabase = await createClient();
  const {
    data: { user },
  } = await supabase.auth.getUser();
  if (!user) return null;
  const { data: profile } = await supabase
    .from("profiles")
    .select("role")
    .eq("id", user.id)
    .single();
  return profile?.role === "admin" ? user : null;
}

export async function GET(
  _req: NextRequest,
  { params }: { params: Promise<{ id: string }> }
) {
  const admin = await verifyAdmin();
  if (!admin) return NextResponse.json({ error: "Forbidden" }, { status: 403 });

  const { id } = await params;
  const adminClient = createAdminClient(
    process.env.NEXT_PUBLIC_SUPABASE_URL!,
    process.env.SUPABASE_SERVICE_ROLE_KEY!
  );

  const [profileRes, chatbotRes] = await Promise.all([
    adminClient.from("profiles").select("*").eq("id", id).single(),
    adminClient.from("chatbots").select("*").eq("user_id", id).single(),
  ]);

  const {
    data: { users },
  } = await adminClient.auth.admin.listUsers();
  const authUser = users?.find((u) => u.id === id);

  let faqs: unknown[] = [];
  let conversations: unknown[] = [];
  if (chatbotRes.data) {
    const [faqRes, convRes] = await Promise.all([
      adminClient
        .from("faqs")
        .select("*")
        .eq("chatbot_id", chatbotRes.data.id)
        .order("sort_order"),
      adminClient
        .from("conversations")
        .select("id, created_at")
        .eq("chatbot_id", chatbotRes.data.id)
        .order("created_at", { ascending: false })
        .limit(20),
    ]);
    faqs = faqRes.data ?? [];
    conversations = convRes.data ?? [];
  }

  return NextResponse.json({
    profile: profileRes.data,
    email: authUser?.email,
    banned: authUser?.banned_until
      ? new Date(authUser.banned_until) > new Date()
      : false,
    chatbot: chatbotRes.data,
    faqs,
    conversations,
  });
}

export async function PATCH(
  req: NextRequest,
  { params }: { params: Promise<{ id: string }> }
) {
  const admin = await verifyAdmin();
  if (!admin) return NextResponse.json({ error: "Forbidden" }, { status: 403 });

  const { id } = await params;
  const body = await req.json();
  const adminClient = createAdminClient(
    process.env.NEXT_PUBLIC_SUPABASE_URL!,
    process.env.SUPABASE_SERVICE_ROLE_KEY!
  );

  if (body.banned !== undefined) {
    const { error } = await adminClient.auth.admin.updateUserById(id, {
      ban_duration: body.banned ? "876000h" : "none",
    });
    if (error)
      return NextResponse.json({ error: error.message }, { status: 500 });
  }

  return NextResponse.json({ success: true });
}
