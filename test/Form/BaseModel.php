<?php

namespace LRC\Form;

/**
 * Base class for models.
 */
class BaseModel implements ValidationInterface
{
    use ValidationTrait;

    // public function isNullable($attr)
    // {
    //     if ($attr) {
    //         return false;
    //     }
    // }
}
