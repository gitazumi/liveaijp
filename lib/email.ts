import { Resend } from "resend";

let _resend: Resend | null = null;

function getResend(): Resend {
  if (!_resend) {
    _resend = new Resend(process.env.RESEND_API_KEY);
  }
  return _resend;
}

/**
 * レポートメールを送信（PDF添付付き）
 */
export async function sendReportEmail({
  to,
  subject,
  html,
  pdfBuffer,
  filename,
}: {
  to: string;
  subject: string;
  html: string;
  pdfBuffer: Buffer;
  filename: string;
}) {
  const resend = getResend();

  const { data, error } = await resend.emails.send({
    from: "LiveAI <noreply@liveai.jp>",
    to,
    subject,
    html,
    attachments: [
      {
        filename,
        content: pdfBuffer,
        contentType: "application/pdf",
      },
    ],
  });

  if (error) {
    console.error("Email send error:", error);
    throw new Error(`メール送信に失敗しました: ${error.message}`);
  }

  return data;
}
