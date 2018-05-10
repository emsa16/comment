<?php

namespace LRC\Repository;

class RepositoryManager
{
    public function createRepository($model, $config)
    {
        return new DbRepository($model, $config);
    }
}
