<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\PagoResource;
use App\Http\Resources\SaldoResource;
use App\Http\Resources\SuccessResource;
use App\Mail\VerificarPago;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
                abort($respuesta->resource->statusCode ?? 00, $respuesta->resource->message);
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
                abort($respuesta->resource->statusCode ?? 00, $respuesta->resource->message);
            }
        } catch (Exception $error) {
            return new ErrorResource($error);
        }
    }

    public function pagar(Request $request)
    {
        $parametros = $request->all();
        try {

            $validador = Validator::make($parametros, [
                'documento' => 'required',
                'celular' => 'required',
                'valor' => 'required',
                'session_id' => 'required'
            ]);

            if ($validador->fails()) {
                return new ErrorResource($validador);
            }

            $respuesta = $this->clienteSoap->__call('pagar', [
                $parametros["documento"],
                $parametros["celular"],
                $parametros["valor"],
                $parametros['session_id']
            ]);

            

            if (is_array($respuesta->resource)) {
                Mail::to($respuesta->resource['email'])->send(new VerificarPago($respuesta->resource['token'], $respuesta->resource['session_id']));
                return new PagoResource(true);
            } else {
                abort($respuesta->resource->statusCode ?? 00, $respuesta->resource->message);
            }
        } catch (Exception $error) {
            return new ErrorResource($error);
        }
    }

    public function consultar_saldo(Request $request)
    {
        $parametros = $request->all();
        try {

            $validador = Validator::make($parametros, [
                'documento' => 'required',
                'celular' => 'required',
            ]);

            if ($validador->fails()) {
                return new ErrorResource($validador);
            }

            $respuesta = $this->clienteSoap->__call('consultar_saldo', [
                $parametros["documento"],
                $parametros["celular"]
            ]);


            if (is_array($respuesta->resource)) {
                return new SaldoResource(['saldo' => $respuesta->resource['saldo']]);
            } else {
                abort($respuesta->resource->statusCode ?? 00, $respuesta->resource->message);
            }
        } catch (Exception $error) {
            return new ErrorResource($error);
        }
    }
}
