# Provides a Form Request Validation for Symfony

## Introduction
This package allows you to validate request parameters based on your rules and restrictions via form requests. Form requests are custom request classes that contain validation logic. 

### Installation
You can install the package via composer:

```shell
composer require sharifi/symfony-validation-bundle
```

### Creating Form Requests
To start, create a class which extends from `Sharifi\Bundle\SymfonyValidationBundle\Requests\FormRequest` and add your validation rules to the rules method:

```php
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sharifi\Bundle\SymfonyValidationBundle\Requests\FormRequest;

class StoreBlogPost extends FormRequest
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
        ];
    }
}
```

### Use a Form Request
So, how are the validation rules evaluated? All you need to do is type-hint the request on your controller method. The incoming form request is validated before the controller method is called, meaning you do not need to clutter your controller with any validation logic:

```php
/**
 * Store the incoming blog post.
 */
public function store(StoreBlogPost $request): Response
{
    // The incoming request is valid...

    // Retrieve the validated input data...
    $validated = $request->validated();
}
```
