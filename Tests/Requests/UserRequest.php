<?php

namespace Sharifi\Bundle\SymfonyValidationBundle\Tests\Requests;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sharifi\Bundle\SymfonyValidationBundle\Requests\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    protected function rules(): array
    {
        return [
            'name'  => [
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
