@section('title', 'Bot Test')
<x-sidebar>
    <style>
        /* Keep only necessary animations that can't be done with Tailwind */
        .typing-indicator span {
            animation: bounce 1.4s infinite ease-in-out;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: scale(0);
            }

            40% {
                transform: scale(1);
            }
        }
    </style>

    <div class="!h-[80vh] bg-blue-50  rounded-2xl">
        <div class="max-w-7xl mx-auto">
            <div class="bg-[#E9F2FF] rounded-xl h-[80vh] flex flex-col px-2 sm:px-6 lg:px-8 pt-3 sm:pt-6 pb-10 sm:pb-3">
                <!-- Header -->
                <div class="p-4">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="hover:opacity-75 transition-opacity">
                            <svg width="24" height="14" viewBox="0 0 24 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8 14C8 13.258 7.267 12.15 6.525 11.22C5.571 10.02 4.431 8.973 3.124 8.174C2.144 7.575 0.956 7 -3.0598e-07 7M-3.0598e-07 7C0.956 7 2.145 6.425 3.124 5.826C4.431 5.026 5.571 3.979 6.525 2.781C7.267 1.85 8 0.74 8 -3.49691e-07M-3.0598e-07 7L24 7"
                                    stroke="black" stroke-width="2" />
                            </svg>
                        </a>
                        <svg width="40" height="40" viewBox="0 0 38 48" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M32.6561 28.4995C35.6074 28.4995 38 30.8921 38 33.8434V35.9931C38 38.5906 36.866 41.0587 34.8951 42.7508C31.1771 45.9428 25.8561 47.5028 18.9922 47.5028C12.1294 47.5028 6.81205 45.9433 3.10013 42.752C1.13209 41.06 0 38.5938 0 35.9984V33.8434C0 30.8921 2.39255 28.4995 5.34391 28.4995H32.6561ZM18.7512 0.0162613L18.993 0C19.8948 0 20.64 0.670136 20.758 1.53959L20.7743 1.7813L20.7724 3.56023L29.0862 3.56144C32.0376 3.56144 34.4301 5.95399 34.4301 8.90535V19.6041C34.4301 22.5554 32.0376 24.948 29.0862 24.948H8.89811C5.94675 24.948 3.5542 22.5554 3.5542 19.6041V8.90535C3.5542 5.95399 5.94675 3.56144 8.89811 3.56144L17.2098 3.56023L17.2117 1.7813C17.2117 0.879499 17.8818 0.134213 18.7512 0.0162613ZM13.6466 10.6867C12.0079 10.6867 10.6794 12.0151 10.6794 13.6538C10.6794 15.2925 12.0079 16.621 13.6466 16.621C15.2853 16.621 16.6137 15.2925 16.6137 13.6538C16.6137 12.0151 15.2853 10.6867 13.6466 10.6867ZM24.3171 10.6867C22.6784 10.6867 21.35 12.0151 21.35 13.6538C21.35 15.2925 22.6784 16.621 24.3171 16.621C25.9559 16.621 27.2843 15.2925 27.2843 13.6538C27.2843 12.0151 25.9559 10.6867 24.3171 10.6867Z"
                                fill="#173F74" />
                        </svg>
                        <span class="text-2xl font-semibold">Bot Test</span>
                    </div>
                </div>

                <!-- Messages Container -->
                <div class="flex-1 overflow-y-auto px-4 py-6" id="message-list">
                    <!-- Messages will be added here -->
                </div>

                <!-- Input Container - Fixed at bottom -->
                <div class="p-4">
                    <div class="relative flex items-center max-w-full">
                        <input type="text"
                            class="w-full py-3 px-4 pr-12 rounded-lg border border-[#344EAB] bg-[#D0E4FF] focus:outline-none focus:ring-2 focus:ring-[#344EAB] focus:border-transparent"
                            placeholder="Write a message..." id="message" onkeypress="handleKeyPress(event)">
                        <button class="absolute right-3 p-2 hover:opacity-75 transition-opacity focus:outline-none"
                            id="send-message">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                width="24px" fill="#173F74">
                                <path
                                    d="M120-160v-640l760 320-760 320Zm80-120 474-200-474-200v140l240 60-240 60v140Zm0 0v-400 400Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            function scrollToBottom() {
                const messageList = document.getElementById('message-list');
                messageList.scrollTop = messageList.scrollHeight;
            }

            function handleKeyPress(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    document.getElementById('send-message').click();
                }
            }

            function createMessageElement(message, isUser = true) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `flex items-start space-x-2 mb-4 ${isUser ? '' : 'flex-row-reverse space-x-reverse'}`;
                const formatDate = () => {
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                    const day = String(now.getDate()).padStart(2, '0');
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');

                    return `${year}-${month}-${day} ${hours}:${minutes}`;
                };

                const timestamp = formatDate();

                messageDiv.innerHTML = `
                <div class="flex-shrink-0 w-10">
                    ${isUser ? getUserAvatar() : getBotAvatar()}
                </div>
                <div class="flex flex-col max-w-[75%]">
                    <div class="bg-[#D0E4FF] border border-[#344EAB] p-4 rounded-lg shadow-sm">
                        <p class="text-base sm:text-lg font-medium mb-2">${formatMessage(message)}</p>
                        <span class="text-xs text-gray-500">${timestamp}</span>
                    </div>
                </div>
            `;
                return messageDiv;
            }

            function formatMessage(text) {
                // Convert line breaks to paragraphs
                let formatted = text.split('\n').map(line => line.trim()).filter(line => line).map(line => `<p>${line}</p>`)
                    .join('');

                // Format code blocks
                formatted = formatted.replace(/```([\s\S]*?)```/g, '<pre><code>$1</code></pre>');

                // Format inline code
                formatted = formatted.replace(/`([^`]+)`/g, '<code>$1</code>');

        // Format lists
        formatted = formatted.replace(/^\s*[-*]\s+(.+)/gm, '<li>$1</li>');
        formatted = formatted.replace(/(<li>.*?<\/li>\s*)+/g, '<ul>$&</ul>');

        return formatted;
    }

    function getUserAvatar() {
        return ` <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4" d="M12 22.01C17.5228 22.01 22 17.5329 22 12.01C22 6.48716 17.5228 2.01001 12 2.01001C6.47715 2.01001 2 6.48716 2 12.01C2 17.5329 6.47715 22.01 12 22.01Z" fill="#FFFFFF" />
                                        <path d="M12 6.93994C9.93 6.93994 8.25 8.61994 8.25 10.6899C8.25 12.7199 9.84 14.3699 11.95 14.4299C11.98 14.4299 12.02 14.4299 12.04 14.4299C12.06 14.4299 12.09 14.4299 12.11 14.4299C12.12 14.4299 12.13 14.4299 12.13 14.4299C14.15 14.3599 15.74 12.7199 15.75 10.6899C15.75 8.61994 14.07 6.93994 12 6.93994Z" fill="#173F74" />
                                        <path d="M18.7807 19.36C17.0007 21 14.6207 22.01 12.0007 22.01C9.3807 22.01 7.0007 21 5.2207 19.36C5.4607 18.45 6.1107 17.62 7.0607 16.98C9.7907 15.16 14.2307 15.16 16.9407 16.98C17.9007 17.62 18.5407 18.45 18.7807 19.36Z" fill="#173F74" />
                                    </svg>`;
    }

    function getBotAvatar() {
        return `<svg width="40" height="40" viewBox="0 0 38 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M32.6561 28.4995C35.6074 28.4995 38 30.8921 38 33.8434V35.9931C38 38.5906 36.866 41.0587 34.8951 42.7508C31.1771 45.9428 25.8561 47.5028 18.9922 47.5028C12.1294 47.5028 6.81205 45.9433 3.10013 42.752C1.13209 41.06 0 38.5938 0 35.9984V33.8434C0 30.8921 2.39255 28.4995 5.34391 28.4995H32.6561ZM18.7512 0.0162613L18.993 0C19.8948 0 20.64 0.670136 20.758 1.53959L20.7743 1.7813L20.7724 3.56023L29.0862 3.56144C32.0376 3.56144 34.4301 5.95399 34.4301 8.90535V19.6041C34.4301 22.5554 32.0376 24.948 29.0862 24.948H8.89811C5.94675 24.948 3.5542 22.5554 3.5542 19.6041V8.90535C3.5542 5.95399 5.94675 3.56144 8.89811 3.56144L17.2098 3.56023L17.2117 1.7813C17.2117 0.879499 17.8818 0.134213 18.7512 0.0162613ZM13.6466 10.6867C12.0079 10.6867 10.6794 12.0151 10.6794 13.6538C10.6794 15.2925 12.0079 16.621 13.6466 16.621C15.2853 16.621 16.6137 15.2925 16.6137 13.6538C16.6137 12.0151 15.2853 10.6867 13.6466 10.6867ZM24.3171 10.6867C22.6784 10.6867 21.35 12.0151 21.35 13.6538C21.35 15.2925 22.6784 16.621 24.3171 16.621C25.9559 16.621 27.2843 15.2925 27.2843 13.6538C27.2843 12.0151 25.9559 10.6867 24.3171 10.6867Z" fill="#173F74" />
                                </svg>`;
    }

    $('#send-message').click(function(e) {
        e.preventDefault();
        const messageInput = $('#message');
        const message = messageInput.val().trim();

        if (!message) return;

        const messageList = document.getElementById('message-list');
        messageList.appendChild(createMessageElement(message, true));
        messageInput.val('');
        scrollToBottom();

        const typingDiv = document.createElement('div');
        typingDiv.className = 'typing-indicator flex items-center space-x-2 mb-4 float-right';
        typingDiv.innerHTML = `
                        <div class="bg-[#D0E4FF] rounded-lg p-4 flex items-center space-x-2">
                            <span class="w-2 h-2 bg-[#344EAB] rounded-full"></span>
                            <span class="w-2 h-2 bg-[#344EAB] rounded-full"></span>
                            <span class="w-2 h-2 bg-[#344EAB] rounded-full"></span>
                        </div>
                        ${getBotAvatar()}
                    `;
                messageList.appendChild(typingDiv);
                scrollToBottom();

                $.ajax({
                    type: "POST",
                    url: "{{ route('chat.message') }}",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        message,
                        // is_test: true  // テストを履歴に残さない
                    },
                    success: function(response) {
                        typingDiv.remove();
                        messageList.appendChild(createMessageElement(response.response, false));
                        scrollToBottom();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        typingDiv.remove();
                        const errorMessage = createMessageElement(
                            "Sorry, there was an error. Please try again.", false);
                        messageList.appendChild(errorMessage);
                        scrollToBottom();
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', scrollToBottom);
        </script>
    @endpush
</x-sidebar>
