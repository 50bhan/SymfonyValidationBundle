<?php

namespace Sharifi\Bundle\SymfonyValidationBundle\Requests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class FormRequest extends Request
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var array
     */
    protected $validated = [];

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * {@inheritDoc}
     */
    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->validator = Validation::createValidator();
        $this->rules     = $this->rules();
        $request         = $this->mergeRulesAndRequest($this->request->all(), $this->rules);
        $this->validate($request);
    }

    /**
     * @param $request
     */
    public function validate($request): void
    {
        $filteredRequest = array_filter($request, function ($parameter) {
            return array_key_exists($parameter, $this->rules);
        }, ARRAY_FILTER_USE_KEY);

        array_walk($filteredRequest, function ($value, $parameter) {
            $violations = $this->validator->validate($value, $this->rules[$parameter]);

            $violations->count() ?
                $this->addToErrors($parameter, $violations) :
                $this->addToValidated($parameter, $value);
        });
    }

    /**
     * This function will merge missing rules (from request) with current
     *  request object for further validation. This way validation will be based
     *  on rules and all rules will be considered.
     *
     * @param array $request
     * @param array $rules
     *
     * @return array
     */
    protected function mergeRulesAndRequest(array $request, array $rules): array
    {
        $rules = array_fill_keys(array_keys($rules), null);

        return array_merge($rules, $request);
    }

    /**
     * @param $parameter
     * @param $violations
     */
    protected function addToErrors($parameter, $violations): void
    {
        foreach ($violations as $violation) {
            /** @var ConstraintViolation $violation */
            $this->errors[$parameter][] = $violation->getMessage();
        }
    }

    /**
     * @param $parameter
     * @param $value
     */
    protected function addToValidated($parameter, $value): void
    {
        $this->validated[$parameter] = $value;
    }

    /**
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Return validated request parameters or errors.
     *
     * @return array
     */
    public function validated(): array
    {
        return empty($this->errors) ? $this->validated : $this->errors;
    }
}
