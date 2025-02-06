@section('title', 'Users')
<x-app-layout>
    <div class="md:flex justify-between">
        <h1 class="text-white font-semibold text-[28px]">
            User List
        </h1>
        <div class="md:flex">
            <a href="{{ route('users.create') }}"
                class="max-w-fit flex items-center border border-[#173F74] py-2 px-5 text-center bg-[#173F74] rounded-full text-white text-[16px] hover:bg-[#1f559c] font-semibold'">
                <svg class="mr-3" width="13" height="13" viewBox="0 0 13 13" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 6.5H13" stroke="white" stroke-width="2" />
                    <path d="M6.5 0L6.5 13" stroke="white" stroke-width="2" />
                </svg>

                Create User
            </a>

            <div class="md:ml-5 mt-3 md:mt-0 w-full md:w-[400px] flex items-center relative">
                <input type="text" id="myInput"
                    class="w-full rounded-full bg-white border-none focus:border-none focus:ring-0 px-5 pr-8"
                    placeholder="Search User">
                <div class="absolute right-[15px]">
                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="7" cy="7" r="6.25" stroke="#6C6C6C" stroke-width="1.5" />
                        <path d="M11 12L15 16" stroke="#6C6C6C" stroke-width="1.5" />
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <div class="w-full bg-[#E9F2FF] rounded-lg overflow-x-scroll mt-7">
        <table class="min-w-full overflow-x-scroll text-center text-nowrap">
            <thead>
                <tr>
                    <th class="p-3 bg-white text-[17px]">
                        SR. NO
                    </th>
                    <th class="p-3 bg-white text-[17px]">
                        Email
                    </th>
                    <th class="p-3 bg-white text-[17px]">
                        Status
                    </th>
                    <th class="p-3 bg-white text-[17px]">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody id="myTable">
                @forelse ($users as $i => $user)
                    <tr>
                        <td class="py-5 px-3">
                            {{ $i + 1 }}
                        </td>
                        <td class="py-5 px-3">
                            {{$user->email}}
                        </td>
                        <td class="py-5 px-3">
                            <span class="bg-[#173F74] text-white rounded p-1 px-3">{{$user->status}}</span>
                        </td>
                        <td>
                            <p class="flex justify-center">
                                <a href="{{url('users/auto-login/' . $user->id)}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" fill="currentColor"><path d="M480-120v-80h280v-560H480v-80h280q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H480Zm-80-160-55-58 102-102H120v-80h327L345-622l55-58 200 200-200 200Z"/></svg>
                                </a>
                                <a href="{{ route('users.edit', ['id' => $user]) }}" class="mx-2">
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
                                <a href="#" class="delete-modal mx-2" data-id="{{$user->id}}">
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
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-red-500">
                        No record found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @push('script')
        <script>
            $('.delete-modal').click(function(e) {
                e.preventDefault();
                $('#delete-modal').removeClass('hidden');
                $('#table').text('User');
                $('#action').text('Delete');

                let id = $(this).data('id');
                // let url = `/users/${id}`;
                let url = "{{ url('/users/') }}" + '/' + id;
                $('#delete-form').attr('action', url);
            });
        </script>
    @endpush
</x-app-layout>
