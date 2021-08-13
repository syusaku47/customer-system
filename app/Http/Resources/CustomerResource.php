<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'sales_shop' => $this->sales_shop,
            'sales_contact' => $this->sales_contact,
            'name' => $this->name,
            'keisho' => $this->keisho,
            'furigana' => $this->furigana,
            'tel_no' => $this->tel_no,
            'mail_address' => $this->mail_address,
            'deficiency_flag' => $this->deficiency_flag,
            'post_no' => $this->post_no,
            'prefecture' => $this->prefecture,
            'city' => $this->city,
            'address' => $this->address,
            'building_name' => $this->building_name,
            'rank' => $this->rank,
            'estimated_rank' => $this->estimated_rank,
            'area' => $this->area,
            'building_category' => $this->building_category,
            'madori' => $this->madori,
            'building_age' => $this->building_age,
            'remarks' => $this->remarks,
            'friend_meeting' => $this->friend_meeting,
            'reform_album' => $this->reform_album,
            'case_permit' => $this->case_permit,
            'field_tour_party' => $this->field_tour_party,
            'lat' => $this->lat,
            'lng' => $this->lng,
        ];
    }
}
