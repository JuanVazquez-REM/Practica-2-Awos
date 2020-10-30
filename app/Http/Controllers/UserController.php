<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function miuser(Request $request){
        if($request->user()->tokenCan('rol:user')||$request->user()->tokenCan('rol:admin')){

            return response()->json(["perfil" => $request->user()],200);
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
        
    }

    public function user(Request $request){
        if($request->user()->tokenCan('rol:admin')){
            $users = User::all(); 
            return response()->json($users, 200); 
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
    }

    public function user_id(Request $request,$id){ 
        if($request->user()->tokenCan('rol:admin')){
            $users = User::select()->where('id',$id)->get(); 
            return response()->json($users, 200);
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }

        return abort(401,"Spcope invalido"); 
    }

    public function insertar(Request $request){
        $request_user = new User; 

        $request_user->name = $request->input('name'); 
        $request_user->email = $request->input('email');
        $request_user->password = Hash::make($request->input('password'));

        $request_user->save();  

        return response()->json($request,201); 
    }

    public function actualizar(Request $request,$id){
        if($request->user()->tokenCan('rol:admin')){

            $request->validate([
                'name'=> 'required',
                'email'=> 'required',
                'password'=> 'required',
            ]);

            $name = $request->input('name'); 
            $email = $request->input('email');
            $password = Hash::make($request->input('password'));
            
    
            User::where('id', $id)->update(['name'=>$name,'email'=> $email ,'password'=> $password]);
            return response()->json($request,200);
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }

        
    }

    public function borrar_user(Request $request,$id){
        if($request->user()->tokenCan('rol:admin')){
            $user = User::where('id','=',$id); 
            $user->delete(); 
            
            return response()->json();  
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
    
    }

    public function posts_user_id(Request $request, $id){
        if($request->user()->tokenCan('rol:user') || $request->user()->tokenCan('rol:admin')){

            //Mostrar los posts de un determinado user
            $posts = DB::table('users')
            ->join('posts', 'users.id', '=' ,'posts.user_id')
            ->where('users.id', '=' , $id)
            ->select('posts.*')
            ->get();
            return response() ->json($posts,200);
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
    }

    public function users_posts(Request $request){
        if($request->user()->tokenCan('rol:admin')){

            $posts = User::with('post')->get(); //Mostrar la tabla users con sus respestivos posts asociados, en formato json  
            return response()->json($posts,200);
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
    
    }

    
    public function registro(Request $request){

        $request->validate([
            'nombre'=> 'required',
            'apellidos'=> 'required',
            'email'=> 'required|email',
            'edad'=> 'required',
            'genero'=> 'required',
            'password'=> 'required',
        ]);

        $persona = new Persona;

        $persona->nombre = $request->input('nombre'); 
        $persona->apellidos = $request->input('apellidos'); 
        $persona->email = $request->input('email');
        $persona->edad = $request->input('edad');
        $persona->genero = $request->input('genero');
        
        if($persona->save()){

            $user = new User; 
            $user->persona_id = $persona->id;
            $user->name = $persona->nombre;
            $user->email = $persona->email;
            $user->password = Hash::make($request->input('password'));

            if($user->save() && $request->rol == 1){
                return response()->json(["Admin"=>$user],201); 
            }
            if($user->save()){
                return response()->json(["User"=>$user],201); 
            }
        }

        return abort(400, "Error al registrar"); 
    }

    public function login(Request $request){

        $request->validate([
            'email'=> 'required|email',
            'password'=> 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if(! $user || ! Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['Email o password incorrectos'],
            ]);
        }

        if($request->rol == 1){
            $token = $user->createToken($request->email,['rol:admin','rol:user'])->plainTextToken; //Crea el token y se asignan permisos donde el request->email sea igual al que estan en la bd, despue retornas el token en texto plano 
        }else{
            $token = $user->createToken($request->email,['rol:user'])->plainTextToken; //Crea el token y se asignan permisos donde el request->email sea igual al que estan en la bd, despue retornas el token en texto plano 
        }
        
        return response()->json(["token" => $token],201);
    }


    public function logout(Request $request){
        return response()->json(["Tokens eliminados" => $request->user()->tokens()->delete()],200);
    }

    public function rol_id(Request $request,$id){
        if($request->user()->tokenCan('rol:admin')){
            $sql = DB::table('personal_access_tokens')->select('abilities')->where('tokenable_id',$id)->get();

            return response()->json($sql,200);
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
        
    }



}

