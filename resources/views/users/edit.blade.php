@section('title', 'Edit User')
<x-app-layout>
    <div class="flex justify-between">
        <h1 class="text-white font-semibold text-[28px]">
            Edit User
        </h1>
    </div>

    <div class="w-full p-5 bg-[#E9F2FF] rounded-lg overflow-x-hidden mt-7">
        <a href="{{ route('users.index') }}">
            <svg width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8 14C8 13.258 7.267 12.15 6.525 11.22C5.571 10.02 4.431 8.973 3.124 8.174C2.144 7.575 0.956 7 -3.0598e-07 7M-3.0598e-07 7C0.956 7 2.145 6.425 3.124 5.826C4.431 5.026 5.571 3.979 6.525 2.781C7.267 1.85 8 0.74 8 -3.49691e-07M-3.0598e-07 7L24 7"
                    stroke="black" stroke-width="2" />
            </svg>
        </a>
        <div class="w-full flex justify-center items-center h-screen max-h-[600px]">
            <div class="w-full lg:w-[500px]">
                <form action="{{ route('users.update', ['id' => $data->id]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="" class="text-[20px] font-medium">
                            Email
                        </label>
                        <input type="email" placeholder="info@xyz.com" name="email" id="email" value="{{ $data->email }}"
                            class="w-full block rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] mt-1">
                        @error('email')
                            <span class="text-red-500">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="" class="text-[20px] font-medium">
                            Password
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password" placeholder="xxxxxxx"
                                class="w-full block rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] mt-1">
                            <button type="button" onclick="togglePassword(this, 'password')" data-status="text"
                                class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                                <i class="fa-regular fa-eye-slash" id="icon-password"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="" class="text-[20px] font-medium">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                placeholder="xxxxxxx"
                                class="w-full block rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] mt-1">
                            <button type="button" onclick="togglePassword(this, 'password_confirmation')"
                                data-status="text"
                                class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                                <i class="fa-regular fa-eye-slash" id="icon-password_confirmation"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <span class="text-red-500">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="" class="text-[20px] font-medium">
                            Status
                        </label>
                        <select name="status" id="status" class="p-2 w-full block rounded border-[#344EAF] bg-transparent focus:ring-[#344EAF] mt-1">
                            <option value="Active" @selected($data->status == 'Active')>Active</option>
                            <option value="Inactive" @selected($data->status == 'Inactive')>Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-red-500">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <x-primary-button class="w-full mt-5">
                        {{ __('Update User') }}
                    </x-primary-button>
                </form>

            </div>

        </div>
    </div>

</x-app-layout>
