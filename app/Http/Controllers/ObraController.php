<?php

namespace App\Http\Controllers;

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

    public function all(){
        return Obra::paginate(20);
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

                $file = $request->file;
                Cloudder::upload($file, null);
                $image_url= Cloudder::secureShow(Cloudder::getPublicId(),
                    array("width" => 362, "height" => 204, "crop" => "fill"));

                $obra->image = $image_url;
                $obra->public_id = Cloudder::getPublicId();
                $obra->save();
            }else{
                $obra = new Obra();
                $obra->name = $request->name;

                $file = $request->file;
                Cloudder::upload($file, null);
                $image_url= Cloudder::secureShow(Cloudder::getPublicId(),
                    array("width" => 362, "height" => 204, "crop" => "fill"));

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
