(async function() {
    class AIChatbot {
        constructor() {
            this.token = this.getTokenFromScript();
            this.conversationId = 'conv_' + Math.random().toString(36).substr(2, 9);
            this.apiEndpoint = 'https://liveai.jp/api/chat/message';
            this.welcomeMessageShown = false; // ウェルカムメッセージが表示されたかどうかを追跡
            console.log('Chatbot Token:', this.token); // トークンをデバッグ出力
            this.init();
        }

        getTokenFromScript() {
            const scripts = document.getElementsByTagName('script');
            for (let i = 0; i < scripts.length; i++) {
                if (scripts[i].src && scripts[i].src.includes('chatbot.js')) {
                    const url = new URL(scripts[i].src);
                    return url.searchParams.get('token');
                }
            }
            const lastScript = scripts[scripts.length - 1];
            if (lastScript && lastScript.src) {
                const url = new URL(lastScript.src);
                return url.searchParams.get('token');
            }
            return null;
        }

        init() {
            this.injectMetaTag();
            this.injectStyles();
            this.createWidget();
            this.addEventListeners();
            this.setupResizing();
        }

        injectMetaTag() {
            if (!document.querySelector('meta[name="viewport"]')) {
                const meta = document.createElement('meta');
                meta.name = 'viewport';
                meta.content = 'width=device-width, initial-scale=1, maximum-scale=1';
                document.head.appendChild(meta);
            }
        }

        injectStyles() {
            const css = `
                #ai-chat-widget {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    z-index: 99999;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                }

                #ai-chat-button {
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    background: #2563eb;
                    border: none;
                    cursor: pointer;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    transition: transform 0.2s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                #ai-chat-button:hover {
                    transform: scale(1.05);
                }

                #ai-chat-window {
                    display: none;
                    position: fixed;
                    bottom: 90px;
                    right: 20px;
                    width: 350px;
                    height: 480px;
                    background: white;
                    border-radius: 16px;
                    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
                    flex-direction: column;
                    overflow: hidden;
                    resize: both;
                }

                #ai-chat-resize-handle {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 20px;
                    height: 20px;
                    cursor: nwse-resize;
                    z-index: 100;
                }

                #ai-chat-resize-handle::before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 10px;
                    height: 10px;
                    border-top: 2px solid #2563eb;
                    border-left: 2px solid #2563eb;
                    border-top-left-radius: 4px;
                }

                .ai-chat-header {
                    padding: 20px;
                    background: white;
                    color: black;
                    font-weight: 600;
                    font-size: 16px;
                }

                .ai-chat-messages {
                    flex: 1;
                    overflow-y: auto;
                    padding: 20px;
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    background: white;
                }

                .ai-chat-input {
                    padding: 16px;
                    border-top: 1px solid #e2e8f0;
                    display: flex;
                    background: white;
                }

                .ai-chat-input input {
                    flex: 1;
                    padding: 12px;
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    margin-right: 8px;
                    font-size: 16px;
                    outline: none;
                    transition: border-color 0.2s ease;
                    -webkit-text-size-adjust: 100%;
                }

                .ai-chat-input input:focus {
                    border-color: #2563eb;
                }

                .ai-chat-input button {
                    padding: 8px 16px;
                    background: #2563eb;
                    color: white;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    font-weight: 500;
                    transition: background 0.2s ease;
                }

                .ai-chat-input button:hover {
                    background: #1d4ed8;
                }

                .ai-message {
                    padding: 12px 16px;
                    border-radius: 12px;
                    max-width: 85%;
                    line-height: 1.5;
                    font-size: 14px;
                    white-space: pre-wrap;
                    word-wrap: break-word;
                }

                .ai-message.user {
                    background: #f1f5f9; /* bg-light equivalent */
                    color: black;
                    margin-left: auto;
                    border-bottom-right-radius: 4px;
                }

                .ai-message.bot {
                    background: white;
                    color: #1f2937;
                    margin-right: auto;
                    border-bottom-left-radius: 4px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                }

                .ai-chat-messages::-webkit-scrollbar {
                    width: 6px;
                }

                .ai-chat-messages::-webkit-scrollbar-track {
                    background: transparent;
                }

                .ai-chat-messages::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 3px;
                }

                .ai-message.loading {
                    display: flex;
                    align-items: center;
                    gap: 4px;
                }

                .ai-message.loading span {
                    width: 8px;
                    height: 8px;
                    background: #94a3b8;
                    border-radius: 50%;
                    display: inline-block;
                    animation: bounce 1.4s infinite ease-in-out both;
                }

                .ai-message.loading span:nth-child(1) { animation-delay: -0.32s; }
                .ai-message.loading span:nth-child(2) { animation-delay: -0.16s; }

                @keyframes bounce {
                    0%, 80%, 100% { transform: scale(0); }
                    40% { transform: scale(1.0); }
                }

                .ai-message p {
                    margin: 0 0 10px 0;
                }

                .ai-message p:last-child {
                    margin-bottom: 0;
                }

                .ai-message pre {
                    background: #f1f5f9;
                    padding: 10px;
                    border-radius: 6px;
                    overflow-x: auto;
                    margin: 8px 0;
                }

                .ai-message code {
                    font-family: monospace;
                    background: #f1f5f9;
                    padding: 2px 4px;
                    border-radius: 4px;
                    font-size: 0.9em;
                }

                .ai-message ul, .ai-message ol {
                    margin: 8px 0;
                    padding-left: 20px;
                }

                .ai-message li {
                    margin: 4px 0;
                }

                @media (max-width: 767px) {
                    #ai-chat-window {
                        width: 90%;
                        height: 50vh;
                        bottom: 90px;
                        right: 5%;
                    }

                    .ai-chat-input {
                        padding: 12px;
                    }

                    .ai-chat-input input {
                        font-size: 16px;
                        -webkit-text-size-adjust: 100%;
                    }
                }
            `;
            const style = document.createElement('style');
            style.textContent = css;
            document.head.appendChild(style);
        }

        createWidget() {
            const widget = document.createElement('div');
            widget.id = 'ai-chat-widget';

            widget.innerHTML = `
                <div id="ai-chat-window">
                    <div id="ai-chat-resize-handle"></div>
                    <div class="ai-chat-header">
                        <a href="https://liveai.jp" target="_blank" style="color: black; text-decoration: none;">liveAI</a>
                        <button id="ai-chat-close" style="position: absolute; top: 15px; right: 15px; background: none; border: none; cursor: pointer;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="ai-chat-messages"></div>
                    <div class="ai-chat-input">
                        <input type="text" placeholder="メッセージを入力してください...">
                        <button>送信</button>
                    </div>
                </div>
                <button id="ai-chat-button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </button>
            `;

            document.body.appendChild(widget);
        }

        addEventListeners() {
            const button = document.getElementById('ai-chat-button');
            const window = document.getElementById('ai-chat-window');
            const input = window.querySelector('input');
            const sendButton = window.querySelector('button');
            const closeButton = document.getElementById('ai-chat-close');

            button.addEventListener('click', () => {
                if (window.style.display === 'none' || window.style.display === '') {
                    window.style.display = 'flex';
                    if (!this.welcomeMessageShown) {
                        this.showWelcomeMessage();
                    }
                } else {
                    window.style.display = 'none';
                }
            });

            closeButton.addEventListener('click', () => {
                window.style.display = 'none';
            });

            const sendMessage = () => {
                const message = input.value.trim();
                if (message) {
                    this.sendMessage(message);
                    input.value = '';
                }
            };

            sendButton.addEventListener('click', sendMessage);
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') sendMessage();
            });
        }

        formatMessage(text) {
            text = text.replace(/\[([^\]]+)\]\(([^)]+)(?!\))/g, '[$1]($2)');
            
            const tempDiv = document.createElement('div');
            
            let formatted = text.split('\n').map(line => {
                line = line.trim();
                if (!line) return '';
                
                const markdownLinkRegex = /\[([^\]]+)\]\(([^)]+)\)/g;
                line = line.replace(markdownLinkRegex, (match, text, url) => {
                    tempDiv.textContent = ''; // Clear previous content
                    const a = document.createElement('a');
                    a.href = url;
                    a.textContent = text;
                    a.target = '_blank';
                    a.rel = 'noopener noreferrer';
                    tempDiv.appendChild(a);
                    return tempDiv.innerHTML;
                });
                
                const urlRegex = /(https?:\/\/[^\s\)\]]+)(?!\)|\])/g;
                line = line.replace(urlRegex, (match, url) => {
                    tempDiv.textContent = ''; // Clear previous content
                    const a = document.createElement('a');
                    a.href = url;
                    a.textContent = url;
                    a.target = '_blank';
                    a.rel = 'noopener noreferrer';
                    tempDiv.appendChild(a);
                    return tempDiv.innerHTML;
                });
                return `<p>${line}</p>`;
            }).join('');

            formatted = formatted.replace(/```([\s\S]*?)```/g, '<pre><code>$1</code></pre>');
            formatted = formatted.replace(/`([^`]+)`/g, '<code>$1</code>');
            
            formatted = formatted.replace(/^\s*[-*]\s+(.+)/gm, '<li>$1</li>');
            formatted = formatted.replace(/(<li>.*?<\/li>\s*)+/g, '<ul>$&</ul>');
            
            return formatted;
        }

        addMessageToChat(message, isUser) {
            const messagesDiv = document.querySelector('.ai-chat-messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `ai-message ${isUser ? 'user' : 'bot'}`;

            if (isUser) {
                messageDiv.textContent = message;
            } else {
                messageDiv.innerHTML = this.formatMessage(message);
            }

            messagesDiv.appendChild(messageDiv);
            // messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        async sendMessage(message) {
            this.addMessageToChat(message, true);

            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'ai-message bot loading';
            loadingDiv.innerHTML = '<span></span><span></span><span></span>';

            const messagesDiv = document.querySelector('.ai-chat-messages');
            messagesDiv.appendChild(loadingDiv);
            // messagesDiv.scrollTop = messagesDiv.scrollHeight;

            try {
                console.log('Sending message:', message); // メッセージをデバッグ出力
                const response = await fetch(this.apiEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Chatbot-Token': this.token
                    },
                    body: JSON.stringify({
                        message,
                        conversation_id: this.conversationId
                    })
                });

                console.log('Response status:', response.status); // レスポンスステータスをデバッグ出力
                const data = await response.json();
                loadingDiv.remove();

                if (data.error) throw new Error(data.error);
                this.addMessageToChat(data.response, false);
            } catch (error) {
                loadingDiv.remove();
                console.error('Chat error:', error);
                this.addMessageToChat('Sorry, I encountered an error. Please try again later.', false);
            }
        }

        setupResizing() {
            const chatWindow = document.getElementById('ai-chat-window');
            const resizeHandle = document.getElementById('ai-chat-resize-handle');
            let isResizing = false;
            let initialWidth, initialHeight, initialX, initialY;

            const minWidth = 250;
            const minHeight = 250;
            const maxWidth = Math.min(800, window.innerWidth * 0.9);
            const maxHeight = Math.min(800, window.innerHeight * 0.9);

            resizeHandle.addEventListener('mousedown', (e) => {
                e.preventDefault();
                isResizing = true;

                initialWidth = parseInt(window.getComputedStyle(chatWindow).width);
                initialHeight = parseInt(window.getComputedStyle(chatWindow).height);
                initialX = e.clientX;
                initialY = e.clientY;

                chatWindow.classList.add('resizing');

                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });

            const onMouseMove = (e) => {
                if (!isResizing) return;

                const widthChange = initialX - e.clientX;
                const heightChange = initialY - e.clientY;

                let newWidth = Math.min(Math.max(initialWidth + widthChange, minWidth), maxWidth);
                let newHeight = Math.min(Math.max(initialHeight + heightChange, minHeight), maxHeight);

                const currentRight = parseInt(window.getComputedStyle(chatWindow).right);
                const currentBottom = parseInt(window.getComputedStyle(chatWindow).bottom);

                chatWindow.style.width = `${newWidth}px`;
                chatWindow.style.height = `${newHeight}px`;
            };

            const onMouseUp = () => {
                isResizing = false;
                chatWindow.classList.remove('resizing');

                document.removeEventListener('mousemove', onMouseMove);
                document.removeEventListener('mouseup', onMouseUp);
            };
        }

        async showWelcomeMessage() {
            try {
                const response = await fetch('https://liveai.jp/api/chat/store-info', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Chatbot-Token': this.token
                    }
                });
                
                const data = await response.json();
                let welcomeMessage = 'こんにちは！なんでもお聞きください！';
                
                if (data.venue_name) {
                    welcomeMessage = `こんにちは！${data.venue_name}についてなんでもお聞きください！`;
                }
                
                const messagesDiv = document.querySelector('.ai-chat-messages');
                const messageDiv = document.createElement('div');
                messageDiv.className = 'ai-message bot';
                messageDiv.innerHTML = this.formatMessage(welcomeMessage);
                messagesDiv.appendChild(messageDiv);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
                
                this.welcomeMessageShown = true;
                
            } catch (error) {
                console.error('Error fetching store info:', error);
                const messagesDiv = document.querySelector('.ai-chat-messages');
                const messageDiv = document.createElement('div');
                messageDiv.className = 'ai-message bot';
                messageDiv.innerHTML = this.formatMessage('こんにちは！なんでもお聞きください！');
                messagesDiv.appendChild(messageDiv);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
                
                this.welcomeMessageShown = true;
            }
        }
    }

    new AIChatbot();
})();
