<?php
/**
 * File name: UpdateWebsiteSlideRequest.php
 * Last modified: 2020.09.12 at 20:01:58
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\WebsiteSlide;

class UpdateWebsiteSlideRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return WebsiteSlide::$rules;
    }
}
