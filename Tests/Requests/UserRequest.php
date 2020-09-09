<?php

namespace Sharifi\Bundle\SymfonyValidationBundle\Tests\Requests;

use Sharifi\Bundle\SymfonyValidationBundle\Requests\FormRequest;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    protected function rules(): array
    {
        return [
            'name' => [
                new Length(['max' => 255]),
                new NotBlank(),
            ],
            'email' => [
                new Length(['max' => 255]),
                new Email(),
                new NotBlank(),
            ],
        ];
    }
}
