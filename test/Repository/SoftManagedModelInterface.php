<?php

namespace LRC\Repository;

/**
 * Interface for managed soft-deletion-aware repository models.
 */
interface SoftManagedModelInterface
{
    public function setReferences($references);
}
