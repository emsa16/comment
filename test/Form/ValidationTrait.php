<?php

namespace LRC\Form;

/**
 * Model validation.
 */
trait ValidationTrait
{
    public function setValidation($rules)
    {
        $this->validationRules = $rules;
    }

    // public function isValid()
    // {
    //     $this->validate();
    //     return empty($this->validationErrors);
    // }
    //
    // public function getValidationErrors()
    // {
    //     $this->validate();
    //     return $this->validationErrors;
    // }
    //
    // private function validate()
    // {
    //     $this->validationErrors = [];
    //     foreach ($this->validationRules as $attr => $rules) {
    //         foreach ($rules as $rule) {
    //             $passed = true;
    //             if ($rule['rule'] == 'required') {
    //                 $passed = $this->hasValue($attr);
    //             } elseif ($this->hasValue($attr)) {
    //                 $passed = $this->validateRule($rule, $attr);
    //             }
    //             if (!$passed) {
    //                 $this->validationErrors[$attr] = $rule['message'];
    //             }
    //         }
    //     }
    // }
    //
    //
    // /**
    //  * @SuppressWarnings(PHPMD.CyclomaticComplexity)
    //  */
    // private function validateRule($rule, $attr)
    // {
    //     switch ($rule['rule']) {
    //         case 'number':
    //             $passed = is_numeric($this->$attr);
    //             break;
    //         case 'minlength':
    //             $passed = (mb_strlen($this->$attr) >= $rule['value']);
    //             break;
    //         case 'maxlength':
    //             $passed = (mb_strlen($this->$attr) <= $rule['value']);
    //             break;
    //         case 'minvalue':
    //             $passed = ($this->$attr >= $rule['value']);
    //             break;
    //         case 'maxvalue':
    //             $passed = ($this->$attr <= $rule['value']);
    //             break;
    //         case 'email':
    //             $passed = (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}/', $this->$attr) == 1);
    //             break;
    //         case 'forbidden-characters':
    //             $passed = (preg_match('/['.$rule['value'].']/', $this->$attr) == 0);
    //             break;
    //         case 'match':
    //             $passed = ($this->{$rule['value']} === $this->$attr);
    //             break;
    //         case 'custom':
    //             $passed = $rule['value']($attr, $this->$attr);
    //             break;
    //         default:
    //             $passed = false;
    //     }
    //     return $passed;
    // }
    //
    // private function hasValue($attr)
    // {
    //     return (isset($this->$attr) && $this->$attr !== '');
    // }
}
