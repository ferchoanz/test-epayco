<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
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
            return new SuccessResource($respuesta);
        } catch (Exception $error) {
            return new ErrorResource($error);
        }
    }
}
