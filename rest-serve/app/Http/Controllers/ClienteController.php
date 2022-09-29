<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use Error;
use Exception;
use Illuminate\Http\Request;
use SoapClient;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    protected SoapClient $clienteSoap;

    public function __construct()
    {
        $this->clienteSoap = new SoapClient(null, ["location" =>  "http://localhost:8001", "uri" => "http://localhost:8001"]);
    }

    public function registro_cliente(Request $request)
    {
        $parametros = $request->all();

        $validador = Validator::make($parametros, [
            'documento' => 'required',
            'nombres' => 'required',
            'email' => 'required',
            'celular' => 'required'
        ]);

        if ($validador->fails()) {
            return new ErrorResource($validador);
        }

        try {
            $respuesta = $this->clienteSoap->__call('registro_cliente', [
                $parametros["documento"],
                $parametros["nombres"],
                $parametros["email"],
                $parametros["celular"]
            ]);

            if (is_bool($respuesta->resource) && $respuesta->resource) {
                return new SuccessResource($respuesta);
            } else {
                abort(500, $respuesta->resource->message);
            }

        } catch (Exception $error) {
            return new ErrorResource($error);
        }
    }

    public function recarga_billetera(Request $request)
    {
        $parametros = $request->all();
        try {

            $validador = Validator::make($parametros, [
                'documento' => 'required',
                'celular' => 'required',
                'valor' => 'required'
            ]);
    
            if ($validador->fails()) {
                return new ErrorResource($validador);
            }

            $respuesta = $this->clienteSoap->__call('recarga_billetera', [
                $parametros["documento"],
                $parametros["celular"],
                $parametros["valor"]
            ]);
            
            if (is_bool($respuesta->resource) && $respuesta->resource) {
                return new SuccessResource(true);
            } else {
                abort(404, $respuesta->resource->message);
            }

        } catch(Exception $error) {
            return new ErrorResource($error);
        }
    }
}
