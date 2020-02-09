<?php

namespace Sharifi\Bundle\SymfonyValidationBundle\Tests\Controllers;

use Sharifi\Bundle\SymfonyValidationBundle\Tests\Requests\UserRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    /**
     * @Route("/api/user", methods={"POST"})
     */
    public function store(UserRequest $request): JsonResponse
    {
        return new JsonResponse($request->validated());
    }
}
