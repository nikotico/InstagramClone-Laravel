@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @include('includes.mensaje')

            <div class="card">
                <div class="card-header">Editar imagen</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('image.update')}}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="image_id" value="{{$image->id}}"/>


                        <div class="form-group row">
                            <label for ="description" class="col-md-3 col-form label text-md-right">Descripcion</label>
                            <div class="col-md-7">
                                <textarea id ="description" name="description" class="form-control" required> {{$image->description}}</textarea>
                                @if($errors->has('description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{$errors->first('description')}}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    Actualizar
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
