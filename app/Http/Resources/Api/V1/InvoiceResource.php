<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'reference_number' => $this->reference_number,
            'total_amount' => $this->total_amount,

            'status' => $this->status->display_name,

            'lines' => InvoiceLineResource::collection($this->lines),
            'available_gateways' => GatewayResource::collection($this->availableGateways())
        ];
    }
}
