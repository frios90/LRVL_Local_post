<?php //b4f2e6d787e3632e35b6465fb958eef5

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateLengthObject implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        // \Log::debug(count($value));
        // \Log::debug($value);
        $response = true;
        foreach ($value as $key => $row) {
            \Log::debug($key);
            if (isset($row['id'])) {  
                $response = false;
            } 
        }
        return$response;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
