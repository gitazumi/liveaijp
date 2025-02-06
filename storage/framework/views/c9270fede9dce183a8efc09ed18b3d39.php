<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title'); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background: #008BFE;
            color: #FFFFFF;
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>
    <div class="flex justify-center items-center h-screen px-6">
        <div class="p-6 max-w-sm w-full">
            <?php echo e($slot); ?>

        </div>
    </div>
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
    </script>
</body>

</html>
<?php /**PATH /var/www/resources/views/layouts/guest.blade.php ENDPATH**/ ?>