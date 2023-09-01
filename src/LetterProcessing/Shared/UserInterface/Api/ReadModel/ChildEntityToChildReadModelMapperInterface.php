<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\UserInterface\Api\ReadModel;

use App\LetterProcessing\Shared\Domain\Child\ChildReadInterface;
use App\Shared\UserInterface\Api\ReadModel\ApiVersion;
use App\Shared\UserInterface\Api\ReadModel\VersionAwareInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface ChildEntityToChildReadModelMapperInterface extends VersionAwareInterface
{
    public function map(ChildReadInterface $childEntity, ApiVersion $requiredSubResourcesVersion): ChildReadModelInterface;
}
