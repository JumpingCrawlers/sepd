@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => 'https://sepd.es'])
            <img src="https://www.sepd.es/storage/settings/January2024/fqg6wgZqtO1CnDihHx6I.png" class="img-fluid" alt="{{ setting('site.title') }}" width="300" height="auto">
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} SEPD. Todos los derechos quedan reservados.
        @endcomponent
    @endslot
@endcomponent
