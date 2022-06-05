<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class StorePostRequest extends AbstractRequestPayload
{
    protected function constraints(): Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'title' => [
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                    new Assert\Length(['min' => 3, 'max' => 100]),
                ],
                'body' => [
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                ],
            ],
        ]);
    }
}
