<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    

</head>


<body class="bg-black text-white">

    <!-- Navbar -->
    <nav class="flex items-center justify-between px-10 py-6 bg-black">

        <!-- Logo / Game Title -->
        <a href="/" class="text-2xl font-bold tracking-widest">
            NIR:MESIAS
        </a>


        <!-- Navigation -->
        <div class="flex items-center gap-10 text-sm tracking-widest">

            <a 
                href="/"
                class="text-gray-400 hover:text-white transition duration-300"
            >
                HOME
            </a>


            <a 
                href="/news"
                class="text-gray-400 hover:text-white transition duration-300"
            >
                NEWS
            </a>


            <a 
                href="/about"
                class="text-gray-400 hover:text-white transition duration-300"
            >
                ABOUT
            </a>

        </div>

    </nav>


    <!-- Content -->
    <main>

        @yield('content')

    </main>

</body>

</html>