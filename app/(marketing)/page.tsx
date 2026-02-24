import Script from "next/script";
import { Header } from "@/components/landing/header";
import { Hero } from "@/components/landing/hero";
import { Features } from "@/components/landing/features";
import { HowItWorks } from "@/components/landing/how-it-works";
import { Benefits } from "@/components/landing/benefits";
import { Pricing } from "@/components/landing/pricing";
import { Faq } from "@/components/landing/faq";
import { Footer } from "@/components/landing/footer";

export default function HomePage() {
  return (
    <>
      <Header />
      <main>
        <Hero />
        <Features />
        <HowItWorks />
        <Benefits />
        <Pricing />
        <Faq />
      </main>
      <Footer />
      <Script
        src="/api/widget/6772cc2d-f404-41bc-96cd-2518d5641146"
        strategy="lazyOnload"
      />
    </>
  );
}
