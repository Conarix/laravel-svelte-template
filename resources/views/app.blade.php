<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    @routes
    @vite('resources/css/app.css')
    @vite('resources/js/app.ts')
    <title></title>
    @inertiaHead

    <script>
        (function () {
            const appearance = '{{ $appearance ?? 'system' }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>
</head>
<body class="font-sans antialiased min-h-screen">
@inertia
</body>
</html>
