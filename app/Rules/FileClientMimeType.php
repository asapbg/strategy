<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileClientMimeType implements Rule
{
    protected $mimeTypes;
    protected $field;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($allowedTypes)
    {
        $this->mimeTypes = $allowedTypes;
        $this->field = '';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->field = $attribute;
        if(!in_array($value->getClientMimeType(), $this->mimeTypes) && !in_array($value->getMimeType(), $this->mimeTypes)){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.mimes', ['attribute' => $this->field, 'values' => implode('|', \App\Models\File::ALL_ALLOWED_FILE_EXTENSIONS)]);
    }
}
