<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-xl mx-auto py-12 px-4">
        <h1 class="text-2xl font-semibold mb-6">Enviar Test Email</h1>

        @if (session('success'))
            <div id="success" class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div id="error" class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
        @endif

        @if ($errors && $errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('test.email.post') }}" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf

            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700">Client ID</label>
                <input id="client_id" name="client_id" type="text" value="{{ old('client_id', $client_id) }}"
                    required
                    class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx">
            </div>

            <div>
                <label for="tenant" class="block text-sm font-medium text-gray-700">Tenant</label>
                <input id="tenant" name="tenant" type="text" value="{{ old('tenant', $tenant) }}" required
                    class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="your-tenant-id-or-domain">
            </div>

            <div>
                <label for="client_secret" class="block text-sm font-medium text-gray-700">Client Secret</label>
                <input id="client_secret" name="client_secret" type="text"
                    value="{{ old('client_secret', $client_secret) }}" required
                    class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="********">
            </div>

            <div>
                <label for="to" class="block text-sm font-medium text-gray-700">Enviar a (email)</label>
                <input id="to" name="to" type="email" value="{{ old('to', $to) }}" required
                    class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="destinatario@dominio.com">
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Enviar
                </button>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="{{ route('test.email.post') }}"]');
            if (form) {
                form.addEventListener('submit', function() {
                    const success = document.getElementById('success');
                    if (success) success.remove();
                    const error = document.getElementById('error');
                    if (error) error.remove();
                });
            }
        });
    </script>
</body>

</html>
