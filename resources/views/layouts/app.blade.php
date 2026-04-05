<!DOCTYPE html>
<html lang="id">
@include('partials.head')

<body class="bg-light d-flex flex-column min-vh-100">
    @include('partials.navbar')

    <main class="container my-4 flex-grow-1">
        @yield('content')
    </main>

    @include('partials.footer')
</body>

</html>