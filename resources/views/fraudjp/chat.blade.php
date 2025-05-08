<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fraud.jp - 詐欺情報監視プラットフォーム</title>
    <meta name="description" content="詐欺かも？と思ったらすぐ相談できるAI駆動型の情報監視・共有プラットフォーム">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Hiragino Sans', 'Hiragino Kaku Gothic ProN', Meiryo, sans-serif;
            background-color: #f9fafb;
        }
        .chat-container {
            height: calc(100vh - 180px);
            overflow-y: auto;
        }
        .user-message {
            background-color: #e5e7eb;
            border-radius: 18px 18px 0 18px;
            max-width: 80%;
            margin-left: auto;
            padding: 12px 16px;
            margin-bottom: 16px;
        }
        .ai-message {
            background-color: #ffffff;
            border-radius: 18px 18px 18px 0;
            max-width: 80%;
            padding: 12px 16px;
            margin-bottom: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .loading-indicator {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }
        .loading-dots {
            display: flex;
        }
        .loading-dots div {
            width: 8px;
            height: 8px;
            margin: 0 2px;
            background-color: #9ca3af;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }
        .loading-dots div:nth-child(1) {
            animation-delay: -0.32s;
        }
        .loading-dots div:nth-child(2) {
            animation-delay: -0.16s;
        }
        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }
        .input-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px;
            background-color: #ffffff;
            border-top: 1px solid #e5e7eb;
        }
        .input-box {
            display: flex;
            background-color: #f3f4f6;
            border-radius: 24px;
            padding: 8px 16px;
        }
        .input-box textarea {
            flex-grow: 1;
            border: none;
            background-color: transparent;
            resize: none;
            outline: none;
            max-height: 100px;
            overflow-y: auto;
        }
        .send-button {
            background-color: #3b82f6;
            color: white;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            margin-left: 8px;
            align-self: flex-end;
        }
        .send-button.disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
        }
        a {
            color: #3b82f6;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-bold text-gray-900">fraud.jp - 詐欺情報監視プラットフォーム</h1>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <div class="chat-container" id="chatContainer">
            <!-- チャットメッセージがここに表示されます -->
        </div>

        <div class="input-container">
            <div class="input-box">
                <textarea id="messageInput" placeholder="メッセージを入力してください..." rows="1"></textarea>
                <div id="sendButton" class="send-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatContainer = document.getElementById('chatContainer');
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.getElementById('sendButton');
            
            let sessionId = localStorage.getItem('fraudjp_session_id') || null;
            let isProcessing = false;
            
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
                if (this.scrollHeight > 100) {
                    this.style.overflowY = 'auto';
                } else {
                    this.style.overflowY = 'hidden';
                }
            });
            
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
            
            sendButton.addEventListener('click', sendMessage);
            
            loadChatHistory();
            
            function sendMessage() {
                if (isProcessing || !messageInput.value.trim()) return;
                
                const message = messageInput.value.trim();
                addMessageToChat(message, true);
                
                messageInput.value = '';
                messageInput.style.height = 'auto';
                
                isProcessing = true;
                sendButton.classList.add('disabled');
                
                showLoadingIndicator();
                
                fetch('/api/fraudjp/message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: message,
                        session_id: sessionId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    removeLoadingIndicator();
                    
                    if (data.error) {
                        addMessageToChat('申し訳ありませんが、エラーが発生しました。しばらくしてからもう一度お試しください。', false);
                    } else {
                        addMessageToChat(data.message, false);
                        
                        if (data.session_id) {
                            sessionId = data.session_id;
                            localStorage.setItem('fraudjp_session_id', sessionId);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    removeLoadingIndicator();
                    addMessageToChat('申し訳ありませんが、エラーが発生しました。しばらくしてからもう一度お試しください。', false);
                })
                .finally(() => {
                    isProcessing = false;
                    sendButton.classList.remove('disabled');
                });
            }
            
            function loadChatHistory() {
                if (!sessionId) return;
                
                fetch(`/api/fraudjp/history?session_id=${sessionId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.messages && data.messages.length > 0) {
                            data.messages.forEach(msg => {
                                addMessageToChat(msg.message, msg.is_user);
                            });
                            scrollToBottom();
                        } else {
                            addMessageToChat('こんにちは！詐欺の可能性がある情報についてご相談ください。電話番号、URL、SMSの内容など、気になる情報をお知らせいただければ調査いたします。', false);
                        }
                    })
                    .catch(error => {
                        console.error('Error loading chat history:', error);
                    });
            }
            
            function addMessageToChat(message, isUser) {
                const messageElement = document.createElement('div');
                messageElement.className = isUser ? 'user-message' : 'ai-message';
                
                const processedMessage = processMarkdown(message);
                messageElement.innerHTML = processedMessage;
                
                chatContainer.appendChild(messageElement);
                scrollToBottom();
            }
            
            function processMarkdown(text) {
                text = text.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>');
                
                text = text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>');
                
                text = text.replace(/^- (.+)$/gm, '<li>$1</li>').replace(/<li>(.+)<\/li>/g, '<ul><li>$1</li></ul>');
                
                text = text.replace(/^> (.+)$/gm, '<blockquote>$1</blockquote>');
                
                text = text.replace(/\n/g, '<br>');
                
                return text;
            }
            
            function showLoadingIndicator() {
                const loadingElement = document.createElement('div');
                loadingElement.className = 'loading-indicator';
                loadingElement.id = 'loadingIndicator';
                
                const loadingText = document.createElement('div');
                loadingText.className = 'text-gray-500 mr-2';
                loadingText.textContent = 'データベース検索中...';
                
                const loadingDots = document.createElement('div');
                loadingDots.className = 'loading-dots';
                
                for (let i = 0; i < 3; i++) {
                    const dot = document.createElement('div');
                    loadingDots.appendChild(dot);
                }
                
                loadingElement.appendChild(loadingText);
                loadingElement.appendChild(loadingDots);
                chatContainer.appendChild(loadingElement);
                scrollToBottom();
            }
            
            function removeLoadingIndicator() {
                const loadingElement = document.getElementById('loadingIndicator');
                if (loadingElement) {
                    loadingElement.remove();
                }
            }
            
            function scrollToBottom() {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
            
            if (!sessionId) {
                addMessageToChat('こんにちは！詐欺の可能性がある情報についてご相談ください。電話番号、URL、SMSの内容など、気になる情報をお知らせいただければ調査いたします。', false);
            }
        });
    </script>
</body>
</html>
