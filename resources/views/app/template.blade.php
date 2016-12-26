<!DOCTYPE html>
<html>
<head>
    <title>Leadgen App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="/js/all.js" async></script>
</head>
<body>
    <div id="page" data-module="PageTransition">
        <header class="header">
            <div class="container">
                <a class="logo" href="{{ route("front.dashboard.home") }}">Multitude</a>
                <nav class="nav">
                    <a href="{{ route("front.dashboard.home") }}">Dashboard</a>
                    <a href="{{ route("front.customer.index") }}">Customers</a>
                    <a href="{{ route("front.segment.index") }}">Segments</a>
                    <a href="#">Interactions</a>
                    <a href="{{ route("front.integration.index") }}">Integrations</a>
                </nav>
            </div>
        </header>
        <div id="page-content">
            @yield('content')
        </div>
    </div>
    <link rel="stylesheet" href="/css/main.css">
</body>
</html>
