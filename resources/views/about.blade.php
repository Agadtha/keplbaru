@extends('layout.app')


@section('title', 'Home')


@section('content')

<section>

    <details class="group border-b border-gray-600 py-4">
        <summary class="flex cursor-pointer items-center justify-between text-xl font-bold list-none">

            Tentang Game

            <span class="transition-transform group-open:rotate-180">
                ▼
            </span>

        </summary>

        <div class="mt-4 text-gray-400">
            <p>
                Game ini adalah game horror dengan konsep teatrikal.
                Pemain tidak dibuat merasa benar-benar masuk ke dunia game,
                tetapi seperti sedang memainkan sebuah pertunjukan.
            </p>
        </div>

    </details>

</section>

@endsection