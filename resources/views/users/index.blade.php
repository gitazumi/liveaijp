@section('title', 'Users')
<x-app-layout>
    <x-delete-modal />
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
                            @if($user->status == 'unverified')
                                <span class="bg-yellow-600 text-white rounded p-1 px-3">メール未認証</span>
                            @elseif($user->status == 'registered')
                                <span class="bg-blue-600 text-white rounded p-1 px-3">登録済み</span>
                            @elseif($user->status == 'active')
                                <span class="bg-[#173F74] text-white rounded p-1 px-3">利用中</span>
                            @elseif($user->status == 'inactive')
                                <span class="bg-gray-600 text-white rounded p-1 px-3">退会済み</span>
                            @else
                                <span class="bg-[#173F74] text-white rounded p-1 px-3">{{$user->status}}</span>
                            @endif
                        </td>
                        <td>
                            <p class="flex justify-center space-x-2">
                                <a href="{{ route('users.manage', ['id' => $user->id]) }}" class="bg-[#173F74] text-white rounded-full px-4 py-1 hover:bg-[#1f559c]">
                                    変更
                                </a>
                                <button type="button" data-id="{{ $user->id }}" class="delete-modal bg-red-600 text-white rounded-full px-4 py-1 hover:bg-red-700">
                                    削除
                                </button>
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
                $('#table-name').text('ユーザー');
                $('#action-name').text('削除');

                let id = $(this).data('id');
                let url = "{{ url('/users/') }}" + '/' + id;
                $('#delete-form').attr('action', url);
            });

            $('#cancel-btn').click(function() {
                $('#delete-modal').addClass('hidden');
            });
        </script>
    @endpush
</x-app-layout>
