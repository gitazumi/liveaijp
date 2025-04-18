@section('title', 'FAQトレーニング')
<x-sidebar>
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
                <svg width="35" height="27" class="mx-2 sm:mx-5" viewBox="0 -19.5 164 164" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19.2329 89.0831C17.3341 89.4211 15.7432 89.7559 14.1371 89.9817C7.06966 90.976 1.51901 86.5687 0.48068 79.5288C-1.0289 69.307 6.73229 58.1139 14.141 55.0389C16.6482 53.9986 19.5794 53.9795 23.0364 53.3665C32.2494 32.1615 49.7618 21.7934 73.5423 20.3488C73.8921 16.4462 74.238 12.5935 74.6022 8.54059C73.5751 8.11988 72.3431 7.95977 71.6796 7.26077C70.7134 6.24344 69.5996 4.84016 69.5957 3.59771C69.5918 2.53116 70.9221 0.709891 71.8974 0.535306C74.597 0.0535535 77.542 -0.276629 80.1608 0.325233C83.5048 1.0938 83.9852 3.75262 81.8548 6.48561C81.4171 6.9389 81.1341 7.51899 81.0462 8.14288C81.224 11.6156 81.5273 15.081 81.7616 18.179C88.0211 18.7375 94.0055 19.0381 99.9211 19.8421C119.273 22.472 132.088 33.3508 139.077 51.3896C139.194 51.6909 139.333 51.9849 139.478 52.2744C139.549 52.3747 139.633 52.4656 139.727 52.5448C142.943 52.5448 146.247 52.1103 149.393 52.6347C156.138 53.7583 161.178 57.4004 162.853 64.3477C164.528 71.2951 161.862 77.0616 156.759 81.6435C151.742 86.1493 145.621 87.389 138.993 86.5404C138.746 86.7453 138.532 86.987 138.359 87.2571C130.949 104.691 117.203 114.915 99.7662 120.658C84.6227 125.684 68.3154 126.026 52.9746 121.639C36.0424 116.958 23.8017 107.182 19.2329 89.0831ZM74.3653 116.033C77.9548 115.728 81.5686 115.59 85.1292 115.09C99.4118 113.083 112.05 107.628 121.744 96.6153C138.759 77.2881 134.524 42.1123 104.846 32.3558C93.8566 28.746 82.3857 26.5243 70.7233 27.2725C57.6687 28.1106 46.2832 33.0968 37.8617 43.4256C30.0513 53.0022 26.6062 64.3694 26.3233 76.5471C25.9125 94.2223 34.5276 106.232 51.1808 112.095C58.6448 114.649 66.4731 115.979 74.362 116.032L74.3653 116.033ZM20.0205 60.3756C19.7421 60.3376 19.4597 60.3412 19.1824 60.3861C12.7641 62.2757 6.45466 73.2929 8.09026 79.6823C8.58579 81.6199 9.81316 82.7712 11.7592 82.8092C13.8765 82.8512 16.0005 82.5894 17.5501 82.4949C18.4092 74.7881 19.2099 67.6156 20.0185 60.3742L20.0205 60.3756ZM141.736 77.21C145.278 77.15 148.678 75.8064 151.305 73.4289C154.874 70.1905 155.296 65.2817 152.224 62.4522C149.242 59.7061 145.667 58.9152 141.736 59.7146V77.21Z"
                        fill="currentColor" />
                    <path
                        d="M84.8075 82.0252C86.4018 82.3193 88.1725 82.2825 89.5331 83.0097C90.1516 83.3495 90.6946 83.8115 91.129 84.3676C91.5634 84.9238 91.8802 85.5624 92.06 86.2448C92.3344 88.1095 90.7172 89.0671 88.9411 89.2994C88.0814 89.4143 87.2076 89.3635 86.367 89.1498C84.8505 88.6937 83.2428 88.6309 81.6954 88.9674C80.148 89.304 78.7116 90.0287 77.5215 91.0734C76.1714 92.182 74.5896 93.0209 73.233 91.3781C72.0319 89.9236 72.5832 88.2348 73.7817 86.9346C75.1549 85.3673 76.8518 84.1166 78.7554 83.269C80.659 82.4214 82.7239 81.9971 84.8075 82.0252Z"
                        fill="currentColor" />
                    <path
                        d="M57.7186 52.5112C61.4295 52.6392 63.7503 55.2876 63.5495 59.1645C63.3893 62.2533 60.9084 64.7434 58.1203 64.6154C54.9698 64.4703 52.4724 61.3206 52.607 57.6582C52.7442 53.9453 54.2853 52.3924 57.7186 52.5112Z"
                        fill="currentColor" />
                    <path
                        d="M93.575 57.3327C93.5684 54.2361 94.7564 52.8328 97.4244 52.7856C100.873 52.7245 103.039 54.689 102.96 57.8066C102.891 60.4916 100.78 62.7678 98.3 62.8282C95.4672 62.8971 93.5822 60.7024 93.575 57.3327Z"
                        fill="currentColor" />
                </svg>

                <span class="text-[20px] sm:text-[28.95px] font-semibold">
                    FAQトレーニング
                </span>
            </div>

            <form action="{{ route('faq.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="" class="text-[20px] font-medium">
                        質問
                    </label>
                    <input type="text" name="question" id="question" value="{{ old('question') }}" required
                        class="w-full block rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] mt-1">
                    @error('question')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="" class="text-[20px] font-medium">
                        回答
                    </label>
                    <textarea name="answer" id="answer" cols="30" rows="5" required
                        class="resize-none p-2 w-full block rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] focus:ring-0 mt-1">{{ old('answer') }}</textarea>
                    @error('answer')
                        {{ $message }}
                    @enderror
                </div>
                <x-primary-button class="!w-[100px] float-right">
                    {{ __('Add') }}
                </x-primary-button>
            </form>

            <div class="mt-20">
                <h1 class="font-semibold text-[28px]">登録済みのFAQ一覧</h1>
                @forelse ($faqs as $i => $faq)
                    <div class="mt-5 p-5 border-b border-[#344EAF]">
                        <div class="flex justify-between">
                            <p class="font-medium text-[20px]">
                                Q: {{ $faq->question }}
                            </p>

                            <p class="flex justify-center">
                                <a href="#" data-id="{{ $faq->id }}" data-question="{{ $faq->question }}"
                                    data-answer="{{ $faq->answer }}" class="edit-modal mx-2">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </a>
                                <a href="#" data-id="{{ $faq->id }}" class="delete-modal mx-2">                                
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 7H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M6 10L7.70141 19.3578C7.87432 20.3088 8.70258 21 9.66915 21H14.3308C15.2974 21 16.1257 20.3087 16.2986 19.3578L18 10"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>

                                </a>
                            </p>
                        </div>
                        <p class="mt-3 font-medium text-[20px]">
                            {{ $faq->answer }}
                        </p>
                    </div>
                @empty
                    <div class="mt-5 p-5 border-b border-[#344EAF] text-red-500">
                        No record found.
                    </div>
                @endforelse
                <div class="mt-5 flex items-center">
                    <div class="bg-[#344EAF] text-white !w-[100px] rounded p-2 text-center">
                        @if ($faqs->onFirstPage())
                            <span class="opacity-50">Previous</span>
                        @else
                            <a href="{{ $faqs->previousPageUrl() }}">Previous</a>
                        @endif
                    </div>

                    <span class="text-[#344EAF] mx-3 text-center">
                        Page {{ $faqs->currentPage() }} of {{ $faqs->lastPage() }}
                    </span>

                    <div class="bg-[#344EAF] text-white !w-[100px] rounded p-2 text-center">
                        @if ($faqs->hasMorePages())
                            <a href="{{ $faqs->nextPageUrl() }}">Next</a>
                        @else
                            <span class="opacity-50">Next</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('modal')
        <div id="edit-modal" class="hidden h-screen w-full bg-[#00000021] absolute top-0 flex justify-center items-center">
            <div class="bg-white rounded-lg w-full max-w-[600px] p-10">
                <h1 class="text-center text-[24px] font-semibold">Edit FAQ</h1>
                <form action="#" method="post" id="edit-form">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="" class="text-[20px] font-medium">
                            質問
                        </label>
                        <input type="text" name="question" id="modal-question"
                            class="w-full block rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] mt-1">
                    </div>
                    <div class="mb-3">
                        <label for="" class="text-[20px] font-medium">
                            回答
                        </label>
                        <textarea name="answer" id="modal-answer" cols="30" rows="5"
                            class="p-2 w-full block rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] focus:ring-0 mt-1"></textarea>
                    </div>
                    <x-primary-button class="float-right">
                        {{ __('Update') }}
                    </x-primary-button>
                    <button type="button" id="cancel-edit-modal"
                        class="mr-1 float-right border border-[#008BFE] py-2 px-5 text-center bg-[#008BFE] rounded-full text-white text-[16px] hover:bg-[#1f559c] font-semibold"
                        id="cancel-edit-modal">
                        Cancel
                    </button>
                </form>
            </div>
        </div>
    @endpush

    @push('script')
        <script>
            $('.delete-modal').click(function(e) {
                e.preventDefault();              
                $('#delete-modal').removeClass('hidden');


                let id = $(this).data('id');
                let url = `{{url('/faq/${id}')}}`;
                $('#delete-form').attr('action', url);
            });
            $('.edit-modal').click(function(e) {
                e.preventDefault();
                $('#edit-modal').removeClass('hidden');

                let question = $(this).data('question');
                let answer = $(this).data('answer');

                $('#modal-question').val(question);
                $('#modal-answer').text(answer);

                let id = $(this).data('id');
                let url = `{{url('/faq/${id}')}}`;
                $('#edit-form').attr('action', url);
            });

            $("#cancel-edit-modal").click(function(e) {
                e.preventDefault();
                $('#edit-modal').addClass('hidden');
            });
            $('#answer').on('input', function() {
                const maxLength = 300;
                if ($(this).val().length > maxLength) {
                    $(this).val($(this).val().slice(0, maxLength));
                }
            });
        </script>
    @endpush

</x-sidebar>
