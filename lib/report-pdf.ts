import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import type { AnalyticsData } from "./analytics";
import { getCountryName } from "./country-names";

interface ReportOptions {
  /** レポート対象期間の日数 */
  days: number;
  /** ユーザーのメールアドレス */
  email: string;
  /** admin用: 追加統計 */
  adminStats?: {
    totalUsers: number;
    newUsersInPeriod: number;
  };
}

/**
 * 分析データからPDFレポートを生成
 * 日本語テキストは英数字+基本記号でフォールバック
 */
export function generateReportPdf(
  data: AnalyticsData,
  options: ReportOptions
): Buffer {
  const doc = new jsPDF({ orientation: "portrait", unit: "mm", format: "a4" });

  const pageWidth = doc.internal.pageSize.getWidth();
  let y = 20;

  // --- ヘッダー ---
  doc.setFontSize(20);
  doc.setTextColor(30, 30, 30);
  doc.text("LiveAI Report", pageWidth / 2, y, { align: "center" });
  y += 10;

  doc.setFontSize(10);
  doc.setTextColor(100, 100, 100);
  const now = new Date();
  const periodStart = new Date();
  periodStart.setDate(periodStart.getDate() - options.days);
  doc.text(
    `Period: ${periodStart.toISOString().slice(0, 10)} - ${now.toISOString().slice(0, 10)}`,
    pageWidth / 2,
    y,
    { align: "center" }
  );
  y += 5;
  doc.text(`Generated: ${now.toISOString().slice(0, 19).replace("T", " ")} UTC`, pageWidth / 2, y, {
    align: "center",
  });
  y += 10;

  // --- 区切り線 ---
  doc.setDrawColor(200, 200, 200);
  doc.line(20, y, pageWidth - 20, y);
  y += 10;

  // --- サマリー ---
  doc.setFontSize(14);
  doc.setTextColor(30, 30, 30);
  doc.text("Summary", 20, y);
  y += 8;

  doc.setFontSize(11);
  doc.text(`Total Conversations: ${data.totalConversations}`, 25, y);
  y += 6;
  doc.text(`Total Messages (User): ${data.totalMessages}`, 25, y);
  y += 6;

  if (options.adminStats) {
    doc.text(`Total Users: ${options.adminStats.totalUsers}`, 25, y);
    y += 6;
    doc.text(
      `New Users (${options.days} days): ${options.adminStats.newUsersInPeriod}`,
      25,
      y
    );
    y += 6;
  }
  y += 6;

  // --- TOP質問 ---
  if (data.topQuestions.length > 0) {
    doc.setFontSize(14);
    doc.text("Top Questions", 20, y);
    y += 4;

    autoTable(doc, {
      startY: y,
      head: [["#", "Question", "Count"]],
      body: data.topQuestions.map((q, i) => [
        (i + 1).toString(),
        q.question.length > 60 ? q.question.slice(0, 57) + "..." : q.question,
        q.count.toString(),
      ]),
      margin: { left: 20, right: 20 },
      styles: { fontSize: 9, cellPadding: 2 },
      headStyles: { fillColor: [59, 130, 246] },
      columnStyles: {
        0: { cellWidth: 10 },
        1: { cellWidth: "auto" },
        2: { cellWidth: 15, halign: "right" },
      },
    });

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    y = (doc as any).lastAutoTable.finalY + 10;
  }

  // --- 未回答質問 ---
  if (data.unanswered.length > 0) {
    if (y > 240) {
      doc.addPage();
      y = 20;
    }

    doc.setFontSize(14);
    doc.text("Unanswered Questions (Not in FAQ)", 20, y);
    y += 4;

    autoTable(doc, {
      startY: y,
      head: [["#", "Question", "Count"]],
      body: data.unanswered.slice(0, 10).map((q, i) => [
        (i + 1).toString(),
        q.question.length > 60 ? q.question.slice(0, 57) + "..." : q.question,
        q.count.toString(),
      ]),
      margin: { left: 20, right: 20 },
      styles: { fontSize: 9, cellPadding: 2 },
      headStyles: { fillColor: [239, 68, 68] },
      columnStyles: {
        0: { cellWidth: 10 },
        1: { cellWidth: "auto" },
        2: { cellWidth: 15, halign: "right" },
      },
    });

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    y = (doc as any).lastAutoTable.finalY + 10;
  }

  // --- 時間帯別 ---
  if (y > 230) {
    doc.addPage();
    y = 20;
  }

  doc.setFontSize(14);
  doc.text("Hourly Distribution", 20, y);
  y += 8;

  const maxHourly = Math.max(...data.hourly, 1);
  const barWidth = (pageWidth - 50) / 24;
  const barMaxHeight = 30;

  for (let h = 0; h < 24; h++) {
    const barHeight = (data.hourly[h] / maxHourly) * barMaxHeight;
    const x = 25 + h * barWidth;

    if (barHeight > 0) {
      doc.setFillColor(59, 130, 246);
      doc.rect(x, y + barMaxHeight - barHeight, barWidth - 1, barHeight, "F");
    }

    if (h % 3 === 0) {
      doc.setFontSize(6);
      doc.setTextColor(100, 100, 100);
      doc.text(`${h}`, x + barWidth / 2, y + barMaxHeight + 4, {
        align: "center",
      });
    }
  }
  y += barMaxHeight + 12;

  // --- 地域別 ---
  if (data.topCountries.length > 0) {
    if (y > 240) {
      doc.addPage();
      y = 20;
    }

    doc.setFontSize(14);
    doc.setTextColor(30, 30, 30);
    doc.text("Top Countries", 20, y);
    y += 4;

    autoTable(doc, {
      startY: y,
      head: [["Country", "Conversations"]],
      body: data.topCountries.map((c) => [
        getCountryName(c.country),
        c.count.toString(),
      ]),
      margin: { left: 20, right: 20 },
      styles: { fontSize: 9, cellPadding: 2 },
      headStyles: { fillColor: [34, 197, 94] },
      columnStyles: {
        0: { cellWidth: "auto" },
        1: { cellWidth: 30, halign: "right" },
      },
    });

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    y = (doc as any).lastAutoTable.finalY + 10;
  }

  // --- 都市別 ---
  if (data.topCities.length > 0) {
    if (y > 240) {
      doc.addPage();
      y = 20;
    }

    doc.setFontSize(14);
    doc.text("Top Cities", 20, y);
    y += 4;

    autoTable(doc, {
      startY: y,
      head: [["City", "Country", "Conversations"]],
      body: data.topCities.map((c) => [
        c.city,
        getCountryName(c.country),
        c.count.toString(),
      ]),
      margin: { left: 20, right: 20 },
      styles: { fontSize: 9, cellPadding: 2 },
      headStyles: { fillColor: [34, 197, 94] },
      columnStyles: {
        0: { cellWidth: "auto" },
        1: { cellWidth: 40 },
        2: { cellWidth: 30, halign: "right" },
      },
    });
  }

  // --- フッター ---
  const pageCount = doc.getNumberOfPages();
  for (let i = 1; i <= pageCount; i++) {
    doc.setPage(i);
    doc.setFontSize(8);
    doc.setTextColor(150, 150, 150);
    doc.text(
      `LiveAI Report - Page ${i}/${pageCount}`,
      pageWidth / 2,
      doc.internal.pageSize.getHeight() - 10,
      { align: "center" }
    );
  }

  // ArrayBuffer → Buffer
  const arrayBuffer = doc.output("arraybuffer");
  return Buffer.from(arrayBuffer);
}
