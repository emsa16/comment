<?php

namespace Emsa;

class Session
{
    public function has($key)
    {
        if ($key == 'admin') {
            return false;
        }
    }
}
