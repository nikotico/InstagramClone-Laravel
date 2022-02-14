<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Siempre que quiera saber si esta autenticado
use Illuminate\Support\Facades\Auth;

//Devolver archivos
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;


use App\Like;


class LikeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
        $likes = Like::where('user_id',$user->id)
                    ->orderBy('id','desc')
                    ->paginate(4);//Cantidad que quiero mostar en la pagina
        return view('likes.index',[
            'likes' => $likes
        ]);
    }

    public function like($image_id){
        $user = Auth::user();


        //Saber si el usuario ya le dio like a esa publicacion
        $isset_like = Like::where('user_id',$user->id)
                        ->where('image_id',$image_id)
                        ->count();
        if($isset_like == 0){  
            //Crea el objeto
            $like = new Like();
            //Le asigna valores a ese objeto
            $like->user_id= $user->id;
            $like->image_id = (int)$image_id;

            //Guarda en la base de datos
            $like->save();

            return response()->json([
                'like' => $like
            ]);
        }else{
            return response()->json([
                'message' => 'no existe'
            ]);
        }
    }

    public function dislike($image_id){

        $user = Auth::user();


        //Saber si el usuario ya le dio like a esa publicacion
        $like = Like::where('user_id',$user->id)
                        ->where('image_id',$image_id)
                        ->first();

        if($like){               
            //Like ya es un objeto que viene de la base de datos
            $like->delete();

            return response()->json([
                'like' => $like,
                'message' => 'dislike'
            ]);
        }
    }



}
