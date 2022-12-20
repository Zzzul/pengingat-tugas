<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Semester;

class SemesterKeMustBeUnique implements Rule
{
    public $id;
    public $semester_ke;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
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
        $semester = Semester::where([
            'user_id' => auth()->id(),
            'semester_ke' => $value,
        ])->first();

        if(!$semester){
            return true;
        }

        if($semester->id == $this->id){
            return true;
        }

        $this->semester_ke = $semester->semester_ke;

        if($semester && $this->id == 0){
            return false;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Semester ke $this->semester_ke is already exists.";
    }
}
