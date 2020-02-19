<?php

namespace Sharifi\Bundle\SymfonyValidationBundle\Tests\Feature;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormRequestResolveTest extends WebTestCase
{
    /**
     * @test
     */
    public function resolve_form_request_successfully_and_return_errors(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/user');

        self::assertJsonStringEqualsJsonString(
            json_encode([
                'name'  => ['This value should not be blank.'],
                'email' => ['This value should not be blank.']
            ]),
            $client->getResponse()->getContent()
        );
    }

    /**
     * @test
     */
    public function resolve_form_request_successfully_and_return_validated_request_parameters(): void
    {
        $client = static::createClient();

        $body = [
            'name'  => 'John Doe',
            'email' => 'john@gmail.com'
        ];

        $client->request('POST', '/api/user', $body);

        self::assertJsonStringEqualsJsonString(
            json_encode($body),
            $client->getResponse()->getContent()
        );
    }
}
