<?php

namespace App\Http\Controllers;

use App\Model\Tecnica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class TecnicaController extends Controller
{
    protected function guard()
    {
        return Auth::guard('api');
    }

    public function all(){
        return Tecnica::all();
    }
    public function getById($id){
        try{
            $obj = Tecnica::find($id);
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
                $obj = Tecnica::find($request->id);
            }else{
                $obj = new Tecnica();
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
            $obj=Tecnica::find($request->id);
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
