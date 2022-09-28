<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $menssge = method_exists($this->resource, 'getMessage') ? $this->getMessage() : $this->errors()->all();
        $code = method_exists($this->resource, 'getCode') ? $this->getCode() : "00";

        return [
            'success' => false,
            'cod_error' => $code,
            'message_error' => $menssge,
        ];
    }
}
