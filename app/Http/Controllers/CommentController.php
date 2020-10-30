<?php

namespace App\Http\Controllers;

use DB;
use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function comments(Request $request){
        if($request->user()->tokenCan('rol:admin')){

            $comments = Comment::select()->get(); 
        return response()->json($comments, 200); 
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
        
    }

    public function comment_id(Request $request, $id){ 
        if($request->user()->tokenCan('rol:admin')){

            $comments = Comment::select()->where('id',$id)->get(); 
            return response()->json($comments, 200);
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
        
    }

    public function insertar(Request $request){
        if($request->user()->tokenCan('rol:user')||$request->user()->tokenCan('rol:admin')){
            $request->validate([
                'post_id'=> 'required',
                'nombre'=> 'required',
                'contenido'=> 'required',
            ]);
    
            $request_comment = new Comment; 
    
            $request_comment->post_id = $request->input('post_id'); 
            $request_comment->nombre = $request->input('nombre');
            $request_comment->user_id = $request->user()->id;
            $request_comment->contenido = $request->input('contenido');
    
            $request_comment->save();  
    
            return response()->json($request,201); 
        }else{
            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
        
    }

    public function actualizar(Request $request,$id){
        if($request->user()->tokenCan('rol:admin')){

            $request->validate([
                'post_id'=> 'required',
                'nombre'=> 'required',
                'user_id'=> 'required',
                'contenido'=> 'required',
            ]);

            $post_id = $request->input('post_id'); 
            $nombre = $request->input('nombre');
            $user_id = $request->input('user_id');
            $contenido = $request->input('contenido');

            Comment::where('id', $id)
            ->update(['post_id'=>$post_id,'nombre'=>$nombre,'user_id'=> $user_id ,'contenido'=> $contenido]);

            return response()->json($request,200);
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
        
    }

    public function borrar_comment(Request $request, $id){
        if($request->user()->tokenCan('rol:admin')){

            $comment = Comment::where('id','=',$id); 
            $comment->delete(); 
            
            return response()->json();   
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
    }


    public function comments_posts_id(Request $request,$id){
        if($request->user()->tokenCan('rol:user')||$request->user()->tokenCan('rol:admin')){

        //Mostrar los comentarios de un determinado posts
        $comments = DB::table('comments')
        ->join('posts', 'posts.id', '=' , 'comments.post_id')
        ->where('posts.id', '=' , $id)
        ->select('comments.*')
        ->get();
        
        return response() ->json($comments,200);
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }

    }


    public function comments_user_id($id){
        //Mostrar los comentarios de un determinado persona
        $comments = DB::table('comments')
        ->join('personas', 'personas.id', '=' , 'comments.persona_id')
        ->where('personas.id', '=' , $id)
        ->select('comments.*')
        ->get();

        return response() ->json($comments,200);
    }

    public function comments_personas(){
        $comment = Persona::with('comments')->get(); //Mostrar la tabla users con sus respestivos posts asociados, en formato json  

        return response()->json($comment,200);
    }

}
