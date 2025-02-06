<?php $__env->startSection('title', 'Chat History'); ?>
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
    <div class="flex justify-center">
        <div class="w-full rounded-xl p-[30px] sm:p-[50px] bg-[#E9F2FF]">
            <div class="flex items-center mb-10">
                <a href="<?php echo e(route('dashboard')); ?>">
                    <svg width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8 14C8 13.258 7.267 12.15 6.525 11.22C5.571 10.02 4.431 8.973 3.124 8.174C2.144 7.575 0.956 7 -3.0598e-07 7M-3.0598e-07 7C0.956 7 2.145 6.425 3.124 5.826C4.431 5.026 5.571 3.979 6.525 2.781C7.267 1.85 8 0.74 8 -3.49691e-07M-3.0598e-07 7L24 7"
                            stroke="black" stroke-width="2" />
                    </svg>
                </a>
                <svg fill="currentColor" width="32" height="32" class="mx-5" viewBox="0 0 32 32" id="icon"
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


                <span class="text-[20px] sm:text-[28.95px] font-semibold">
                    Chat History
                </span>
            </div>

            <div class="w-full overflow-x-scroll">
                <table class="min-w-full">
                    <thead class="bg-[#344EAF] text-[#E9F2FF] rounded p-2">
                        <tr>
                            <th class="rounded-l-lg p-2 font-semibold text-[16px] sm:text-[20px] mb-5 uppercase">
                                Title
                            </th>
                            <th class="p-2 font-semibold text-[16px] sm:text-[20px] mb-5 uppercase">
                                Last Update
                            </th>
                            <th class="rounded-r-lg p-2 font-semibold text-[16px] sm:text-[20px] mb-5 uppercase">
                                Messages
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="text-nowrap border-b border-[#344EAF] mt-3">
                                <td class="text-[16px] sm:text-[20px] py-5 mt-5">
                                    <a class="flex items-center hover:underline"
                                        href="<?php echo e(route('chat.chat', ['chatId' => $conversation->session_id])); ?>">

                                        <svg class="mr-3" width="20" height="20" viewBox="0 0 32 32"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M15.651 19.0053C14.3597 19.0064 13.0882 18.6875 11.9502 18.0771C10.8123 17.4666 9.84334 16.5837 9.13006 15.5072L10.9926 14.2697C11.5024 15.0385 12.1946 15.6692 13.0075 16.1055C13.8203 16.5418 14.7285 16.7701 15.651 16.7701C16.5736 16.7701 17.4818 16.5418 18.2946 16.1055C19.1074 15.6692 19.7997 15.0385 20.3095 14.2697L22.172 15.5072C21.4587 16.5837 20.4898 17.4666 19.3518 18.0771C18.2139 18.6875 16.9424 19.0064 15.651 19.0053ZM20.1228 6.70788C19.6806 6.70788 19.2483 6.83901 18.8806 7.08469C18.5129 7.33038 18.2264 7.67958 18.0571 8.08813C17.8879 8.49669 17.8436 8.94625 17.9299 9.37997C18.0162 9.81369 18.2291 10.2121 18.5418 10.5248C18.8545 10.8375 19.2529 11.0504 19.6866 11.1367C20.1203 11.223 20.5699 11.1787 20.9785 11.0095C21.387 10.8402 21.7362 10.5537 21.9819 10.186C22.2276 9.81828 22.3587 9.38599 22.3587 8.94377C22.3617 8.64932 22.3059 8.35723 22.1946 8.08461C22.0833 7.81199 21.9187 7.56431 21.7105 7.35609C21.5023 7.14787 21.2546 6.98329 20.982 6.87198C20.7094 6.76068 20.4173 6.70489 20.1228 6.70788ZM11.1793 6.70788C10.737 6.70788 10.3047 6.83901 9.93706 7.08469C9.56937 7.33038 9.28279 7.67958 9.11356 8.08813C8.94433 8.49669 8.90005 8.94625 8.98632 9.37997C9.07259 9.81369 9.28554 10.2121 9.59824 10.5248C9.91093 10.8375 10.3093 11.0504 10.7431 11.1367C11.1768 11.223 11.6263 11.1787 12.0349 11.0095C12.4434 10.8402 12.7926 10.5537 13.0383 10.186C13.284 9.81828 13.4151 9.38599 13.4151 8.94377C13.4181 8.64932 13.3623 8.35723 13.251 8.08461C13.1397 7.81199 12.9752 7.56431 12.7669 7.35609C12.5587 7.14787 12.311 6.98329 12.0384 6.87198C11.7658 6.76068 11.4737 6.70489 11.1793 6.70788Z"
                                                fill="currentColor" />
                                            <path
                                                d="M17.592 31.3025L15.6512 30.1846L20.123 22.3589H26.8307C27.1245 22.3594 27.4154 22.3018 27.6869 22.1896C27.9583 22.0774 28.205 21.9127 28.4127 21.705C28.6204 21.4973 28.7851 21.2507 28.8973 20.9792C29.0095 20.7077 29.067 20.4168 29.0666 20.123V4.47179C29.067 4.17804 29.0095 3.88709 28.8973 3.61562C28.7851 3.34415 28.6204 3.0975 28.4127 2.88979C28.205 2.68208 27.9583 2.5174 27.6869 2.40519C27.4154 2.29298 27.1245 2.23545 26.8307 2.23589H4.47179C4.17804 2.23545 3.88709 2.29298 3.61562 2.40519C3.34415 2.5174 3.0975 2.68208 2.88979 2.88979C2.68208 3.0975 2.5174 3.34415 2.40519 3.61562C2.29298 3.88709 2.23545 4.17804 2.23589 4.47179V20.123C2.23545 20.4168 2.29298 20.7077 2.40519 20.9792C2.5174 21.2507 2.68208 21.4973 2.88979 21.705C3.0975 21.9127 3.34415 22.0774 3.61562 22.1896C3.88709 22.3018 4.17804 22.3594 4.47179 22.3589H14.5333V24.5948H4.47179C3.28579 24.5948 2.14838 24.1237 1.30976 23.2851C0.471133 22.4464 0 21.309 0 20.123V4.47179C0 3.28579 0.471133 2.14838 1.30976 1.30976C2.14838 0.471133 3.28579 0 4.47179 0H26.8307C28.0167 0 29.1541 0.471133 29.9927 1.30976C30.8314 2.14838 31.3025 3.28579 31.3025 4.47179V20.123C31.3025 21.309 30.8314 22.4464 29.9927 23.2851C29.1541 24.1237 28.0167 24.5948 26.8307 24.5948H21.4254L17.592 31.3025Z"
                                                fill="currentColor" />
                                        </svg>
                                        <?php echo e($conversation->title); ?>

                                    </a>
                                </td>
                                <td class="text-center text-[16px] sm:text-[20px] py-5 mt-5">
                                    <?php echo e($conversation->created_at); ?>

                                </td>
                                <td class="text-center text-[16px] sm:text-[20px] py-5 mt-5">
                                    <?php echo e(count($conversation->messages)); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="mt-5 p-5 border-b border-[#344EAF] text-red-500">
                                    No record found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="mt-5 flex items-center">
                    <div class="bg-[#344EAF] text-white w-[80px] sm:!w-[100px] rounded p-2 text-center">
                        <?php if($conversations->onFirstPage()): ?>
                            <span class="opacity-50 text-[16px] sm:text-[18px]">Previous</span>
                        <?php else: ?>
                            <a href="<?php echo e($conversations->previousPageUrl()); ?>" class="text-[15px] sm:text-[18px]">Previous</a>
                        <?php endif; ?>
                    </div>

                    <span class="text-[#344EAF] mx-3 text-center text-[14px] sm:text-[18px]">
                        Page <?php echo e($conversations->currentPage()); ?> of <?php echo e($conversations->lastPage()); ?>

                    </span>

                    <div class="bg-[#344EAF] text-white w-[80px] sm:!w-[100px] rounded p-2 text-center">
                        <?php if($conversations->hasMorePages()): ?>
                            <a href="<?php echo e($conversations->nextPageUrl()); ?>" class="text-[15px] sm:text-[18px]">Next</a>
                        <?php else: ?>
                            <span class="opacity-50 text-[16px] sm:text-[18px]">Next</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    </div>
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
<?php /**PATH /var/www/resources/views/admin/chat/history.blade.php ENDPATH**/ ?>