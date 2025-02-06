<?php $__env->startSection('title', 'Dashboard'); ?>
<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-5 gap-y-8">
        <div class="relative bg-white hover:bg-[#80C5FF] text-[#173F74] hover:text-white rounded-2xl h-[220px] p-5">
            <p class="text-[16.16px] flex font-semibold">
                <svg width="25" height="25" viewBox="0 -0.5 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M10.9426 8.674C10.9426 8.25979 10.6068 7.924 10.1926 7.924C9.77842 7.924 9.44263 8.25979 9.44263 8.674H10.9426ZM15.5566 8.674C15.5566 8.25979 15.2208 7.924 14.8066 7.924C14.3924 7.924 14.0566 8.25979 14.0566 8.674H15.5566ZM9.44274 8.66112C9.43563 9.07527 9.76559 9.41677 10.1797 9.42389C10.5939 9.43101 10.9354 9.10104 10.9425 8.68688L9.44274 8.66112ZM10.9525 8.10488C10.9596 7.69073 10.6297 7.34923 10.2155 7.34211C9.80136 7.33499 9.45986 7.66496 9.45274 8.07912L10.9525 8.10488ZM10.9201 8.85645C11.0209 8.45468 10.7769 8.04729 10.3751 7.94653C9.97331 7.84577 9.56593 8.08978 9.46516 8.49155L10.9201 8.85645ZM10.0356 9.3L10.7613 9.48948L10.7631 9.48245L10.0356 9.3ZM8.60872 10.8759L8.8691 11.5792H8.8691L8.60872 10.8759ZM6.927 9.99268C6.58662 9.75665 6.11934 9.84124 5.88331 10.1816C5.64728 10.522 5.73187 10.9893 6.07226 11.2253L6.927 9.99268ZM7.24959 10.6167C7.25384 10.2025 6.92151 9.86328 6.50732 9.85904C6.09312 9.85479 5.75392 10.1871 5.74967 10.6013L7.24959 10.6167ZM6.44463 15.976L7.19458 15.985L7.19459 15.9837L6.44463 15.976ZM7.02186 17.4058L6.48838 17.9329H6.48838L7.02186 17.4058ZM8.44463 18V17.25H8.44458L8.44463 18ZM10.4996 18.75C10.9138 18.75 11.2496 18.4142 11.2496 18C11.2496 17.5858 10.9138 17.25 10.4996 17.25V18.75ZM6.07039 11.224C6.41006 11.4611 6.87759 11.3779 7.11465 11.0382C7.35172 10.6986 7.26854 10.231 6.92887 9.99398L6.07039 11.224ZM5.57863 8.092L4.87557 7.83085C4.86723 7.85329 4.85997 7.87612 4.85382 7.89926L5.57863 8.092ZM6.41363 5.844L7.1167 6.10515C7.12393 6.08569 7.13034 6.06594 7.13593 6.04594L6.41363 5.844ZM7.52263 5V4.25L7.52046 4.25L7.52263 5ZM17.4756 5L17.4778 4.25H17.4756V5ZM18.5846 5.844L17.8623 6.04595C17.8679 6.0659 17.8743 6.08562 17.8815 6.10505L18.5846 5.844ZM19.4196 8.093L20.1445 7.90034C20.1383 7.87721 20.1311 7.85439 20.1227 7.83195L19.4196 8.093ZM18.0701 9.99422C17.7305 10.2315 17.6476 10.699 17.8848 11.0386C18.1221 11.3781 18.5897 11.461 18.9292 11.2238L18.0701 9.99422ZM9.74963 18C9.74963 18.4142 10.0854 18.75 10.4996 18.75C10.9138 18.75 11.2496 18.4142 11.2496 18H9.74963ZM13.7496 18C13.7496 18.4142 14.0854 18.75 14.4996 18.75C14.9138 18.75 15.2496 18.4142 15.2496 18H13.7496ZM10.4996 17.25C10.0854 17.25 9.74963 17.5858 9.74963 18C9.74963 18.4142 10.0854 18.75 10.4996 18.75V17.25ZM14.4996 18.75C14.9138 18.75 15.2496 18.4142 15.2496 18C15.2496 17.5858 14.9138 17.25 14.4996 17.25V18.75ZM14.0567 8.68302C14.0617 9.0972 14.4015 9.42893 14.8157 9.42395C15.2298 9.41896 15.5616 9.07916 15.5566 8.66498L14.0567 8.68302ZM15.5496 8.08298C15.5446 7.6688 15.2048 7.33707 14.7906 7.34205C14.3764 7.34704 14.0447 7.68684 14.0497 8.10102L15.5496 8.08298ZM15.5369 8.49073C15.4357 8.08907 15.028 7.84552 14.6264 7.94674C14.2247 8.04796 13.9811 8.45562 14.0824 8.85727L15.5369 8.49073ZM14.9666 9.297L14.2393 9.48028L14.2409 9.48622L14.9666 9.297ZM16.3915 10.8729L16.1304 11.5759L16.3915 10.8729ZM18.926 11.226C19.2667 10.9906 19.3521 10.5235 19.1167 10.1827C18.8812 9.84189 18.4141 9.7565 18.0733 9.99195L18.926 11.226ZM19.2496 10.6017C19.2456 10.1875 18.9066 9.85502 18.4924 9.85904C18.0782 9.86305 17.7457 10.2021 17.7497 10.6163L19.2496 10.6017ZM18.5516 15.976L17.8017 15.9833L17.8017 15.985L18.5516 15.976ZM17.9744 17.4058L17.4409 16.8786L17.9744 17.4058ZM16.5516 18L16.5517 17.25H16.5516V18ZM14.4996 17.25C14.0854 17.25 13.7496 17.5858 13.7496 18C13.7496 18.4142 14.0854 18.75 14.4996 18.75V17.25ZM9.44263 8.674C9.44263 10.3623 10.8113 11.731 12.4996 11.731V10.231C11.6397 10.231 10.9426 9.53391 10.9426 8.674H9.44263ZM12.4996 11.731C14.188 11.731 15.5566 10.3623 15.5566 8.674H14.0566C14.0566 9.53391 13.3595 10.231 12.4996 10.231V11.731ZM10.9425 8.68688L10.9525 8.10488L9.45274 8.07912L9.44274 8.66112L10.9425 8.68688ZM9.46516 8.49155L9.30816 9.11755L10.7631 9.48245L10.9201 8.85645L9.46516 8.49155ZM9.30996 9.11053C9.18207 9.60032 8.82307 9.9968 8.34835 10.1725L8.8691 11.5792C9.80324 11.2334 10.5097 10.4533 10.7613 9.48947L9.30996 9.11053ZM8.34835 10.1725C7.87362 10.3483 7.34299 10.2811 6.927 9.99268L6.07226 11.2253C6.89081 11.7929 7.93496 11.9251 8.8691 11.5792L8.34835 10.1725ZM5.74967 10.6013L5.69467 15.9683L7.19459 15.9837L7.24959 10.6167L5.74967 10.6013ZM5.69469 15.967C5.68586 16.702 5.9717 17.41 6.48838 17.9329L7.55535 16.8786C7.32049 16.6409 7.19057 16.3191 7.19458 15.985L5.69469 15.967ZM6.48838 17.9329C7.00507 18.4558 7.70959 18.7501 8.44469 18.75L8.44458 17.25C8.11044 17.25 7.7902 17.1163 7.55535 16.8786L6.48838 17.9329ZM8.44463 18.75H10.4996V17.25H8.44463V18.75ZM6.92887 9.99398C6.38194 9.61226 6.13204 8.92931 6.30344 8.28474L4.85382 7.89926C4.52041 9.15305 5.00651 10.4815 6.07039 11.224L6.92887 9.99398ZM6.2817 8.35315L7.1167 6.10515L5.71057 5.58285L4.87557 7.83085L6.2817 8.35315ZM7.13593 6.04594C7.18473 5.8714 7.34356 5.75052 7.5248 5.75L7.52046 4.25C6.66795 4.25247 5.92088 4.82103 5.69133 5.64206L7.13593 6.04594ZM7.52263 5.75H17.4756V4.25H7.52263V5.75ZM17.4735 5.75C17.6547 5.75052 17.8135 5.8714 17.8623 6.04595L19.3069 5.64205C19.0774 4.82103 18.3303 4.25247 17.4778 4.25L17.4735 5.75ZM17.8815 6.10505L18.7165 8.35405L20.1227 7.83195L19.2877 5.58295L17.8815 6.10505ZM18.6948 8.28566C18.866 8.92987 18.6164 9.61242 18.0701 9.99422L18.9292 11.2238C19.9921 10.4811 20.4775 9.15344 20.1445 7.90034L18.6948 8.28566ZM11.2496 18V15H9.74963V18H11.2496ZM11.2496 15C11.2496 14.8619 11.3616 14.75 11.4996 14.75V13.25C10.5331 13.25 9.74963 14.0335 9.74963 15H11.2496ZM11.4996 14.75H13.4996V13.25H11.4996V14.75ZM13.4996 14.75C13.6377 14.75 13.7496 14.8619 13.7496 15H15.2496C15.2496 14.0335 14.4661 13.25 13.4996 13.25V14.75ZM13.7496 15V18H15.2496V15H13.7496ZM10.4996 18.75H14.4996V17.25H10.4996V18.75ZM15.5566 8.66498L15.5496 8.08298L14.0497 8.10102L14.0567 8.68302L15.5566 8.66498ZM14.0824 8.85727L14.2394 9.48027L15.6939 9.11373L15.5369 8.49073L14.0824 8.85727ZM14.2409 9.48622C14.492 10.4494 15.1973 11.2294 16.1304 11.5759L16.6526 10.1698C16.1784 9.99367 15.82 9.59726 15.6924 9.10778L14.2409 9.48622ZM16.1304 11.5759C17.0635 11.9225 18.107 11.7919 18.926 11.226L18.0733 9.99195C17.6571 10.2795 17.1268 10.3459 16.6526 10.1698L16.1304 11.5759ZM17.7497 10.6163L17.8017 15.9833L19.3016 15.9687L19.2496 10.6017L17.7497 10.6163ZM17.8017 15.985C17.8057 16.3191 17.6758 16.6409 17.4409 16.8786L18.5079 17.9329C19.0246 17.41 19.3104 16.702 19.3016 15.967L17.8017 15.985ZM17.4409 16.8786C17.2061 17.1163 16.8858 17.25 16.5517 17.25L16.5516 18.75C17.2867 18.7501 17.9912 18.4558 18.5079 17.9329L17.4409 16.8786ZM16.5516 17.25H14.4996V18.75H16.5516V17.25Z"
                        fill="currentColor" />
                </svg>
                <span class="ml-3">
                    Store Information
                </span>
            </p>

            <p class="text-[20px] mt-3">
                Configure your store's basic informaiton
            </p>

            <a href="<?php echo e(route('information.create')); ?>" class="absolute bottom-[10px] font-semibold flex items-center">
                <span class="mr-3">
                    Configure
                </span>
                <svg width="30" height="25" viewBox="0 0 24 24" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>
        <div class="relative bg-white hover:bg-[#80C5FF] text-[#173F74] hover:text-white rounded-2xl h-[220px] p-5">
            <p class="text-[16.16px] flex font-semibold">
                <svg width="20" height="20" viewBox="0 -19.5 164 164" fill="currentColor"
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

                <span class="ml-3">
                    FAQ Training
                </span>
            </p>

            <p class="text-[20px] mt-3">
                Train AI with your FAQs for automated responses.
            </p>

            <a href="<?php echo e(route('faq.index')); ?>" class="absolute bottom-[10px] font-semibold flex items-center">
                <span class="mr-3">
                    Configure FAQ’s
                </span>
                <svg width="30" height="25" viewBox="0 0 24 24" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>

            </a>
        </div>
        <div class="relative bg-white hover:bg-[#80C5FF] text-[#173F74] hover:text-white rounded-2xl h-[220px] p-5">
            <div class="flex justify-between">
                <p class="text-[16.16px] flex font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px"
                        fill="currentColor">
                        <path
                            d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
                    </svg>

                    <span class="ml-3">
                        Calendar Sync
                    </span>
                </p>
                
            </div>

            <p class="text-[20px] mt-3">
                Syc booking with Google Calendar
            </p>
            


            <a href="<?php echo e(route('google-calendar.index')); ?>"
                class="absolute bottom-[10px] font-semibold flex items-center">
                <span class="mr-3">
                    Connect
                </span>
                <svg width="30" height="25" viewBox="0 0 24 24" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>

            </a>
        </div>
        <div class="relative bg-white hover:bg-[#80C5FF] text-[#173F74] hover:text-white rounded-2xl h-[220px] p-5">
            <p class="text-[16.16px] flex font-semibold">
                <svg fill="currentColor" width="20" height="20" viewBox="0 0 32 32" id="icon"
                    xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <style>
                            .cls-1 {
                                fill: none;
                            }
                        </style>
                    </defs>
                    <title>chat-bot</title>
                    <path
                        d="M16,19a6.9908,6.9908,0,0,1-5.833-3.1287l1.666-1.1074a5.0007,5.0007,0,0,0,8.334,0l1.666,1.1074A6.9908,6.9908,0,0,1,16,19Z" />
                    <path d="M20,8a2,2,0,1,0,2,2A1.9806,1.9806,0,0,0,20,8Z" />
                    <path d="M12,8a2,2,0,1,0,2,2A1.9806,1.9806,0,0,0,12,8Z" />
                    <path
                        d="M17.7358,30,16,29l4-7h6a1.9966,1.9966,0,0,0,2-2V6a1.9966,1.9966,0,0,0-2-2H6A1.9966,1.9966,0,0,0,4,6V20a1.9966,1.9966,0,0,0,2,2h9v2H6a3.9993,3.9993,0,0,1-4-4V6A3.9988,3.9988,0,0,1,6,2H26a3.9988,3.9988,0,0,1,4,4V20a3.9993,3.9993,0,0,1-4,4H21.1646Z" />
                    <rect id="_Transparent_Rectangle_" data-name="&lt;Transparent Rectangle&gt;" class="cls-1"
                        width="32" height="32" />
                </svg>
                <span class="ml-3">
                    Chat History
                </span>
            </p>

            <p class="text-[20px] mt-3">
                view past chat conversation
            </p>

            <a href="<?php echo e(route('chat.history')); ?>" class="absolute bottom-[10px] font-semibold flex items-center">
                <span class="mr-3">
                    View All
                </span>
                <svg width="30" height="25" viewBox="0 0 24 24" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>

            </a>
        </div>
        <div class="relative bg-white hover:bg-[#80C5FF] text-[#173F74] hover:text-white rounded-2xl h-[220px] p-5">
            <p class="text-[16.16px] flex font-semibold">
                <svg fill="currentColor" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20"
                    viewBox="0 0 31.612 31.612" xml:space="preserve">
                    <g>
                        <g>
                            <path d="M10.871,13.671l-4.058,4.057c-0.234,0.234-0.367,0.553-0.367,0.885c0,0.333,0.133,0.65,0.367,0.885l3.923,3.924
                       c0.245,0.244,0.565,0.367,0.887,0.367c0.32,0,0.641-0.123,0.885-0.367c0.49-0.488,0.49-1.281,0-1.771L9.47,18.613l3.173-3.172
                       c0.489-0.488,0.489-1.281,0-1.77C12.152,13.182,11.36,13.182,10.871,13.671z" />
                            <path d="M18.969,15.443l3.174,3.171l-3.039,3.038c-0.488,0.488-0.488,1.281,0,1.771c0.244,0.244,0.564,0.366,0.886,0.366
                       s0.642-0.122,0.887-0.366l3.923-3.924c0.234-0.234,0.367-0.554,0.367-0.886c0-0.333-0.133-0.651-0.367-0.886l-4.058-4.056
                       c-0.489-0.489-1.281-0.489-1.771,0C18.48,14.16,18.48,14.954,18.969,15.443z" />
                            <path d="M13.265,26.844c0.081,0.023,0.162,0.037,0.245,0.037c0.356,0,0.688-0.232,0.798-0.592l4.59-14.995
                       c0.138-0.441-0.111-0.908-0.553-1.043c-0.443-0.135-0.906,0.113-1.043,0.554L12.71,25.799
                       C12.576,26.241,12.823,26.707,13.265,26.844z" />
                            <path d="M11.216,0L3.029,8.643v22.969h25.554V0H11.216z M10.495,3.635v3.83H6.867L10.495,3.635z M26.605,29.637H5.005V9.441h7.465
                       V1.975h14.135V29.637z" />
                        </g>
                    </g>
                </svg>

                <span class="ml-3">
                    Generate Snippet
                </span>
            </p>

            <p class="text-[20px] mt-3">
                Generate chat widget code for your website
            </p>

            <a href="<?php echo e(url('chat/generate-snippet')); ?>" class="absolute bottom-[10px] font-semibold flex items-center">
                <span class="mr-3">
                    Generate Code
                </span>
                <svg width="30" height="25" viewBox="0 0 24 24" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>

            </a>
        </div>
        <div class="relative bg-white hover:bg-[#80C5FF] text-[#173F74] hover:text-white rounded-2xl h-[220px] p-5">
            <p class="text-[16.16px] flex font-semibold">
                <svg width="20" height="20" viewBox="0 0 24 24" version="1.1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>ic_fluent_bot_24_filled</title>
                    <desc>Created with Sketch.</desc>
                    <g id="🔍-Product-Icons" stroke="none" stroke-width="1" fill="currentColor"
                        fill-rule="evenodd">
                        <g id="ic_fluent_bot_24_filled" fill="currentColor" fill-rule="nonzero">
                            <path
                                d="M17.7530511,13.999921 C18.9956918,13.999921 20.0030511,15.0072804 20.0030511,16.249921 L20.0030511,17.1550008 C20.0030511,18.2486786 19.5255957,19.2878579 18.6957793,20.0002733 C17.1303315,21.344244 14.8899962,22.0010712 12,22.0010712 C9.11050247,22.0010712 6.87168436,21.3444691 5.30881727,20.0007885 C4.48019625,19.2883988 4.00354153,18.2500002 4.00354153,17.1572408 L4.00354153,16.249921 C4.00354153,15.0072804 5.01090084,13.999921 6.25354153,13.999921 L17.7530511,13.999921 Z M11.8985607,2.00734093 L12.0003312,2.00049432 C12.380027,2.00049432 12.6938222,2.2826482 12.7434846,2.64872376 L12.7503312,2.75049432 L12.7495415,3.49949432 L16.25,3.5 C17.4926407,3.5 18.5,4.50735931 18.5,5.75 L18.5,10.254591 C18.5,11.4972317 17.4926407,12.504591 16.25,12.504591 L7.75,12.504591 C6.50735931,12.504591 5.5,11.4972317 5.5,10.254591 L5.5,5.75 C5.5,4.50735931 6.50735931,3.5 7.75,3.5 L11.2495415,3.49949432 L11.2503312,2.75049432 C11.2503312,2.37079855 11.5324851,2.05700336 11.8985607,2.00734093 L12.0003312,2.00049432 L11.8985607,2.00734093 Z M9.74928905,6.5 C9.05932576,6.5 8.5,7.05932576 8.5,7.74928905 C8.5,8.43925235 9.05932576,8.99857811 9.74928905,8.99857811 C10.4392523,8.99857811 10.9985781,8.43925235 10.9985781,7.74928905 C10.9985781,7.05932576 10.4392523,6.5 9.74928905,6.5 Z M14.2420255,6.5 C13.5520622,6.5 12.9927364,7.05932576 12.9927364,7.74928905 C12.9927364,8.43925235 13.5520622,8.99857811 14.2420255,8.99857811 C14.9319888,8.99857811 15.4913145,8.43925235 15.4913145,7.74928905 C15.4913145,7.05932576 14.9319888,6.5 14.2420255,6.5 Z"
                                id="🎨-Color">

                            </path>
                        </g>
                    </g>
                </svg>

                <span class="ml-3">
                    Bot Test
                </span>
            </p>

            <p class="text-[20px] mt-3">
                Test your trained AI responses
            </p>

            <a href="<?php echo e(route('chat.bot')); ?>" class="absolute bottom-[10px] font-semibold flex items-center">
                <span class="mr-3">
                    Test
                </span>
                <svg width="30" height="25" viewBox="0 0 24 24" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>

            </a>
        </div>
    </div>

    <?php $__env->startPush('modal'); ?>
        <div id="snippet-modal"
            class="hidden h-screen w-full bg-[#00000021] absolute top-0 flex justify-center items-center p-6">
            <div class="bg-white rounded-lg max-w-[280px] sm:max-w-lg p-5 sm:p-10">
                <button id="close-snippet-modal" class="float-right text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#666666"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                </button>
                <pre class="my-5 max-w-fit break-words whitespace-pre-wrap" id="snippet-code">

                </pre>
                <button id="copy-snippet" class="float-right text-white bg-[#173F74] rounded py-1 px-3">
                    Copy
                </button>
            </div>
        </div>
    <?php $__env->stopPush(); ?>
    <?php $__env->startPush('script'); ?>
        <script>
            $('#generate-snippet').click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "<?php echo e(route('chat.generate-snippet')); ?>",
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        if (response.error) {
                            Toast.fire({
                                icon: "error",
                                title: response.message
                            });
                            return;
                        }

                        $('#snippet-modal').removeClass('hidden');
                        $('#snippet-code').text(response.snippet);
                    }
                });
            });

            $('#close-snippet-modal').click(function (e) { 
                e.preventDefault();
                $('#snippet-modal').addClass('hidden');
               $('#snippet-code').text(null);
            });

            
            $('#copy-snippet').click(function(e) {
                e.preventDefault();

                const textToCopy = $(`#snippet-code`).text().trim();
                const tempInput = $('<textarea>');
                tempInput.val(textToCopy);
                $('body').append(tempInput);
                tempInput.select();
                document.execCommand('copy');
                tempInput.remove();
                $(this).html('Copied');
            });
        </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /var/www/resources/views/dashboard.blade.php ENDPATH**/ ?>