<?php

namespace App\Core;

/**
 * Request validation
 */
class Validator
{
    /**
     * Default errors messages
     *
     * @var array|string[]
     */
    protected const ERROR_MESSAGES = [
        'required' => 'This field is required',
        'email' => 'Email is not correct',
        'max' => 'This field must contain no more than %s characters',
    ];

    protected array $rules;
    protected array $parameters;
    protected array $messages;
    protected array $errors = [];
    protected bool $validated = true;

    /**
     * @param array $parameters
     * @param array $rules
     * @param array $messages
     */
    public function __construct(array $parameters, array $rules, array $messages = [])
    {
        $this->rules = $rules;
        $this->parameters = $parameters;
        $this->messages = $messages;

        $this->validate();
    }

    /**
     * Required validation
     *
     * @param $value
     * @return bool
     */
    private function validateRequired($value): bool
    {
        return !(mb_strlen((string)$value, 'UTF-8') === 0);
    }

    /**
     * Email validation
     *
     * @param $value
     * @return bool
     */
    private function validateEmail($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Maximum string length validation
     *
     * @param $value
     * @param $count
     * @return bool
     */
    private function validateMax($value, $count): bool
    {
        if (mb_strlen((string)$value, 'UTF-8') > (int)$count) {
            return false;
        }

        return true;
    }

    /**
     * Make validation for all field
     *
     * @return void
     */
    private function validate(): void
    {
        foreach ($this->rules as $fieldName => $rules) {
            $fieldValue = $this->parameters[$fieldName] ?? null;

            foreach ($rules as $key => $value) {
                $ruleName = is_int($key) ? $value : $key;
                $explanation = !is_int($key) ? $value : null;

                if (!$this->make($fieldValue, $ruleName, $explanation)) {
                    $this->addError($fieldName, $ruleName, $explanation);
                    $this->validated = false;
                }
            }
        }
    }

    /**
     * Make validation for field
     *
     * @param $value
     * @param string $rule
     * @param $explanation
     * @return bool
     */
    private function make($value, string $rule, $explanation = null): bool
    {
        $methodName = 'validate' . ucfirst($rule);

        return $explanation !== null ? $this->{$methodName}($value, $explanation) : $this->{$methodName}($value);
    }

    /**
     * Add error
     *
     * @param string $fieldName
     * @param string $ruleName
     * @param $explanation
     * @return void
     */
    private function addError(string $fieldName, string $ruleName, $explanation = null): void
    {
        $message = $this->messages[$ruleName] ?? self::ERROR_MESSAGES[$ruleName];

        $this->errors[$fieldName] = $explanation ? sprintf($message, $explanation) : $message;
    }

    /**
     * Get after errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Has errors
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !$this->validated;
    }

    /**
     * Get all validated parameters
     *
     * @return array
     */
    public function validatedParameters(): array
    {
        return array_intersect_key($this->parameters, $this->rules);
    }
}