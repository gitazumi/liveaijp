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
    return NextResponse.redirect(`${origin}${next}`);
  }

  console.error("Auth callback: no code parameter in URL");
  return NextResponse.redirect(`${origin}/login`);
}
