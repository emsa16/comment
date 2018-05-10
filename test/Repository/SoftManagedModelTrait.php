<?php

namespace LRC\Repository;

trait SoftManagedModelTrait
{
    public function setReferences($references)
    {
        $this->references = [];
        foreach ($references as $name => $ref) {
            $ref['key'] = 'id';
            $this->references[$name] = $ref;
        }
    }

    public function __get($attr)
    {
        foreach ($this->references as $name => $ref) {
            if (!empty($ref['magic']) && $attr === $name) {
                return new $ref['model']($this->{$ref['attribute']});
            }
        }
    }
}
