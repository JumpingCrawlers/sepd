{{-- 3 Ways - Alexis Bogado --}}
@extends('base.sepd')
@section('content')
<div class="container">
        <div class="col-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">Calculadora</h2>
            </div>

            <div class="row left-bordered">
                <div class="col-sm-12">
                    <iframe src="{{url('/')}}/calculadoras/" width="973" height="1200px" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection