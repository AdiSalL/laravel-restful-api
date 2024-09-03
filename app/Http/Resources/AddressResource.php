<?php

namespace App\Http\Resources;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $user = Auth::user();
        // $contact = Contact::where("");
        return [
            "id" => $this->id,
            "street" => $this->street,
            "city" => $this->city,
            "province" => $this->province,
            "country" => $this->country,
            "postal_code" => $this->postal_code,
            
        ];
    }
}
