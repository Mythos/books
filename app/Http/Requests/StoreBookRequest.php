<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Intervention\Validation\Rules\Isbn;
use Nicebooks\Isbn\Exception\InvalidIsbnException;
use Nicebooks\Isbn\Isbn as IsbnIsbn;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $series_id = $this->get('series_id');
        return [
            'publish_date' => 'date',
            'status' => 'required|integer|min:0',
            'isbn' => ['required', 'unique:books,isbn,NULL,id,series_id,'.$series_id, new Isbn()],
            'series_id' => 'required|exists:series,id'
        ];
    }

    protected function getValidatorInstance()
{
    $data = $this->all();
    try {
        if(!empty($data['isbn'])) {
            $data['isbn'] = IsbnIsbn::of($data['isbn'])->to13();
        }
    } catch (InvalidIsbnException $exception) {
    }
    $this->getInputSource()->replace($data);
    return parent::getValidatorInstance();
}
}
