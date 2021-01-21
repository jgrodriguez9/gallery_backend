<?php

namespace App\Http\Controllers;

use App\Model\Categoria;
use App\Model\Obra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JD\Cloudder\Facades\Cloudder;
use Mockery\Exception;

class ObraController extends Controller
{
    protected function guard()
    {
        return Auth::guard('api');
    }

    public function all(Request $request){
        if(empty($request->query('query')) ||  $request->query('query')=='null'){
            return Obra::with('categoria', 'soporte', 'tecnica', 'tematica')
                ->simplePaginate(20);
        }else{
            return Obra::with('categoria', 'soporte', 'tecnica', 'tematica')
                ->where('name', 'like', '%'.$request->query('query').'%')
                ->simplePaginate(20);
        }
    }

    public function getById($id){
        try{
            $obj = Obra::find($id);
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
            //validamos data
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 200);
            }

            if(!empty($request->id)){
                $obra = Obra::find($request->id);
                $obra->name = $request->name;
                $obra->width = $request->width;
                $obra->height = $request->height;
                $obra->categoria_id = $request->categoria;
                $obra->soporte_id = $request->soporte;
                $obra->tecnica_id = $request->tecnica;
                $obra->tematica_id = $request->tematica;

                $file = $request->file;
                if(!empty($file)){
                    //eliminados la q esta arriba
                    Cloudder::delete($obra->public_id, null);

                    //add new
                    Cloudder::upload($file, null);
                    $w = 200;
                    $h = 200;
                    if($request->width < $request->height){
                        $w = 200;
                        $h = 200;
                    }

                    $image_url= Cloudder::secureShow(Cloudder::getPublicId(),
                        array("width" => $w, "height" => $h, "crop" => "fit"));

                    $obra->image = $image_url;
                    $obra->public_id = Cloudder::getPublicId();
                }


                $obra->save();
            }else{
                $obra = new Obra();
                $obra->name = $request->name;
                $obra->width = $request->width;
                $obra->height = $request->height;
                $obra->categoria_id = $request->categoria;
                $obra->soporte_id = $request->soporte;
                $obra->tecnica_id = $request->tecnica;
                $obra->tematica_id = $request->tematica;

                $file = $request->file;
                Cloudder::upload($file, null);
                $w = 200;
                $h = 200;
                if($request->width < $request->height){
                    $w = 200;
                    $h = 200;
                }
                $image_url= Cloudder::secureShow(Cloudder::getPublicId(),
                    array("width" => $w, "height" => $h, "crop" => "fill"));

                $obra->image = $image_url;
                $obra->public_id = Cloudder::getPublicId();
                $obra->save();
            }

            return response()->json([
                'message'=> 'Acción exitosa',
                'success'=>true,
                'data'=>$obra
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
            $obj=Obra::find($request->id);
            Cloudder::delete($obj->public_id, null);
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
