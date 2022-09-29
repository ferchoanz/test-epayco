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
        $menssge = method_exists($this->resource, 'errors') ?  $this->errors()->all() : $this->getMessage();
        $code = method_exists($this->resource, 'getStatusCode') ? $this->getStatusCode() : "00";

        return [
            'success' => false,
            'cod_error' => $code,
            'message_error' => $menssge,
        ];
    }
}
