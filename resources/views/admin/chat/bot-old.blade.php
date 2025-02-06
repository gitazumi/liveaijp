@section('title', 'Bot Test')
<x-app-layout>
    <style>
        .typing-indicator {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .typing-indicator span {
            width: 8px;
            height: 8px;
            margin: 0 4px;
            background-color: #344EAB;
            border-radius: 50%;
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


    <div class="h-screen flex justify-center">
        <div class="h-[90%] w-full rounded-xl p-[30px] sm:p-[50px] bg-[#E9F2FF]">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}">
                    <svg width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8 14C8 13.258 7.267 12.15 6.525 11.22C5.571 10.02 4.431 8.973 3.124 8.174C2.144 7.575 0.956 7 -3.0598e-07 7M-3.0598e-07 7C0.956 7 2.145 6.425 3.124 5.826C4.431 5.026 5.571 3.979 6.525 2.781C7.267 1.85 8 0.74 8 -3.49691e-07M-3.0598e-07 7L24 7"
                            stroke="black" stroke-width="2" />
                    </svg>
                </a>
                <svg width="36" height="44" class="mx-5" viewBox="0 0 24 24" version="1.1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>ic_fluent_bot_24_filled</title>
                    <desc>Created with Sketch.</desc>
                    <g id="🔍-Product-Icons" stroke="none" stroke-width="1" fill="currentColor" fill-rule="evenodd">
                        <g id="ic_fluent_bot_24_filled" fill="currentColor" fill-rule="nonzero">
                            <path
                                d="M17.7530511,13.999921 C18.9956918,13.999921 20.0030511,15.0072804 20.0030511,16.249921 L20.0030511,17.1550008 C20.0030511,18.2486786 19.5255957,19.2878579 18.6957793,20.0002733 C17.1303315,21.344244 14.8899962,22.0010712 12,22.0010712 C9.11050247,22.0010712 6.87168436,21.3444691 5.30881727,20.0007885 C4.48019625,19.2883988 4.00354153,18.2500002 4.00354153,17.1572408 L4.00354153,16.249921 C4.00354153,15.0072804 5.01090084,13.999921 6.25354153,13.999921 L17.7530511,13.999921 Z M11.8985607,2.00734093 L12.0003312,2.00049432 C12.380027,2.00049432 12.6938222,2.2826482 12.7434846,2.64872376 L12.7503312,2.75049432 L12.7495415,3.49949432 L16.25,3.5 C17.4926407,3.5 18.5,4.50735931 18.5,5.75 L18.5,10.254591 C18.5,11.4972317 17.4926407,12.504591 16.25,12.504591 L7.75,12.504591 C6.50735931,12.504591 5.5,11.4972317 5.5,10.254591 L5.5,5.75 C5.5,4.50735931 6.50735931,3.5 7.75,3.5 L11.2495415,3.49949432 L11.2503312,2.75049432 C11.2503312,2.37079855 11.5324851,2.05700336 11.8985607,2.00734093 L12.0003312,2.00049432 L11.8985607,2.00734093 Z M9.74928905,6.5 C9.05932576,6.5 8.5,7.05932576 8.5,7.74928905 C8.5,8.43925235 9.05932576,8.99857811 9.74928905,8.99857811 C10.4392523,8.99857811 10.9985781,8.43925235 10.9985781,7.74928905 C10.9985781,7.05932576 10.4392523,6.5 9.74928905,6.5 Z M14.2420255,6.5 C13.5520622,6.5 12.9927364,7.05932576 12.9927364,7.74928905 C12.9927364,8.43925235 13.5520622,8.99857811 14.2420255,8.99857811 C14.9319888,8.99857811 15.4913145,8.43925235 15.4913145,7.74928905 C15.4913145,7.05932576 14.9319888,6.5 14.2420255,6.5 Z"
                                id="🎨-Color">

                            </path>
                        </g>
                    </g>
                </svg>



                <span class="text-[28.95px] font-semibold">
                    Bot Test
                </span>
            </div>
            <div class="h-[95%] overflow-y-auto relative">
                <div class="max-h-[90%] sm:max-h-[90%] overflow-y-auto w-full">
                    {{-- chats --}}
                    <div class="max-h-[85%] overflow-y-auto p-3" id="message-list">

                    </div>
                </div>
                <div class="absolute bottom-0 w-full">
                    <div class="relative flex items-center">
                        <input type="text"
                            class="w-full border bg-[#D0E4FF] rounded border-[#344EAB] min-h-fit pr-[20px]"
                            placeholder="Write a message..." id="message">
                        <button class="absolute right-[10px]" id="send-message">
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
            $('#send-message').click(function(e) {
                e.preventDefault();
                console.log('clicked worked');

                let message = $('#message').val();

                // Create a new message div for the user's message
                let messageDiv = document.createElement('div');
                messageDiv.innerHTML = `
                    <div class="flex mb-5">
                        <div class="mr-2">
                            <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.4" d="M12 22.01C17.5228 22.01 22 17.5329 22 12.01C22 6.48716 17.5228 2.01001 12 2.01001C6.47715 2.01001 2 6.48716 2 12.01C2 17.5329 6.47715 22.01 12 22.01Z" fill="#FFFFFF" />
                                <path d="M12 6.93994C9.93 6.93994 8.25 8.61994 8.25 10.6899C8.25 12.7199 9.84 14.3699 11.95 14.4299C11.98 14.4299 12.02 14.4299 12.04 14.4299C12.06 14.4299 12.09 14.4299 12.11 14.4299C12.12 14.4299 12.13 14.4299 12.13 14.4299C14.15 14.3599 15.74 12.7199 15.75 10.6899C15.75 8.61994 14.07 6.93994 12 6.93994Z" fill="#173F74" />
                                <path d="M18.7807 19.36C17.0007 21 14.6207 22.01 12.0007 22.01C9.3807 22.01 7.0007 21 5.2207 19.36C5.4607 18.45 6.1107 17.62 7.0607 16.98C9.7907 15.16 14.2307 15.16 16.9407 16.98C17.9007 17.62 18.5407 18.45 18.7807 19.36Z" fill="#173F74" />
                            </svg>
                        </div>
                        <div class="bg-[#D0E4FF] border border-[#344EAB] p-1 px-3 rounded relative min-h-[70px] w-full">
                            <p class="text-[16px] sm:text-[20px] font-semibold mb-5">${message}</p>
                            <small class="text-16px absolute bottom-[2px]">2025-01-15 14:30</small>
                        </div>
                    </div>
                `;
                $('#message-list').append(messageDiv);
                $('#message').val(''); // Clear the input field
                scrollToBottom();

                // Show bot's typing indicator
                let botTypingDiv = document.createElement('div');
                botTypingDiv.className = 'flex mb-5 bot-typing';
                botTypingDiv.innerHTML = `
                    <div class="bg-[#D0E4FF] border border-[#344EAB] p-1 px-3 rounded relative min-h-[70px] w-full">
                        <p class="text-[16px] sm:text-[20px] font-semibold mb-5">Typing...</p>
                    </div>
                    <div class="ml-2">
                        <svg width="40" height="40" viewBox="0 0 38 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M32.6561 28.4995C35.6074 28.4995 38 30.8921 38 33.8434V35.9931C38 38.5906 36.866 41.0587 34.8951 42.7508C31.1771 45.9428 25.8561 47.5028 18.9922 47.5028C12.1294 47.5028 6.81205 45.9433 3.10013 42.752C1.13209 41.06 0 38.5938 0 35.9984V33.8434C0 30.8921 2.39255 28.4995 5.34391 28.4995H32.6561ZM18.7512 0.0162613L18.993 0C19.8948 0 20.64 0.670136 20.758 1.53959L20.7743 1.7813L20.7724 3.56023L29.0862 3.56144C32.0376 3.56144 34.4301 5.95399 34.4301 8.90535V19.6041C34.4301 22.5554 32.0376 24.948 29.0862 24.948H8.89811C5.94675 24.948 3.5542 22.5554 3.5542 19.6041V8.90535C3.5542 5.95399 5.94675 3.56144 8.89811 3.56144L17.2098 3.56023L17.2117 1.7813C17.2117 0.879499 17.8818 0.134213 18.7512 0.0162613ZM13.6466 10.6867C12.0079 10.6867 10.6794 12.0151 10.6794 13.6538C10.6794 15.2925 12.0079 16.621 13.6466 16.621C15.2853 16.621 16.6137 15.2925 16.6137 13.6538C16.6137 12.0151 15.2853 10.6867 13.6466 10.6867ZM24.3171 10.6867C22.6784 10.6867 21.35 12.0151 21.35 13.6538C21.35 15.2925 22.6784 16.621 24.3171 16.621C25.9559 16.621 27.2843 15.2925 27.2843 13.6538C27.2843 12.0151 25.9559 10.6867 24.3171 10.6867Z" fill="#173F74" />
                        </svg>
                    </div>
                `;
                $('#message-list').append(botTypingDiv);
                $("#message-list").animate({ scrollTop: 0 }, "slow");
                scrollToBottom();

                // Send the message via AJAX
                $.ajax({
                    type: "POST",
                    url: "{{ route('chat.message') }}",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}" // Include CSRF token
                    },
                    data: {
                        message: message
                    },
                    success: function(response) {
                        console.log("Message sent successfully:", response);

                        // Replace typing indicator with the bot's response
                        botTypingDiv.remove();

                        let responseDiv = document.createElement('div');
                        responseDiv.innerHTML = `
                        <div class="flex mb-5">
                            <div class="bg-[#D0E4FF] border border-[#344EAB] p-1 px-3 rounded relative min-h-[70px] w-full">
                                <p class="text-[16px] sm:text-[20px] font-semibold mb-5">${response.response}</p>
                            </div>
                            <div class="ml-2">
                                <svg width="40" height="40" viewBox="0 0 38 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M32.6561 28.4995C35.6074 28.4995 38 30.8921 38 33.8434V35.9931C38 38.5906 36.866 41.0587 34.8951 42.7508C31.1771 45.9428 25.8561 47.5028 18.9922 47.5028C12.1294 47.5028 6.81205 45.9433 3.10013 42.752C1.13209 41.06 0 38.5938 0 35.9984V33.8434C0 30.8921 2.39255 28.4995 5.34391 28.4995H32.6561ZM18.7512 0.0162613L18.993 0C19.8948 0 20.64 0.670136 20.758 1.53959L20.7743 1.7813L20.7724 3.56023L29.0862 3.56144C32.0376 3.56144 34.4301 5.95399 34.4301 8.90535V19.6041C34.4301 22.5554 32.0376 24.948 29.0862 24.948H8.89811C5.94675 24.948 3.5542 22.5554 3.5542 19.6041V8.90535C3.5542 5.95399 5.94675 3.56144 8.89811 3.56144L17.2098 3.56023L17.2117 1.7813C17.2117 0.879499 17.8818 0.134213 18.7512 0.0162613ZM13.6466 10.6867C12.0079 10.6867 10.6794 12.0151 10.6794 13.6538C10.6794 15.2925 12.0079 16.621 13.6466 16.621C15.2853 16.621 16.6137 15.2925 16.6137 13.6538C16.6137 12.0151 15.2853 10.6867 13.6466 10.6867ZM24.3171 10.6867C22.6784 10.6867 21.35 12.0151 21.35 13.6538C21.35 15.2925 22.6784 16.621 24.3171 16.621C25.9559 16.621 27.2843 15.2925 27.2843 13.6538C27.2843 12.0151 25.9559 10.6867 24.3171 10.6867Z" fill="#173F74" />
                                </svg>
                            </div>
                        </div>
                    `;
                        $('#message-list').append(responseDiv);
                        scrollToBottom();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error sending message:", error);
                        botTypingDiv.remove(); // Remove typing indicator on error
                    }
                });
            });
            function scrollToBottom() {
                let chatBox = document.getElementById('message-list');
                if (chatBox) {
                    // Delay the scroll to ensure DOM updates
                    setTimeout(() => {
                        chatBox.scrollTop = chatBox.scrollHeight;
                        console.log("scrollTop:", chatBox.scrollTop);
                        console.log("scrollHeight:", chatBox.scrollHeight);
                    }, 100); // Adjust delay if necessary
                } else {
                    console.error("Chatbox not found");
                }
            }
        </script>
    @endpush
</x-app-layout>
