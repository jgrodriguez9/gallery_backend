<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public $client;

    public function __construct()
    {
        $this->client = \Laravel\Passport\Client::where('password_client', 1)->first();
    }

    protected function guard()
    {
        return Auth::guard('api');
    }

    public function login(Request $request){
        try {
            $usuario = User::where('username', request('username'))->first();
            if (!$usuario) {
                return response()->json([
                    'message' => trans('Usuario no encontrado'),
                    'success' => false
                ], 200);
            }else {
                if (!\Hash::check(request('password'), $usuario->password)) {
                    return response()->json([
                        'message' => trans('Contraseña no coincide'),
                        'success' => false
                    ], 200);
                }
            }

            $request->request->add([
                'grant_type' => 'password',
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
                'username' => $request['username'],
                'password' => $request['password'],
            ]);

            // Fire off the internal request.
            $proxy = Request::create(
                'oauth/token',
                'POST'
            );
            $response =  \Route::dispatch($proxy);
            $json = (array) json_decode($response->getContent());
            $json['success'] = true;
            $json['username'] = $usuario->username;
            $response->setContent(json_encode($json));
            return $response;

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage() . $e->getFile() . $e->getLine(),
                'message' => trans('msgs.msg_error_contacte_al_adminitrador'),
                'type' => trans('msgs.type_error')
            ], 500);
        }
    }

    protected function refresh(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'refresh_token' => 'required',
        ]);
        if ($validacion->fails()) {
            return response()->json([
                'errors' => $validacion->errors(),
                'success' => false
            ], 200);
        }
        try {
            $request->request->add([
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->refresh_token,
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
            ]);

            $proxy = Request::create(
                '/oauth/token',
                'POST'
            );

            $response =  \Route::dispatch($proxy);
            $json = (array) json_decode($response->getContent());
            $json['success'] = true;
            $json['username'] = $request->username;
            $response->setContent(json_encode($json));
            return $response;

        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('msgs.msg_error_contacte_al_adminitrador'),
                'type' => trans('msgs.type_error')
            ], 500);
        }
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            $user->token()->revoke();
            $user->tokens()->delete();
            return response()->json([
                'message' => 'Cerró sesión correctamente',
                'success' => true
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('msgs.msg_error_contacte_al_adminitrador'),
                'type' => trans('msgs.type_error')
            ], 500);
        }
    }
}
