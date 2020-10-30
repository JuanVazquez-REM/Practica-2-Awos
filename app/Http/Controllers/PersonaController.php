<?php

namespace App\Http\Controllers;

use DB;
use App\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    public function personas(Request $request){
        if($request->user()->tokenCan('rol:admin')){

            $personas = Persona::select()->get(); 
            return response()->json($personas, 200); 
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
    }

    public function persona_id(Request $request, $id){ 
        if($request->user()->tokenCan('rol:admin')){

            $personas = Persona::select()->where('id',$id)->get(); 
            return response()->json($personas, 200);
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
        
    }


    public function actualizar(Request $request,$id){
        if($request->user()->tokenCan('rol:admin')){

            $request->validate([
                'nombre'=> 'required',
                'apellidos'=> 'required',
                'email'=> 'required|email',
                'edad'=> 'required',
                'genero'=> 'required',
            ]);

            $nombre = $request->input('nombre'); 
            $apellidos = $request->input('apellidos');
            $email= $request->input('email');
            $edad = $request->input('edad');
            $genero = $request->input('genero');

            Persona::where('id', $id)
            ->update(['nombre'=>$nombre,'apellidos'=>$apellidos,'email'=> $email ,'edad'=> $edad, 'genero'=> $genero]);

            return response()->json($request,200);
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
    }

    public function borrar_persona(Request $request, $id){
        if($request->user()->tokenCan('rol:admin')){

            $Persona = Persona::where('id','=',$id); 
            $Persona->delete(); 
            
            return response()->json();   
        }else{

            return abort(
                response()->json(['Message' => 'Unauthorized'], 401)
            );
        }
    }

}
