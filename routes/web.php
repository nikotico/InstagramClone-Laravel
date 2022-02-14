<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//use App\Models\Image;

Route::get('/', function () {
    
    	/*
    Esto es un prueba del ORM de Laravel
    
	$images = Image::all();
	foreach($images as $image){
		echo $image->image_path."<br/>";
		echo $image->description."<br/>";
		echo $image->user->name.' '.$image->user->surname.'<br/>';
		
		if(count($image->comments) >= 1){
			echo '<h4>Comentarios</h4>';
			foreach($image->comments as $comment){
				echo $comment->user->name.' '.$comment->user->surname.': ';
				echo $comment->content.'<br/>';
			}
		}
		
		echo 'LIKES: '.count($image->likes);
		echo "<hr/>";
	}
	
	die();
	 */
    return view('welcome');
});

//GENERALES

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

//USUARIO
Route::get('/configuracion', 'UserController@config')->name('config');
Route::post('/user/edit', 'UserController@update')->name('user.update');
Route::get('/user/avatar/{filename}', 'UserController@getImage')->name('user.avatar');
Route::get('/perfil/{id}', 'UserController@profile')->name('profile');//Referencia de uso: home.blade.php div=card-body
Route::get('/personas/{search?}', 'UserController@index')->name('user.index');//Referencia de uso: home.blade.php div=card-body

//IMAGENES
Route::get('/image/subir', 'ImageController@create')->name('image.create');
Route::post('/image/save', 'ImageController@save')->name('image.save');
Route::get('/image/file/{filename}', 'ImageController@getImage')->name('image.file');//Referencia de uso: home.blade.php div=card-body
Route::get('/image/{id}', 'ImageController@detail')->name('image.detail');//Referencia de uso: home.blade.php div=card-body
Route::get('/image/delete/{id}', 'ImageController@delete')->name('image.delete');
Route::get('/image/edit/{id}', 'ImageController@edit')->name('image.edit');
Route::post('/image/update', 'ImageController@update')->name('image.update');

//COMENTARIOS
Route::post('/comment/save', 'CommentController@save')->name('comment.save');
Route::get('/comment/delete/{id}', 'CommentController@delete')->name('comment.delete');

//LIKES
Route::get('/like/{image_id}', 'LikeController@like')->name('like.save');
Route::get('/dislike/{image_id}', 'LikeController@dislike')->name('like.delete');
Route::get('/likes', 'LikeController@index')->name('like.index');





