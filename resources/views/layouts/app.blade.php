<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #b8d7ff;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #0081eb;
        }

        body {
            background: #008BFE;
            color: #173F74;
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div x-data="{ sidebarOpen: false }" class="flex h-screen">
        {{-- @include('layouts.navigation') --}}

        <div class="flex overflow-hidden flex-col flex-1">
            @if (request()->routeIs('dashboard') || Auth::user()->hasRole('admin'))
                @include('layouts.header')
            @endif

            <main class="overflow-y-auto overflow-x-hidden flex-1 ">
                <div class="container px-6 py-8 mx-auto">
                    @if (isset($header))
                        <h3 class="mb-4 text-3xl font-medium text-gray-700">
                            {{ $header }}
                        </h3>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <div id="delete-modal"
        class="hidden h-screen w-full bg-[#00000021] absolute top-0 flex justify-center items-center p-6">
        <div class="bg-white rounded-lg w-lg p-5 sm:p-10">
            <p class="text-center mb-5">
                Are you sure, you want to <b id="action">Delete</b> this <b id="table">FAQ</b>?
            </p>
            <form action="#" method="post" id="delete-form">
                @csrf
                @method('DELETE')
                <div class="text-center">
                    <button type="submit"
                        class="bg-green-500 hover:bg-white border border-green-500 text-white hover:text-green-500 rounded p-2 text-center w-[100px] sm:w-[150px]">Yes</button>
                    <button type="button" id="close-modal"
                        class="mt-1 sm:mt-0 bg-red-500 hover:bg-white border border-red-500 text-white hover:text-red-500 rounded p-2 text-center w-[100px] sm:w-[150px]">No</button>
                </div>
            </form>
        </div>
    </div>

    @stack('modal')
    @stack('script')
    <script>
        function togglePassword(a, id) {
            let passwordInput = document.getElementById(id);

            let status = a.getAttribute('data-status');
            passwordInput.setAttribute('type', status);

            let newStatus = status == 'text' ? 'password' : 'text';
            a.setAttribute('data-status', newStatus);


            let showIcon = "fa-eye";
            let hideIcon = "fa-eye-slash";
            let icon = document.getElementById('icon-' + id);
            if (status == 'text') {
                icon.classList.add(showIcon)
                icon.classList.remove(hideIcon)
            } else {
                icon.classList.remove(showIcon)
                icon.classList.add(hideIcon)
            }
        }

        $(document).ready(function() {
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                var visibleRows = 0;

                $("#myTable tr").each(function() {
                    if (!$(this).is("#noResult")) {
                        var match = $(this).text().toLowerCase().indexOf(value) > -1;
                        $(this).toggle(match);
                        if (match) visibleRows++;
                    }
                });

                if (visibleRows === 0) {
                    if ($("#noResult").length === 0) {
                        $("#myTable").append(
                            '<tr id="noResult"><td colspan="100%" class="border-b py-5 text-center font-semibold text-red-500">No record found</td></tr>'
                        );
                    }
                } else {
                    $("#noResult").remove();
                }
            });
        });


        $('#close-modal').click(function(e) {
            e.preventDefault();
            $('#delete-modal').addClass('hidden');
        });
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
    </script>
    @session('success')
        <script>
            Toast.fire({
                icon: "success",
                title: "{{ session('success') }}"
            });
        </script>
    @endsession
</body>

</html>
