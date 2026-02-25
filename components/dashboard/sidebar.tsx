"use client";

import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import { createClient } from "@/lib/supabase/client";
import { Button } from "@/components/ui/button";
import {
  MessageSquare,
  LayoutDashboard,
  HelpCircle,
  History,
  Settings,
  User,
  LogOut,
  Shield,
  CreditCard,
  BarChart3,
  LifeBuoy,
} from "lucide-react";
import { cn } from "@/lib/utils";

const navItems = [
  { href: "/dashboard", icon: LayoutDashboard, label: "ダッシュボード" },
  { href: "/dashboard/faqs", icon: HelpCircle, label: "FAQ管理" },
  { href: "/dashboard/history", icon: History, label: "チャット履歴" },
  { href: "/dashboard/analytics", icon: BarChart3, label: "分析" },
  { href: "/dashboard/settings", icon: Settings, label: "設定" },
  { href: "/dashboard/account", icon: User, label: "アカウント" },
  { href: "/dashboard/billing", icon: CreditCard, label: "課金" },
];

export function Sidebar() {
  const pathname = usePathname();
  const router = useRouter();
  const [isAdmin, setIsAdmin] = useState(false);
  const [unreadSupport, setUnreadSupport] = useState(0);

  useEffect(() => {
    async function checkRole() {
      const supabase = createClient();
      const {
        data: { user },
      } = await supabase.auth.getUser();
      if (!user) return;
      const { data: profile } = await supabase
        .from("profiles")
        .select("role")
        .eq("id", user.id)
        .single();
      setIsAdmin(profile?.role === "admin");
    }
    checkRole();
  }, []);

  // 未読サポートメッセージのポーリング
  useEffect(() => {
    async function checkUnread() {
      const supabase = createClient();
      const {
        data: { user },
      } = await supabase.auth.getUser();
      if (!user) return;

      const { data: profile } = await supabase
        .from("profiles")
        .select("role")
        .eq("id", user.id)
        .single();

      if (profile?.role === "admin") {
        // admin: 全チケットの未読ユーザーメッセージ
        const { count } = await supabase
          .from("support_messages")
          .select("*", { count: "exact", head: true })
          .eq("sender_role", "user")
          .is("read_at", null);
        setUnreadSupport(count ?? 0);
      } else {
        // ユーザー: 自分のチケットの未読adminメッセージ
        const { data: tickets } = await supabase
          .from("support_tickets")
          .select("id")
          .eq("user_id", user.id);
        if (tickets && tickets.length > 0) {
          const ticketIds = tickets.map((t) => t.id);
          const { count } = await supabase
            .from("support_messages")
            .select("*", { count: "exact", head: true })
            .in("ticket_id", ticketIds)
            .eq("sender_role", "admin")
            .is("read_at", null);
          setUnreadSupport(count ?? 0);
        }
      }
    }

    checkUnread();
    const interval = setInterval(checkUnread, 30_000);
    return () => clearInterval(interval);
  }, []);

  async function handleLogout() {
    const supabase = createClient();
    await supabase.auth.signOut();
    router.push("/");
    router.refresh();
  }

  const allNavItems = [
    ...navItems,
    { href: "/dashboard/support", icon: LifeBuoy, label: "お問い合わせ" },
    ...(isAdmin
      ? [
          { href: "/dashboard/admin/support", icon: LifeBuoy, label: "お問い合わせ管理" },
          { href: "/dashboard/admin", icon: Shield, label: "管理者" },
        ]
      : []),
  ];

  return (
    <aside className="flex h-screen w-64 flex-col border-r bg-sidebar">
      <div className="flex h-16 items-center gap-2 border-b px-6">
        <Link href="/" className="flex items-center gap-2 font-bold text-lg">
          <MessageSquare className="h-5 w-5 text-primary" />
          LiveAI
        </Link>
      </div>
      <nav className="flex-1 space-y-1 p-4">
        {allNavItems.map((item) => {
          const isActive =
            pathname === item.href ||
            (item.href !== "/dashboard" && pathname.startsWith(item.href));
          return (
            <Link
              key={item.href}
              href={item.href}
              className={cn(
                "flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors",
                isActive
                  ? "bg-sidebar-accent text-sidebar-accent-foreground"
                  : "text-sidebar-foreground/70 hover:bg-sidebar-accent/50 hover:text-sidebar-foreground"
              )}
            >
              <item.icon className="h-4 w-4" />
              {item.label}
              {(item.href === "/dashboard/support" || item.href === "/dashboard/admin/support") &&
                unreadSupport > 0 && (
                  <span className="ml-auto flex h-5 min-w-5 items-center justify-center rounded-full bg-destructive px-1.5 text-[10px] font-bold text-white">
                    {unreadSupport}
                  </span>
                )}
            </Link>
          );
        })}
      </nav>
      <div className="border-t p-4">
        <Button
          variant="ghost"
          className="w-full justify-start gap-3 text-muted-foreground"
          onClick={handleLogout}
        >
          <LogOut className="h-4 w-4" />
          ログアウト
        </Button>
      </div>
    </aside>
  );
}
