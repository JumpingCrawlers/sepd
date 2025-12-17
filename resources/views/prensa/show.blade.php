@extends('puzzle.master')

@section('contenido')
<div class="container px-0 mt-2 mb-3 border border-primary">
    <div class="row">
        <div class="col-sm">
            <div class="card">
                <div class="card-header">{!! $nota->titulo !!}</div>

                <div class="card-body">
                    <p>{!! $nota->fecha !!}</p>
                    <p>{!! $nota->texto !!}</p>
                    <p><a href="{{ setting('site.url_web_antigua')."/contenido/prensa/".$nota->all_file }}">{!! $nota->all_file !!}</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
