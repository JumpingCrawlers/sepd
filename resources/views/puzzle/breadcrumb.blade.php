<div class="container px-0">
    <div class="row my-2 px-3">
        <div class="col-sm-8 text-left">
            @if (isset($miga_pan))

                @if ($miga_pan != '-')
                
                {{ $miga_pan }}
                
                @endif

            @elseif (isset($pagina))

                {{ $pagina->miga_pan }}

            @endif
        </div>
    </div>
</div>
