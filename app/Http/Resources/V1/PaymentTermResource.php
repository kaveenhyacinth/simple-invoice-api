<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTermResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $termStr = match($this->term) {
            "1" => "Net 1 Day",
            "7" => "Net 7 Days",
            "14" => "Net 14 Days",
            default => "Net 30 Days",
        };

        return [
            'id' => $this->id,
            'term' => (int)$this->term,
            'termStr' => $termStr,
        ];
    }
}
