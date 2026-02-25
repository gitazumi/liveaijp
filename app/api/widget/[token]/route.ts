import { NextRequest, NextResponse } from "next/server";
import { createClient } from "@supabase/supabase-js";

const supabaseAdmin = createClient(
  process.env.NEXT_PUBLIC_SUPABASE_URL!,
  process.env.SUPABASE_SERVICE_ROLE_KEY!
);

/** Hex色コードのみ許可（XSS防止） */
function sanitizeColor(value: string): string {
  return /^#[0-9a-fA-F]{3,6}$/.test(value) ? value : "#4f46e5";
}

/** JSテンプレートリテラル内でのHTMLインジェクション防止 */
function escapeForJs(value: string): string {
  return value
    .replace(/\\/g, "\\\\")
    .replace(/`/g, "\\`")
    .replace(/\$/g, "\\$")
    .replace(/'/g, "\\'")
    .replace(/"/g, "&quot;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}

export async function GET(
  request: NextRequest,
  { params }: { params: Promise<{ token: string }> }
) {
  const { token } = await params;
  const apiUrl = `${request.nextUrl.origin}/api/chat`;

  // チャットボットのカスタム設定を取得
  const { data: chatbot } = await supabaseAdmin
    .from("chatbots")
    .select("widget_color, widget_display_name, widget_placeholder, greeting, allowed_origins")
    .eq("token", token)
    .single();

  // XSSサニタイズ
  const widgetColor = sanitizeColor(chatbot?.widget_color || "#4f46e5");
  const widgetName = escapeForJs(chatbot?.widget_display_name || "LiveAI");
  const widgetPlaceholder = escapeForJs(chatbot?.widget_placeholder || "メッセージを入力...");
  const greetingMsg = escapeForJs(chatbot?.greeting || "こんにちは！なんでもお聞きください！");

  // オリジン制御
  const requestOrigin = request.headers.get("origin");
  const allowedOrigins: string[] | null = chatbot?.allowed_origins;
  let corsOrigin = "*";
  if (allowedOrigins?.length && requestOrigin) {
    corsOrigin = allowedOrigins.includes(requestOrigin) ? requestOrigin : allowedOrigins[0];
  } else if (requestOrigin) {
    corsOrigin = requestOrigin;
  }

  const js = `
(function() {
  if (window.location.hostname === 'liveai.jp') return;
  if (document.getElementById('liveai-widget')) return;

  var container = document.createElement('div');
  container.id = 'liveai-widget';
  document.body.appendChild(container);

  var shadow = container.attachShadow({ mode: 'open' });

  var style = document.createElement('style');
  style.textContent = \`
    * { margin: 0; padding: 0; box-sizing: border-box; }
    .liveai-btn {
      position: fixed; bottom: 20px; right: 20px; z-index: 9999;
      width: 56px; height: 56px; border-radius: 50%;
      background: ${widgetColor}; color: white; border: none; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 4px 12px ${widgetColor}66;
      transition: transform 0.2s;
    }
    .liveai-btn:hover { transform: scale(1.05); }
    .liveai-btn svg { width: 24px; height: 24px; }
    .liveai-chat {
      position: fixed; bottom: 88px; right: 20px; z-index: 9999;
      width: 380px; max-width: calc(100vw - 40px); height: 520px; max-height: calc(100vh - 120px);
      background: white; border-radius: 16px; box-shadow: 0 8px 30px rgba(0,0,0,0.12);
      display: none; flex-direction: column; overflow: hidden;
      border: 1px solid #e5e7eb; font-family: -apple-system, sans-serif;
    }
    .liveai-chat.open { display: flex; }
    .liveai-header {
      padding: 16px; background: ${widgetColor}; color: white;
      font-weight: 600; font-size: 14px;
      display: flex; align-items: center; gap: 8px;
    }
    .liveai-messages {
      flex: 1; overflow-y: auto; padding: 16px;
      display: flex; flex-direction: column; gap: 8px;
    }
    .liveai-msg { max-width: 85%; padding: 10px 14px; border-radius: 16px; font-size: 14px; line-height: 1.5; word-break: break-word; }
    .liveai-msg.user { align-self: flex-end; background: ${widgetColor}; color: white; border-bottom-right-radius: 4px; }
    .liveai-msg.bot { align-self: flex-start; background: #f3f4f6; color: #1f2937; border-bottom-left-radius: 4px; }
    .liveai-input-wrap {
      padding: 12px; border-top: 1px solid #e5e7eb;
      display: flex; gap: 8px;
    }
    .liveai-input {
      flex: 1; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 24px;
      font-size: 14px; outline: none; resize: none; max-height: 100px;
      font-family: inherit;
    }
    .liveai-input:focus { border-color: ${widgetColor}; }
    .liveai-send {
      width: 40px; height: 40px; border-radius: 50%;
      background: ${widgetColor}; color: white; border: none; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .liveai-send:disabled { opacity: 0.5; cursor: not-allowed; }
    .liveai-typing { display: flex; gap: 4px; padding: 10px 14px; }
    .liveai-typing span {
      width: 8px; height: 8px; border-radius: 50%; background: #9ca3af;
      animation: bounce 1.4s infinite ease-in-out;
    }
    .liveai-typing span:nth-child(2) { animation-delay: 0.2s; }
    .liveai-typing span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes bounce {
      0%, 80%, 100% { transform: scale(0); }
      40% { transform: scale(1); }
    }
  \`;
  shadow.appendChild(style);

  var chatIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>';
  var closeIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>';
  var sendIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4z"/></svg>';

  var html = \`
    <button class="liveai-btn" id="liveai-toggle">\${chatIcon}</button>
    <div class="liveai-chat" id="liveai-chat">
      <div class="liveai-header">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        ${widgetName}
      </div>
      <div class="liveai-messages" id="liveai-messages"></div>
      <div class="liveai-input-wrap">
        <textarea class="liveai-input" id="liveai-input" rows="1" placeholder="${widgetPlaceholder}"></textarea>
        <button class="liveai-send" id="liveai-send">\${sendIcon}</button>
      </div>
    </div>
  \`;

  var wrapper = document.createElement('div');
  wrapper.innerHTML = html;
  while (wrapper.firstChild) shadow.appendChild(wrapper.firstChild);

  var isOpen = false;
  var messages = [];
  var conversationId = null;
  var sending = false;

  var toggleBtn = shadow.getElementById('liveai-toggle');
  var chatDiv = shadow.getElementById('liveai-chat');
  var messagesDiv = shadow.getElementById('liveai-messages');
  var inputEl = shadow.getElementById('liveai-input');
  var sendBtn = shadow.getElementById('liveai-send');

  toggleBtn.addEventListener('click', function() {
    isOpen = !isOpen;
    chatDiv.classList.toggle('open', isOpen);
    toggleBtn.innerHTML = isOpen ? closeIcon : chatIcon;
    if (isOpen && messages.length === 0) {
      addMessage('bot', '${greetingMsg.replace(/'/g, "\\'")}');
    }
  });

  inputEl.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey && !e.isComposing) {
      e.preventDefault();
      sendMessage();
    }
  });

  sendBtn.addEventListener('click', sendMessage);

  function addMessage(role, content) {
    var div = document.createElement('div');
    div.className = 'liveai-msg ' + (role === 'user' ? 'user' : 'bot');
    div.textContent = content;
    messagesDiv.appendChild(div);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
    if (role !== 'typing') {
      messages.push({ role: role === 'user' ? 'user' : 'assistant', content: content });
    }
    return div;
  }

  function showTyping() {
    var div = document.createElement('div');
    div.className = 'liveai-msg bot';
    div.innerHTML = '<div class="liveai-typing"><span></span><span></span><span></span></div>';
    div.id = 'liveai-typing';
    messagesDiv.appendChild(div);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
    return div;
  }

  async function sendMessage() {
    var text = inputEl.value.trim();
    if (!text || sending) return;
    sending = true;
    sendBtn.disabled = true;
    inputEl.value = '';

    addMessage('user', text);
    var typingEl = showTyping();

    try {
      var res = await fetch('${apiUrl}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          messages: messages.filter(function(m) { return m.role === 'user' || m.role === 'assistant'; }),
          token: '${token}',
          conversationId: conversationId
        })
      });

      if (!res.ok) {
        typingEl.remove();
        var errText = await res.text();
        addMessage('bot', errText || 'エラーが発生しました。');
        sending = false;
        sendBtn.disabled = false;
        return;
      }

      if (res.headers.get('X-Conversation-Id')) {
        conversationId = res.headers.get('X-Conversation-Id');
      }

      typingEl.remove();

      var reader = res.body.getReader();
      var decoder = new TextDecoder();
      var botMsg = document.createElement('div');
      botMsg.className = 'liveai-msg bot';
      messagesDiv.appendChild(botMsg);

      var fullText = '';
      while (true) {
        var result = await reader.read();
        if (result.done) break;
        var chunk = decoder.decode(result.value, { stream: true });
        fullText += chunk;
        botMsg.textContent = fullText;
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
      }
      if (fullText) {
        messages.push({ role: 'assistant', content: fullText });
      }
    } catch(err) {
      typingEl.remove();
      addMessage('bot', 'エラーが発生しました。もう一度お試しください。');
    }

    sending = false;
    sendBtn.disabled = false;
    inputEl.focus();
  }
})();
`;

  return new NextResponse(js, {
    headers: {
      "Content-Type": "application/javascript",
      "Access-Control-Allow-Origin": corsOrigin,
      "Vary": "Origin",
      "Cache-Control": "public, max-age=300",
    },
  });
}
