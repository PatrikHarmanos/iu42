<!doctype html>
<html lang="sk">
<head>
    @include('layouts.partials.head')
</head>
<body>
<header>
    @include('layouts.partials.nav')
</header>

<main role="main">
    @yield('content')
</main>
<!-- Bootstrap core JavaScript -->
@include('layouts.partials.footer-scripts')

@include('layouts.partials.footer')
</body>
</html>
