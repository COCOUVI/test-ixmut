<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>IMUXT - Plateforme de Gestion des Employés et des Tâches</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<body class="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400">

    <main class="bg-gray-100">

        <div class="relative flex">

            <!-- Content -->
            <div class="w-full md:w-1/2">

                <div class="min-h-[100dvh] h-full flex flex-col after:flex-1">


                    <!-- Logo pour mobile uniquement -->
                    <div class="md:hidden bg-dark py-4 m-0 sticky top-0 z-50">
                        <div class="hidden md:flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('images/Logo IMUXT (Blanc).png') }}" alt="Logo" class="h-5 w-auto">
                            </a>
                        </div>
                        <div class="flex justify-center">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('images/Logo IMUXT (Blanc).png') }}" alt="Logo" class="h-5 w-auto">
                            </a>
                        </div>
                    </div>


                    <div
                        class="max-w-md mx-auto w-full h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 animate-slideFadeIn will-change-transform">
                        <div class="animate-float">
                            @yield('content')
                        </div>
                    </div>


                </div>

            </div>

            <!-- Image -->
            <div class="hidden md:flex flex-col absolute top-0 bottom-0 right-0 md:w-1/2 items-center justify-center bg-dark rounded-l-3xl border-8 border-r-0 border-dark"
                aria-hidden="true">



                <h1 class="text-3xl md:text-4xl text-white font-black mb-6">Bienvenue à vous</h1>
                <div>
                    <p class=" text-white text-sm mx-auto mb-8 text-center px-10">
                        Suivez efficacement en temps et en heures l'évolution de vos présences et de vos tâches
                        accomplies.
                    </p>
                </div>
                <img src="{{ asset('images/Logo IMUXT (Blanc).png')}}" alt="Authentication image"
                    class="h-10 w-auto animate-pulseZoom">

                    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

                <lottie-player src="lotties/BOOkVagD7J.json"
                    background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay>
                </lottie-player>
            </div>


        </div>

    </main>
</body>
</html>
