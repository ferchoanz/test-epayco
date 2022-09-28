<?php
namespace App\Soap;

class SoapProvider
{
    public function hola()
    {
        return "hola". print_r(func_get_args(), true);
    }

    public function registro_cliente()
    {
        return [
            "mensaje" => "registro cliente"
        ];
    }
}