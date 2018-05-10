<?php

namespace LRC\Form;

/**
 * Data-bound model form.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class ModelForm
{
    // public function __construct($id, $model = null)
    // {
    //     $this->id = $id;
    //     $this->model = (is_object($model) ? $model : (!is_null($model) ? new $model() : null));
    //     $this->extra = [];
    //     $this->errors = [];
    // }
    //
    // public function getExtra($field, $default = null)
    // {
    //     return (array_key_exists($field, $this->extra) ? $this->extra[$field] : $default);
    // }
    //
    // public function populateModel($include = null, $exclude = null, $source = null)
    // {
    //     // null check
    //     if (is_null($this->model)) {
    //         return null;
    //     }
    //
    //     // determine which properties to bind
    //     $props = (is_array($include) ? $include : array_keys(get_object_vars($this->model)));
    //     if (is_array($exclude)) {
    //         $props = array_diff($props, $exclude);
    //     }
    //
    //     // bind properties from provided data source
    //     if (is_null($source)) {
    //         $source = $_POST;
    //     }
    //     foreach ($source as $param => $value) {
    //         if (in_array($param, $props)) {
    //             // save model property
    //             if ($value === '' && $this->model->isNullable($param)) {
    //                 $value = null;
    //             }
    //             $this->model->$param = $value;
    //         } else {
    //             // save extraneous parameter
    //             $this->extra[$param] = $value;
    //         }
    //     }
    //
    //     return $this->model;
    // }
    //
    // public function validate()
    // {
    //     if (is_null($this->model) || $this->model->isValid()) {
    //         $this->errors = [];
    //     } else {
    //         $this->errors = array_merge($this->errors, $this->model->getValidationErrors());
    //     }
    // }
    //
    // public function isValid()
    // {
    //     return empty($this->errors);
    // }
    //
    // public function hasError($prop)
    // {
    //     return isset($this->errors[$prop]);
    // }
    //
    // public function getError($prop)
    // {
    //     return (isset($this->errors[$prop]) ? $this->errors[$prop] : null);
    // }
    //
    // public function input($prop, $type, $attrs = [])
    // {
    //     $attrs['type'] = $type;
    //     if ($type != 'checkbox' && $type != 'radio') {
    //         $attrs['value'] = (strtolower($type) != 'password' ? $this->getFieldValue($prop) : '');
    //     }
    //     return '<input ' . $this->getAttributeString($prop, $attrs) . '>';
    // }
    //
    // public function textarea($prop, $attrs = [])
    // {
    //     return '<textarea '.$this->getAttributeString($prop, $attrs).'>'.$this->getFieldValue($prop).'</textarea>';
    // }
    //
    // private function getAttributeString($prop, $attrs, $isField = true)
    // {
    //     // attributes for form fields
    //     if ($isField) {
    //         $attrs['id'] = $this->id . "-$prop";
    //         $attrs['name'] = $prop;
    //     }
    //
    //     // error detection
    //     if (!is_null($prop) && isset($this->errors[$prop])) {
    //         $attrs['class'] = (empty($attrs['class']) ? 'has-error' : $attrs['class'] . ' has-error');
    //     }
    //
    //     // generate string
    //     $res = [];
    //     foreach ($attrs as $attr => $val) {
    //         if (!is_bool($val)) {
    //             $res[] = $attr . '="' . htmlspecialchars($val) . '"';
    //         } elseif ($val === true) {
    //             $res[] = $attr;
    //         }
    //     }
    //     return implode(' ', $res);
    // }
    //
    // private function getModelValue($prop)
    // {
    //     if (!is_null($this->model) && isset($this->model->$prop)) {
    //         return $this->model->$prop;
    //     }
    //     return null;
    // }
    //
    // private function getFieldValue($field)
    // {
    //     $value = $this->getModelValue($field);
    //     return (!is_null($value) ? $value : $this->getExtra($field));
    // }
}
