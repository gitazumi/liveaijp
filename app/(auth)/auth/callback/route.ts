import { createClient } from "@/lib/supabase/server";
import { NextResponse } from "next/server";

export async function GET(request: Request) {
  const { searchParams, origin } = new URL(request.url);
  const code = searchParams.get("code");
  const next = searchParams.get("next") ?? "/dashboard";

  if (code) {
    const supabase = await createClient();
    const { error } = await supabase.auth.exchangeCodeForSession(code);
    if (error) {
      console.error("Auth callback error:", error.message, error.status);
      const loginUrl = new URL("/login", origin);
      loginUrl.searchParams.set("error", "callback_failed");
      return NextResponse.redirect(loginUrl.toString());
    }

    // Ensure subscription record exists for this user
    const {
      data: { user },
    } = await supabase.auth.getUser();
    if (user) {
      const { data: existing } = await supabase
        .from("subscriptions")
        .select("id")
        .eq("user_id", user.id)
        .single();
      if (!existing) {
        await supabase.from("subscriptions").insert({
          user_id: user.id,
          plan: "free",
          status: "active",
        });
      }
    }

    return NextResponse.redirect(`${origin}${next}`);
  }

  console.error("Auth callback: no code parameter in URL");
  return NextResponse.redirect(`${origin}/login`);
}
