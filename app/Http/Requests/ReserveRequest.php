<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ReserveRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "table_number"=>["required", "numeric" ],
            "start_time"=>["required", Rule::date()->format("Y-m-d H:i"), "after:now",
            // "end_time"=>["required", "date", "after:start_time"],

            function ($attribute, $value, $fail) {
                
                $date=Carbon::parse($value);
                $hour=$date->hour;

                if($hour<12 || $hour>22){

                    $fail("A foglalás csak 12:00 és 22:00 óra között kezdődhet");
                    
                }

            }
            ]
        ];
    }
}
