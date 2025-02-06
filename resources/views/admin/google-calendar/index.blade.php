@section('title', ' FAQ Training')
<x-app-layout>
    <div class="flex justify-center">
        <div class="w-full rounded-xl p-[30px] sm:p-[50px] bg-[#E9F2FF]">
            <div class="flex items-center mb-5">
                <a href="{{ route('dashboard') }}">
                    <svg width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8 14C8 13.258 7.267 12.15 6.525 11.22C5.571 10.02 4.431 8.973 3.124 8.174C2.144 7.575 0.956 7 -3.0598e-07 7M-3.0598e-07 7C0.956 7 2.145 6.425 3.124 5.826C4.431 5.026 5.571 3.979 6.525 2.781C7.267 1.85 8 0.74 8 -3.49691e-07M-3.0598e-07 7L24 7"
                            stroke="black" stroke-width="2" />
                    </svg>
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"
                    fill="currentColor" class="mx-2 sm:mx-5">
                    <path
                        d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
                </svg>


                <span class="text-[20px] sm:text-[28.95px] font-semibold">
                    Google Calendar Sync
                </span>
            </div>
            <span class="teext-red-500">
                {{ session('error') }}
            </span>

            <form action="{{ route('google-calendar.update') }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $data->id ?? '' }}">
                <div class="mb-3">
                    <label for="" class="text-[20px] font-medium">
                        Client ID
                    </label>
                    <input type="text" name="client_id" id="client_id" value="{{ $data->client_id ?? '' }}"
                        class="w-full block rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] mt-1">
                    @error('client_id')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="" class="text-[20px] font-medium">
                        Client Secret Key
                    </label>
                    <input type="text" name="client_secret" id="client_secret"
                        value="{{ $data->client_secret ?? '' }}"
                        class="w-full block rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] mt-1">
                    @error('client_secret')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="" class="text-[20px] font-medium">
                        Google Calendar ID
                    </label>
                    <input type="text" name="calendar_id" id="calendar_id" value="{{ $data->calendar_id ?? '' }}"
                        class="w-full block rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] mt-1">
                    @error('calendar_id')
                        {{ $message }}
                    @enderror
                </div>

                <x-primary-button class="!px-5 float-right">
                    {{ __('Connect With Google Calendar') }}
                </x-primary-button>
                <br>
                <div class="mt-20">
                    <div class="sm:flex justify-between items-center">
                        <label class="text-[20px] font-medium" for="">
                            Authorised JavaScript origins
                        </label>
                        <div data-id="url"
                            class="max-w-fit copy-button cursor-pointer px-4 py-2 rounded bg-[#173F74] hover:bg-[#344EAF] text-white mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>

                        </div>
                    </div>
                    <div id="url"
                        class="w-full p-2 block border rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] mt-1">
                        https://sty1.devmail-sty.online
                    </div>
                </div>
                <div class="mt-3">
                    <div class="sm:flex justify-between items-center">
                        <label class="text-[20px] font-medium" for="">
                            Authorised redirect URIs
                        </label>
                        <div data-id="callback-url"
                            class="max-w-fit copy-button cursor-pointer px-4 py-2 rounded bg-[#173F74] hover:bg-[#344EAF] text-white mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>

                        </div>
                    </div>
                    <div class="relative flex flex-wrap items-center">
                        <div id="callback-url"
                            class="flex-grow p-2 block border rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] mt-1">
                            {{ route('google-calendar.callback') }}
                        </div>
                    </div>





                </div>
            </form>

        </div>

    </div>

    @push('script')
        <script>
            $('.copy-button').click(function(e) {
                e.preventDefault();

                let id = $(this).data('id');
                // Trim the text to remove extra spaces
                const textToCopy = $(`#${id}`).text().trim();
                const tempInput = $('<textarea>');
                tempInput.val(textToCopy);
                $('body').append(tempInput);
                tempInput.select();
                document.execCommand('copy');
                tempInput.remove();
                $(this).html(
                    `copied`
                );
            });
        </script>
    @endpush
</x-app-layout>
