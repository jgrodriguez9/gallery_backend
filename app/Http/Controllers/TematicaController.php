<?php

namespace App\Http\Controllers;

use App\Model\Tematica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class TematicaController extends Controller
{
    protected function guard()
    {
        return Auth::guard('api');
    }

    public function all(){
        return Tematica::all();
    }

    public function getById($id){
        try{
            $obj = Tematica::find($id);
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
                $obj = Tematica::find($request->id);
            }else{
                $obj = new Tematica();
            }

            $obj->name = $request->name;
            $obj->save();
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

    public function delete(Request $request){
        try{
            $obj=Tematica::find($request->id);
            $obj->delete();
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
