<?php

namespace Sharifi\Bundle\SymfonyValidationBundle\Requests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

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

        $this->validate($this->request->all());
    }

    /**
     * @param $request
     *
     * @return $this
     */
    public function validate($request): self
    {
        $validator     = Validation::createValidator();
        $rules         = $this->rules();
        $request       = $this->mergeRulesAndRequest($request, $rules);
        $violationList = [];

        foreach ($request as $key => $value) {
            if (array_key_exists($key, $rules)) {
                $validated = $validator->validate($value, $rules[$key]);

                if ($validated->count()) {
                    $violationList[$key] = $validated;
                } else {
                    $this->validated[$key] = $value;
                }
            }
        }

        if ($violationList) {
            $this->prepareErrors($violationList);
        }

        return $this;
    }

    /**
     * @param array $violationList
     */
    protected function prepareErrors(array $violationList): void
    {
        foreach ($violationList as $field => $violations) {
            foreach ($violations as $index => $violation) {
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
