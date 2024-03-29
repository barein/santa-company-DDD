<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateGiftRequest\UserInterface\Api;

use Symfony\Component\Validator\Constraints\NotBlank;

class CreateGiftRequestDto
{
    #[NotBlank]
    public string $giftName;
}
