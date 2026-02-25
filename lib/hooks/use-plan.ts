"use client";

import { useEffect, useState, useCallback } from "react";
import { createClient } from "@/lib/supabase/client";
import { PLAN_FEATURES, type PlanType, type FeatureKey } from "@/lib/stripe";

interface PlanState {
  plan: PlanType;
  status: string;
  faqCount: number;
  faqLimit: number;
  conversationCount: number;
  conversationLimit: number;
  loading: boolean;
}

const PLAN_LIMITS: Record<PlanType, { faqLimit: number; conversationLimit: number }> = {
  free: { faqLimit: 10, conversationLimit: 100 },
  standard: { faqLimit: 50, conversationLimit: 1000 },
  pro: { faqLimit: Infinity, conversationLimit: Infinity },
};

export function usePlan() {
  const [state, setState] = useState<PlanState>({
    plan: "free",
    status: "active",
    faqCount: 0,
    faqLimit: 10,
    conversationCount: 0,
    conversationLimit: 100,
    loading: true,
  });

  useEffect(() => {
    async function load() {
      const supabase = createClient();
      const {
        data: { user },
      } = await supabase.auth.getUser();
      if (!user) {
        setState((prev) => ({ ...prev, loading: false }));
        return;
      }

      // サブスクリプション取得
      const { data: subscription } = await supabase
        .from("subscriptions")
        .select("plan, status")
        .eq("user_id", user.id)
        .single();

      const plan = (subscription?.plan as PlanType) ?? "free";
      const limits = PLAN_LIMITS[plan] ?? PLAN_LIMITS.free;

      // チャットボット取得
      const { data: chatbot } = await supabase
        .from("chatbots")
        .select("id")
        .eq("user_id", user.id)
        .single();

      let faqCount = 0;
      let conversationCount = 0;

      if (chatbot) {
        const { count: fc } = await supabase
          .from("faqs")
          .select("*", { count: "exact", head: true })
          .eq("chatbot_id", chatbot.id);
        faqCount = fc ?? 0;

        const startOfMonth = new Date();
        startOfMonth.setDate(1);
        startOfMonth.setHours(0, 0, 0, 0);

        const { count: cc } = await supabase
          .from("conversations")
          .select("*", { count: "exact", head: true })
          .eq("chatbot_id", chatbot.id)
          .gte("created_at", startOfMonth.toISOString());
        conversationCount = cc ?? 0;
      }

      setState({
        plan,
        status: subscription?.status ?? "active",
        faqCount,
        faqLimit: limits.faqLimit,
        conversationCount,
        conversationLimit: limits.conversationLimit,
        loading: false,
      });
    }

    load();
  }, []);

  const canUse = useCallback(
    (feature: FeatureKey): boolean => {
      return PLAN_FEATURES[state.plan]?.[feature] ?? false;
    },
    [state.plan]
  );

  return { ...state, canUse };
}
