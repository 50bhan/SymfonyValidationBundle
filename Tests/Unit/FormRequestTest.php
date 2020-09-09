<?php

namespace Sharifi\Bundle\SymfonyValidationBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sharifi\Bundle\SymfonyValidationBundle\Tests\Requests\UserRequest;

class FormRequestTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_validate_and_return_errors(): void
    {
        $request = new UserRequest();

        self::assertEquals(
            [
                'name' => ['This value should not be blank.'],
                'email' => ['This value should not be blank.'],
            ], $request->validated()
        );
    }

    /**
     * @test
     */
    public function it_can_validate_and_return_validated_request_parameters(): void
    {
        $body = [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
        ];

        $request = new UserRequest([], $body);

        self::assertEquals($body, $request->validated());
    }
}
