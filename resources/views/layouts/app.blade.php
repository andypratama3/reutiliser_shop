<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Libre+Caslon+Text:ital,wght@0,400;0,700;1,400&family=Hanken+Grotesk:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('css')
</head>
<body class="bg-background text-on-background font-sans antialiased" x-data>
    @include('partials.navbar')

    <main class="min-h-screen pt-16">
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')

    <script>
        function confirmDelete(event, message = 'Are you sure?') {
            event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2a4a38',
                cancelButtonColor: '#605e59',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#2a4a38',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#2a4a38'
                });
            @endif
        });
    </script>
</body>
</html>
