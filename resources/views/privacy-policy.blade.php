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

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

</head>

<body class="bg-gray-100 text-gray-800">
    <header class="bg-blue-600 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold">Privacy Policy</h1>
        </div>
    </header>

    <main class="container mx-auto px-4 py-10">
        <section class="bg-white shadow-md rounded-lg p-6">
            <p class="text-gray-500 mb-4">Effective Date: <strong>21/01/2025</strong></p>
            <p class="mb-6">At <strong>liveai.jp</strong>, we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform. By using our platform, you agree to the terms of this Privacy Policy.</p>
        </section>

        <section class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">1. Information We Collect</h2>
            <p class="mb-4">We collect the following types of information to provide our services:</p>
            <ul class="list-disc pl-6 space-y-2 text-gray-700">
                <li><strong>User-Provided Information:</strong> Account details, store information, FAQs, and Google Calendar data.</li>
                <li><strong>Visitor Interaction Data:</strong> Messages exchanged in the chatbox and usage patterns.</li>
                <li><strong>Automatically Collected Information:</strong> IP addresses, browser types, and cookies for analytics.</li>
            </ul>
        </section>

        <section class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">2. How We Use Your Information</h2>
            <ul class="list-disc pl-6 space-y-2 text-gray-700">
                <li>To provide, operate, and improve our services.</li>
                <li>To generate the JavaScript snippet for your website’s chat functionality.</li>
                <li>To respond to visitor inquiries using AI based on FAQs, store information, and calendar events.</li>
            </ul>
        </section>

        <section class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">3. How We Share Your Information</h2>
            <p class="mb-4">We do not sell or rent your personal data to third parties. However, we may share your information with:</p>
            <ul class="list-disc pl-6 space-y-2 text-gray-700">
                <li><strong>Service Providers:</strong> Third-party services like Google Calendar integrations.</li>
                <li><strong>Legal Obligations:</strong> Authorities if required by law or to protect our rights.</li>
            </ul>
        </section>

        <section class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">4. Data Retention</h2>
            <p class="text-gray-700">We retain user account data and visitor interaction data for as long as necessary to provide our services. You can request data deletion at any time.</p>
        </section>

        <section class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">5. Your Data Rights</h2>
            <ul class="list-disc pl-6 space-y-2 text-gray-700">
                <li>Access and portability of your data.</li>
                <li>Correction or deletion of your data.</li>
                <li>Withdrawal of consent for data processing.</li>
            </ul>
            <p class="mt-4 text-gray-700">Contact us at <strong>contact@liveai.jp</strong> to exercise these rights.</p>
        </section>

        <section class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">6. Security Measures</h2>
            <p class="text-gray-700">We implement industry-standard security measures to protect your data but cannot guarantee absolute security due to the nature of the Internet.</p>
        </section>

        <section class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">7. Third-Party Integrations</h2>
            <p class="text-gray-700">Our platform integrates with third-party services like Google Calendar. By using these integrations, you agree to their respective privacy policies. We are not responsible for their practices.</p>
        </section>

        <section class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">8. Children’s Privacy</h2>
            <p class="text-gray-700">Our platform is not intended for individuals under 18. We do not knowingly collect data from minors.</p>
        </section>

        <section class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">9. Updates to This Privacy Policy</h2>
            <p class="text-gray-700">We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated "Effective Date." Continued use of the platform constitutes acceptance of the updated policy.</p>
        </section>

        <section class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">10. Contact Us</h2>
            <p class="text-gray-700">If you have any questions about this Privacy Policy, contact us at:</p>
            <ul class="list-disc pl-6 space-y-2 text-gray-700">
                <li><strong>Email:</strong> contact@liveai.jp</li>
                <!--<li><strong>Phone:</strong> [Insert Phone Number]</li>-->
            </ul>
        </section>
    </main>

    <footer class="bg-blue-600 text-white py-6">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; liveai.jp 2024. All Rights Reserved.</p>
        </div>
    </footer>
</body>

</html>
