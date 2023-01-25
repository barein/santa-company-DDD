<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

interface ChildRepositoryInterface
{
    public function add(Child $child): void;
}
