(async function() {
    class AIChatbot {
        constructor() {
            this.token = this.getTokenFromScript();
            this.conversationId = 'conv_' + Math.random().toString(36).substr(2, 9);
            this.apiEndpoint = 'https://liveai.jp/api/chat/message';
            this.welcomeMessageShown = false; // ウェルカムメッセージが表示されたかどうかを追跡
            this.shadow = null; // Shadow DOM root
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
            this.createShadowRoot();
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

        createShadowRoot() {
            const container = document.createElement('div');
            container.id = 'ai-chatbot-container';
            document.body.appendChild(container);
            
            this.shadow = container.attachShadow({ mode: 'open' });
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
                }

                #ai-chat-resize-handle {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 20px;
                    height: 20px;
                    cursor: move;
                    z-index: 100;
                    background-color: transparent;
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
                    justify-content: flex-start;
                    gap: 12px;
                    background: white;
                }

                .ai-chat-input {
                    padding: 16px;
                    border-top: 1px solid #e2e8f0;
                    display: flex;
                    align-items: center;
                    background: white;
                }

                .ai-chat-input textarea {
                    flex: 1;
                    padding: 12px;
                    border: 1px solid #e2e8f0;
                    border-radius: 10px;
                    margin-right: 10px;
                    font-size: 16px;
                    outline: none;
                    transition: border-color 0.2s ease;
                    -webkit-text-size-adjust: 100%;
                    resize: none;
                    min-height: 40px;
                    max-height: 150px;
                    overflow-y: auto;
                    font-family: inherit;
                }

                .ai-chat-input textarea:focus {
                    border-color: #2563eb;
                }

                .ai-chat-input button {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    background: #000000;
                    color: #FFFFFF;
                    border: none;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 20px;
                    transition: background 0.2s ease;
                    flex-shrink: 0;
                }

                .ai-chat-input button:hover {
                    background: #333333;
                }

                .ai-chat-input button svg {
                    width: 20px;
                    height: 20px;
                    fill: #FFFFFF;
                }

                .ai-message {
                    padding: 12px 16px;
                    border-radius: 12px;
                    max-width: 85%;
                    line-height: 1.5;
                    font-size: 14px;
                    white-space: pre-wrap;
                    word-wrap: break-word;
                    text-align: left;
                    display: block;
                    position: relative;
                }

                .ai-message.user {
                    background: #f1f5f9;
                    color: black;
                    margin-left: auto;
                    margin-right: 0;
                    border-bottom-right-radius: 4px;
                    text-align: left;
                }

                .ai-message.bot {
                    background: #f8fafc;
                    color: #1f2937;
                    margin-right: auto;
                    margin-left: 0;
                    border-bottom-left-radius: 4px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                    border: 1px solid #e2e8f0;
                    text-align: left;
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

                    .ai-chat-input textarea {
                        font-size: 16px;
                        -webkit-text-size-adjust: 100%;
                    }
                }
            `;
            const style = document.createElement('style');
            style.textContent = css;
            this.shadow.appendChild(style);
        }

        createWidget() {
            const widget = document.createElement('div');
            widget.id = 'ai-chat-widget';

            widget.innerHTML = `
                <div id="ai-chat-window">
                    <div id="ai-chat-resize-handle"></div>
                    <div class="ai-chat-header">
                        <a href="https://liveai.jp" target="_blank" style="color: black; text-decoration: none; text-align: left; display: block;">liveAI</a>
                        <button id="ai-chat-close" style="position: absolute; top: 15px; right: 15px; background: none; border: none; cursor: pointer;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="ai-chat-messages"></div>
                    <div class="ai-chat-input">
                        <textarea placeholder="メッセージを入力してください..." rows="1"></textarea>
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 24 24">
                                <path d="M21.73 3.73L2.77 12.23c-.4.2-.24.74.22.74l7.78-.02L10.73 20.5c0 .5.55.7.83.3l10.14-16.22c.36-.52-.05-1.1-.67-.84z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <button id="ai-chat-button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </button>
            `;

            this.shadow.appendChild(widget);
        }

        addEventListeners() {
            const button = this.shadow.getElementById('ai-chat-button');
            const window = this.shadow.getElementById('ai-chat-window');
            const textarea = window.querySelector('textarea');
            const sendButton = window.querySelector('.ai-chat-input button');
            const closeButton = this.shadow.getElementById('ai-chat-close');

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

            const resizeTextarea = () => {
                textarea.style.height = 'auto';
                textarea.style.height = Math.min(textarea.scrollHeight, 150) + 'px';
            };

            textarea.addEventListener('input', resizeTextarea);

            const sendMessage = () => {
                const message = textarea.value.trim();
                if (message) {
                    this.sendMessage(message);
                    textarea.value = '';
                    textarea.style.height = 'auto';
                }
            };

            sendButton.addEventListener('click', sendMessage);
            textarea.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
        }

        decodeHtmlEntities(text) {
            const textarea = document.createElement('textarea');
            textarea.innerHTML = text;
            return textarea.value;
        }

        formatMessage(text) {
            text = this.decodeHtmlEntities(text);
            
            const containsHtmlTags = /<[a-z][\s\S]*>/i.test(text);
            
            if (containsHtmlTags) {
                let formatted = text.replace(/\n/g, '<br>');
                return formatted;
            }
            
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
                
                const urlRegex = /(https?:\/\/[^\s<>"']+)/g;
                line = line.replace(urlRegex, (match, url) => {
                    if (line.indexOf(`<a href="${url}"`) !== -1) {
                        return match;
                    }
                    
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

        typingCancelled = false;
        currentTypingPromise = null;

        parseTextSegments(text) {
            const segments = [];
            const decodedText = this.decodeHtmlEntities(text);
            
            const containsHtmlTags = /<[a-z][\s\S]*>/i.test(decodedText);
            
            if (containsHtmlTags) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = decodedText;
                
                const processNode = (node) => {
                    if (node.nodeType === Node.TEXT_NODE) {
                        if (node.textContent.trim()) {
                            segments.push({ type: 'text', content: node.textContent });
                        }
                    } else if (node.nodeType === Node.ELEMENT_NODE) {
                        if (node.tagName.toLowerCase() === 'a') {
                            segments.push({
                                type: 'link',
                                href: node.href,
                                content: node.textContent,
                                target: node.target || '_blank',
                                rel: node.rel || 'noopener noreferrer'
                            });
                        } else {
                            node.childNodes.forEach(processNode);
                        }
                    }
                };
                
                tempDiv.childNodes.forEach(processNode);
            } else {
                const urlRegex = /(https?:\/\/[^\s<>"']+)/g;
                let lastIndex = 0;
                let match;
                
                while ((match = urlRegex.exec(decodedText)) !== null) {
                    if (match.index > lastIndex) {
                        segments.push({
                            type: 'text',
                            content: decodedText.substring(lastIndex, match.index)
                        });
                    }
                    
                    segments.push({
                        type: 'link',
                        href: match[0],
                        content: match[0],
                        target: '_blank',
                        rel: 'noopener noreferrer'
                    });
                    
                    lastIndex = match.index + match[0].length;
                }
                
                if (lastIndex < decodedText.length) {
                    segments.push({
                        type: 'text',
                        content: decodedText.substring(lastIndex)
                    });
                }
            }
            
            return segments;
        }

        typeText(element, text, speed = 50) {
            this.typingCancelled = false;
            
            return new Promise((resolve) => {
                const segments = this.parseTextSegments(text);
                let segmentIndex = 0;
                let charIndex = 0;
                
                const type = () => {
                    if (this.typingCancelled) {
                        element.innerHTML = this.formatMessage(text);
                        return resolve();
                    }
                    
                    if (segmentIndex >= segments.length) {
                        return resolve();
                    }
                    
                    const segment = segments[segmentIndex];
                    
                    if (segment.type === 'text') {
                        if (charIndex < segment.content.length) {
                            element.append(segment.content.charAt(charIndex));
                            charIndex++;
                            setTimeout(type, speed);
                        } else {
                            segmentIndex++;
                            charIndex = 0;
                            type();
                        }
                    } else if (segment.type === 'link') {
                        const a = document.createElement('a');
                        a.href = segment.href;
                        a.target = segment.target;
                        a.rel = segment.rel;
                        a.textContent = segment.content;
                        element.append(a);
                        
                        segmentIndex++;
                        charIndex = 0;
                        setTimeout(type, speed);
                    }
                };
                
                type();
            });
        }

        cancelTyping() {
            this.typingCancelled = true;
        }

        addMessageToChat(message, isUser) {
            const messagesDiv = this.shadow.querySelector('.ai-chat-messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `ai-message ${isUser ? 'user' : 'bot'}`;

            if (isUser) {
                messageDiv.textContent = message;
            } else {
                messageDiv.innerHTML = '';
                this.currentTypingPromise = this.typeText(messageDiv, message).then(() => {
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                });
            }

            messagesDiv.appendChild(messageDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        async sendMessage(message) {
            this.cancelTyping();
            
            this.addMessageToChat(message, true);

            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'ai-message bot loading';
            loadingDiv.innerHTML = '<span></span><span></span><span></span>';

            const messagesDiv = this.shadow.querySelector('.ai-chat-messages');
            messagesDiv.appendChild(loadingDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

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
                
                if (error.message && error.message.includes('リクエスト回数制限を超えました')) {
                    this.addMessageToChat('リクエスト回数制限を超えました。しばらくしてから再度お試しください。', false);
                } else {
                    this.addMessageToChat('Sorry, I encountered an error. Please try again later.', false);
                }
            }
        }

        setupResizing() {
            const chatWindow = this.shadow.getElementById('ai-chat-window');
            const dragHandle = this.shadow.getElementById('ai-chat-resize-handle');
            let isDragging = false;
            let initialX, initialY, initialLeft, initialTop;

            dragHandle.addEventListener('mousedown', (e) => {
                e.preventDefault();
                isDragging = true;

                initialX = e.clientX;
                initialY = e.clientY;

                const rect = chatWindow.getBoundingClientRect();
                initialLeft = rect.left;
                initialTop = rect.top;

                chatWindow.classList.add('dragging');

                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });

            const onMouseMove = (e) => {
                if (!isDragging) return;

                const dx = e.clientX - initialX;
                const dy = e.clientY - initialY;
                
                chatWindow.style.left = `${initialLeft + dx}px`;
                chatWindow.style.top = `${initialTop + dy}px`;
                
                chatWindow.style.right = 'auto';
                chatWindow.style.bottom = 'auto';
            };

            const onMouseUp = () => {
                isDragging = false;
                chatWindow.classList.remove('dragging');

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
                
                const messagesDiv = this.shadow.querySelector('.ai-chat-messages');
                const messageDiv = document.createElement('div');
                messageDiv.className = 'ai-message bot';
                messageDiv.innerHTML = this.formatMessage(welcomeMessage);
                messagesDiv.appendChild(messageDiv);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
                
                this.welcomeMessageShown = true;
                
            } catch (error) {
                console.error('Error fetching store info:', error);
                const messagesDiv = this.shadow.querySelector('.ai-chat-messages');
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
