<?php

namespace App\Http\Controllers;

use App\Model\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class CategoriaController extends Controller
{
    protected function guard()
    {
        return Auth::guard('api');
    }

    public function all(){
        return Categoria::all();
    }

    public function getById($id){
        try{
            $obj = Categoria::find($id);
            return response()->json([
                'message'=> 'Acción exitosa',
                'success'=>true,
                'data'=>$obj
            ], 200);
        }catch (Exception $e){
            return response()->json([
                'message'=> '',
                'success'=>false
            ], 500);
        }
    }

    public function save(Request $request){
        try{
            if(!empty($request->id)){
                $c = Categoria::find($request->id);
            }else{
                $c = new Categoria();
            }

            $c->name = $request->name;
            $c->save();
            return response()->json([
                'message'=> 'Acción exitosa',
                'success'=>true,
                'data'=>$c
            ], 200);

        }catch (Exception $e){
            return response()->json([
                'message'=> '',
                'success'=>false
            ], 500);
        }
    }

    public function delete(Request $request){
        try{
            $c=Categoria::find($request->id);
            $c->delete();
            return response()->json([
                'message'=> 'Acción exitosa',
                'success'=>true,
            ], 200);
        }catch (Exception $e){
            return response()->json([
                'message'=> '',
                'success'=>false
            ], 500);
        }
    }
}
