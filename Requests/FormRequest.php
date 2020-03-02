<?php

namespace Sharifi\Bundle\SymfonyValidationBundle\Requests;

use Symfony\Component\HttpFoundation\Request;
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

        $this->validate($this->request->all());
    }

    /**
     * @param $request
     */
    public function validate($request): void
    {
        $request       = $this->mergeRulesAndRequest($request, $this->rules);
        $violationList = [];

        $filteredRequest = array_filter($request, function ($parameter) {
            return array_key_exists($parameter, $this->rules);
        }, ARRAY_FILTER_USE_KEY);

        array_walk($filteredRequest, function ($value, $parameter) {
            $validated = $this->validator->validate($value, $this->rules[$parameter]);

            if ($validated->count()) {
                $violationList[$parameter] = $validated;
            } else {
                $this->validated[$parameter] = $value;
            }
        });

        if ($violationList) {
            $this->prepareErrors($violationList);
        }
    }

    /**
     * @param array $violationList
     */
    protected function prepareErrors(array $violationList): void
    {
        foreach ($violationList as $field => $violations) {
            foreach ($violations as $index => $_) {
                $this->errors[$field][] = $violations[$index]->getMessage();
            }
        }
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
