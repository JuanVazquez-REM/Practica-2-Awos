<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Post; //direccionamiento a la clase post

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */
//post debbug
//Route::get('posts_log/', 'PostController@index');


Route::post('/registro', 'UserController@registro')->middleware('verificaedad','minimopassword','validacionemail'); //listo
Route::post('/login', 'UserController@login'); //listo


//GroupMiddleware  SANCTUM
Route::middleware(['auth:sanctum'])->group(function () {

    /////RUTAS USER\\\\\

    //Logout
    Route::delete('/logout', 'UserController@logout'); //LISTO
    //Ver mi user
    Route::post('/miuser', 'UserController@miuser'); //LISTO

    //TABLA POSTS 
        //Insertar post
        Route::post('post', 'PostController@insertar')->middleware('minimocontenido',); //listo
        //Ver todos los posts
        Route::post('posts/', 'PostController@posts'); //LISTO
        //Ver post en especifico
        Route::post('post/{id}', 'PostController@post_id'); // LISTO
        //Todos los users y sus posts asociados
        Route::post('posts/users/all', 'UserController@users_posts'); //listo
        //Posts de un determinado user
        Route::post('posts/user/{id}', 'UserController@posts_user_id'); //listo


    //TABLA COMMENTS 
        //Insertar comment
        Route::post('comment', 'CommentController@insertar')->middleware('minimocontenido',); //LISTo
        //Comentarios de un determinado post
        Route::post('comments/post/{id}', 'CommentController@comments_posts_id');//LISTO
        //Todos los posts con sus respectivos comentarios
        Route::post('posts/comments/all', 'PostController@posts_comments'); //listo


        /////RUTAS AMINISTRADOR\\\\\

    //TABLA USERS
       //Ver todos los users
        Route::post('/users', 'UserController@user'); //listo
        //ver un user en especifico
        Route::post('user/{id}', 'UserController@user_id'); //listo
        //Actualizar user
        Route::put('user/{id}', 'UserController@actualizar')->middleware('verificaedad','validacionemail','minimopassword');  //listo
        //Eliminar user
        Route::delete('user/{id}', 'UserController@borrar_user'); //SQLSTATE[23000]: Integrity constraint violation: 1451

    //TABLA POSTS
        
        //Actualizar un post en especifico
        Route::put('post/{id}', 'PostController@actualizar')->middleware('minimocontenido'); //listo
        //Eliminar un post en especifico
        Route::delete('post/{id}', 'PostController@borrar_post'); //listo
    
    //TABLA COMMENTS 
        //Ver un comentario en especifico
        Route::post('comment/{id}', 'CommentController@comment_id'); //listo
        //Ver todos los comentarios
        Route::post('comments/', 'CommentController@comments'); //listo
        //Actualizar un comentario en especifico
        Route::put('comment/{id}', 'CommentController@actualizar')->middleware('minimocontenido');  //LISTO
        //Eliminar un comentario en especifico
        Route::delete('comment/{id}', 'CommentController@borrar_comment'); //LISTO

    //TABLA PERSONAS 
        // Ver todas las personas
        Route::post('personas/', 'PersonaController@personas'); //listo
        //Ver una persona en especifico
        Route::post('persona/{id}', 'PersonaController@persona_id'); //listo
        // Actualizar persona
        Route::put('persona/{id}', 'PersonaController@actualizar')->middleware('verificaedad','validacionemail'); //listo
        //Eliminar persona 
        Route::delete('persona/{id}', 'PersonaController@borrar_persona'); //SQLSTATE[23000]: Integrity constraint violation: 1451


    //TABLA 
    Route::post('rol/{id}', 'UserController@rol_id'); //listo

});



