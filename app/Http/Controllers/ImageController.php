<?php

namespace App\Http\Controllers;

use App\Image;
use App\Like;
use App\Comment;

use Illuminate\Http\Request;

//Siempre que quiera saber si esta autenticado
use Illuminate\Support\Facades\Auth;

//Estos 2 son para el manejo de los archivos
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
//Devolver archivos
use Illuminate\Http\Response;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function create(){
        return view('image.create');
    }

    public function save(Request $request){

        //Valido mi formulario
        $validate = $this->validate($request,[
            
            'description' => 'required',
            'image_path' => 'required|file|mimes:jpg'
        ]);

        
        //Recibo los archivos
        $image_path = $request->file('image_path');
        $desc = $request->input('description');

        //Creo un nuevo objeto
        $user = Auth::user();
        $image = new Image();//Ya con esto tengo acceso a la table images y sus atributos
        $image->user_id = $user->id;
        $image->description = $desc;


        //Subir la imagen
        if($image_path){
            //Coloca un nombre unico
            $image_name = time().$image_path->getClientOriginalName();
            //Guarda la imagen en storage/users
            Storage::disk('images')->put($image_name,File::get($image_path));
            //Coloco el nombre en el usuario DB
            $image->image_path = $image_name;

            //Ingresar los datos en la base de datos
            $image->save();

            return redirect()->route('home')
                ->with(['mensaje' => 'Imagen subida correctamente']);
        }else{
        return redirect()->route('image.create')
                ->with(['mensaje' => 'Imagen fallida']);
        }
    }

    public function getImage($filename){
        $file = Storage::disk('images')->get($filename);
        return new Response($file,200);
    }

    public function detail($id){
        $image = Image::find($id);
        return view('image.detail',[
            'image' => $image
        ]);
    }

    public function delete($id){

        $user = Auth::user();
        $image = Image::find($id);

        //Tengo que sacar todo esto para que borrar la imagen no de problemas, ya que estan anidados
        $comments = Comment::where('image_id',$id)->get();
        $likes = Like::where('image_id',$id)->get();
        
        if($user && $image && $image->user->id == $user->id){
            //Eliminar los comentarios
            if($comments && count($comments) >=1){
                foreach($comments as $comment){
                    $comment->delete();
                }
            }
            //Eliminar los likes
            if($likes && count($likes) >=1){
                foreach($likes as $like){
                    $like->delete();
                }
            }
            //Eliminar la imagen del disco
            Storage::disk('images')->delete($image->image_path);

            //Eliminar de la base de datos
            $image->delete();
            $message = array('mensaje' => 'Imagen borrada');
        }else{
            $message = array('mensaje' => 'Imagen NO borrada');
        }
        return redirect()->route('home')
                ->with($message);
    }

    public function edit($id){
        $user = Auth::user();
        $image = Image::find($id);

        if($user && $image && $image->user->id == $user->id){
            return view('image.edit',['image' => $image]);
        }else{
            return redirect()->route('home');
        }
    }
    public function update(Request $request){
        
        //Recibo los archivos
        $image_id = $request->input('image_id');
        $desc = $request->input('description');

        //Conseguir el objeto en la base de datos
        $image = Image::find($image_id);
        $image->description = $desc;

        //Actualizar registro
        $image->update();

        return redirect()->route('image.detail',['id'=>$image_id])
                    ->with(['mensaje' => 'Imagen actualizada']);
    }
}
