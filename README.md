# Provides a Form Request Validation for Symfony

This package allows you to validate request parameters based on your rules and restrictions via form requests. Form requests are custom request classes that contain validation logic. 

## Basic Usage

```php
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Get the validation rules that apply to the request.
 */
protected function rules(): array
{
    return [
        'email' => [
            new Email(),
            new NotBlank(),
        ],
    ];
}
```

### Installation
You can install the package via composer:

```shell
composer require sharifi/symfony-validation-bundle
```
