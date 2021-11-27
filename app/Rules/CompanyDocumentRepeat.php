<?php //b4f2e6d787e3632e35b6465fb958eef5

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\RequestPayment;
class CompanyDocumentRepeat implements Rule
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
        $validate_company_document = RequestPayment::where('number', '=', $request->input('number'))
        ->where('rut_company', '=', $request->input('rut_company'))
        ->count();
        if ($validate_company_document == 0) {
            return true;
        } else {
            return false;
        }
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
