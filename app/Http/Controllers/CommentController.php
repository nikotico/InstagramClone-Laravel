<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Siempre que quiera saber si esta autenticado
use Illuminate\Support\Facades\Auth;

use App\Comment;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function save(Request $request){
        //Valido mi formulario
        $validate = $this->validate($request,[
                    
            'content' => 'string|required',
            'image_id' => 'integer|required'
            ]);


        //Recibo los archivos
        $image_id = $request->input('image_id');
        $content = $request->input('content');

        //Creo un nuevo objeto
        $user = Auth::user();
        $comment = new Comment();//Ya con esto tengo acceso a la table images y sus atributos
        $comment->user_id = $user->id;
        $comment->image_id = $image_id;
        $comment->content = $content;

        //Guardo en la base de datos;
        $comment->save();

        //Redireccion
        return redirect()->route('image.detail',['id' => $image_id])
            ->with(['mensaje' => 'Comentario publicado']);
        }
    
    public function delete($id){
        //Datos el usuario actual
        $user = Auth::user();

        //Conseguir objeto del comentario
        $comment = Comment::find($id);

        //Comprobar si el usuario actual es dueño del comentario o publicacion
        if($user && ($comment->user_id == $user->id || $comment->image->user_id == $user->id)){
            $comment->delete();//Esto se puede hacer porque ya comment es un objeto

            return redirect()->route('image.detail',['id' => $comment->image->id])
                ->with(['mensaje' => 'Comentario eliminado']);

        }else{
            return redirect()->route('image.detail',['id' => $comment->image->id])
                ->with(['mensaje' => 'Comentario NO borrado']);

        }




    }
}
