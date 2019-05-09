<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ContestFormRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return Auth::check() && Auth::user()->permission > 0;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'title' => 'bail|required|string|max:255',
      'contest_info' => 'required|string|max:65000',
      'begin_time' => 'required|date',
      'end_time' => 'required|date|after:begin_time',
      'rule' => 'required|integer',
      'problemset' => ['required', 'string', 'max:1000']
    ];
  }
}
