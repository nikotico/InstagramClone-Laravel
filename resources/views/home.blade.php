@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @include('includes.mensaje')
            
            @foreach ($images as $image)
                @include('includes.image',['image'=>$image]){{--Mostar la tarjeta de una imagen--}}
            @endforeach
            <!-- Paginacion -->
            <div class="clearfix">
                {{$images->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
