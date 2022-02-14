<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Siempre que quiera saber si esta autenticado
use Illuminate\Support\Facades\Auth;
//Estos 2 son para el manejo de los archivos
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
//Devolver archivos
use Illuminate\Http\Response;
//Cargo el modelo
use App\User;

class UserController extends Controller
{
    // Este constructor es para que solo las personas autenticadas/identificadas logren llegar hasta aca
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index($search = null){
        if($search){
            $users = User::where('nick','LIKE','%'.$search.'%')
                    ->orWhere('name','LIKE','%'.$search.'%')
                    ->orWhere('surname','LIKE','%'.$search.'%')
                    ->orderBy('id','desc')
                    ->paginate(5);
        }else{
            $users = User::orderBy('id','desc')->paginate(5);
        }
        
        return view('user.index',[
            'users'=>$users
        ]);
    }

    public function config(){

        return view('user.config');
    }

    public function update(Request $request){

        //Conseguir el usuario identificado
        $user = Auth::user();
        $id = Auth::user()->id;

        //Validacion del formulario
        $validate = $this->validate($request,[
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            //Esto es para que busque en la tabla user el nick y sea unico, pero solo lo permita pasar cuando
            // ese nick cooncuerda con el nick que tiene el usuario activo en ese momento
            'nick' => 'required|string|max:255|unique:users,nick,'.$id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$id
        ]);

        //Recoger los valores del formulario POST
        $name = $request->input('name');
        $surname = $request->input('surname');
        $email = $request->input('email');
        $nick = $request->input('nick');


        //Asiganar los nuevos valores al user
        $user->name = $name;
        $user->surname = $surname;
        $user->email = $email;
        $user->nick = $nick;

        //Subir la imagen
        $image_path = $request->file("image_path");
        if($image_path){
            //Coloca un nombre unico
            $image_name = time().$image_path->getClientOriginalName();
            //Guarda la imagen en storage/users
            Storage::disk('users')->put($image_name,File::get($image_path));
            //Coloco el nombre en el usuario DB
            $user->image = $image_name;
        }

        //Ejecutar cambios en la base de datos
        $user->update();

        return redirect()->route('config')
                ->with(['mensaje' => 'Actualizado correctamente']);
    }

    //Obtener la foto de perfil del usuario
    public function getImage($filename){
        $file = Storage::disk('users')->get($filename);
        return new Response($file,200);
    }

    public function profile($id){
		$user = User::find($id);
		
		return view('user.profile', [
			'user' => $user
		]);
	}


}
