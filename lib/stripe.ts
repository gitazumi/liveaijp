import Stripe from "stripe";

let _stripe: Stripe | null = null;

export function getStripe(): Stripe {
  if (!_stripe) {
    _stripe = new Stripe(process.env.STRIPE_SECRET_KEY!);
  }
  return _stripe;
}

export const PLANS = {
  free: {
    name: "無料",
    price: 0,
    faqLimit: 10,
    conversationLimit: 100,
    priceId: null,
  },
  standard: {
    name: "スタンダード",
    price: 3980,
    faqLimit: 50,
    conversationLimit: 1000,
    priceId: process.env.STRIPE_STANDARD_PRICE_ID ?? null,
  },
  pro: {
    name: "プロ",
    price: 9800,
    faqLimit: Infinity,
    conversationLimit: Infinity,
    priceId: process.env.STRIPE_PRO_PRICE_ID ?? null,
  },
} as const;

export type PlanType = keyof typeof PLANS;

export function getPlanLimits(plan: PlanType) {
  return PLANS[plan] ?? PLANS.free;
}
