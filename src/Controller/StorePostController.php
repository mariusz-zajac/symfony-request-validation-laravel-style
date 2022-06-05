<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\StorePostRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
final class StorePostController
{
    #[Route('/post', name: 'post_store', methods: ['POST'])]
    public function __invoke(StorePostRequest $request): JsonResponse
    {
        // The incoming request is valid...

        // Retrieve the validated data...
        $data = $request->all();

        // Or retrieve a portion of the validated data...
        // $data = [
        //     'title' => $request->get('title', 'Default title'),
        //     'body' => $request->get('body'),
        // ];

        // You can also retrieve the base Symfony request
        // $symfonyRequest = $request->getMainRequest();

        return new JsonResponse($data, Response::HTTP_CREATED);
    }
}
