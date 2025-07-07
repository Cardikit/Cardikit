<?php

namespace App\Core;

/**
* Validator class contains methods to validate user input.
*
* @package App\Core
*
* @since 0.0.1
*/
class Validator
{
    /**
    * Collection of validation errors.
    *
    * @var array
    *
    * @since 0.0.1
    */
    protected array $errors = [];

    /**
    * Collection of models for unique validation.
    *
    * @var array
    *
    * @since 0.0.1
    */
    protected array $models = [];

    /**
    * Injects models for unique validation.
    * Takes models as an array. Ex: ['App\Models\User' => $userModel]
    *
    * @param array $models
    *
    * @since 0.0.1
    */
    public function __construct(array $models = [])
    {
        $this->models = $models;
    }

    /**
    * Validates user input based on provided rules.
    *
    * @param array $data
    * @param array $rules
    *
    * @return bool
    *
    * @since 0.0.1
    */
    public function validate(array $data, array $rules): bool
    {
        // reset errors
        $this->errors = [];

        // separate string of rules
        foreach ($rules as $field => $ruleList) {
            $rules = explode('|', $ruleList);
            $value = $data[$field] ?? null;

            // loop through rules
            foreach ($rules as $rule) {
                if ($rule === 'required') {
                    $this->required($field, $value);
                }

                if ($rule === 'email') {
                    $this->email($field, $value);
                }

                if (str_starts_with($rule, 'min:')) {
                    $length = (int) explode(':', $rule)[1];
                    $this->min($field, $value, $length);
                }

                if (str_starts_with($rule, 'max:')) {
                    $length = (int) explode(':', $rule)[1];
                    $this->max($field, $value, $length);
                }

                if (str_starts_with($rule, 'type:')) {
                    $type = explode(':', $rule)[1];
                    $this->type($field, $value, $type);
                }

                if ($rule === 'confirmed') {
                    $this->confirmed($field, $data);
                }

                if (str_starts_with($rule, 'unique:')) {
                    $this->unique($field, $value, $rule);
                }
            }
        }

        return empty($this->errors);
    }

    /**
    * Adds error message if value is empty.
    *
    * @param string $field
    * @param mixed $value
    *
    * @return void
    *
    * @since 0.0.1
    */
    protected function required(string $field, mixed $value): void
    {
        if (empty($value)) {
            $this->addError($field, 'is required');
        }
    }

    /**
    * Adds error message if value is not
    * a valid email address.
    *
    * @param string $field
    * @param mixed $value
    *
    * @return void
    *
    * @since 0.0.1
    */
    protected function email(string $field, mixed $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'is invalid');
        }
    }

    /**
    * Adds error message if value is
    * shorter than specified length.
    *
    * @param string $field
    * @param mixed $value
    * @param int $length
    *
    * @return void
    *
    * @since 0.0.1
    */
    protected function min(string $field, mixed $value, int $length): void
    {
        if (strlen((string) $value) < $length) {
            $this->addError($field, 'is too short');
        }
    }

    /**
    * Adds error message if value is
    * longer than specified length.
    *
    * @param string $field
    * @param mixed $value
    * @param int $length
    *
    * @return void
    *
    * @since 0.0.1
    */
    protected function max(string $field, mixed $value, int $length): void
    {
        if (strlen((string) $value) > $length) {
            $this->addError($field, 'is too long');
        }
    }

    /**
    * Adds error message if value is
    * not of the specified type.
    *
    * @param string $field
    * @param mixed $value
    * @param string $type
    *
    * @return void
    *
    * @since 0.0.1
    */
    protected function type(string $field, mixed $value, string $type): void
    {
        $isValid = match ($type) {
            'string' => is_string($value),
            'int', 'integer' => filter_var($value, FILTER_VALIDATE_INT) !== false,
            'bool', 'boolean' => is_bool($value) || in_array($value, ['true', 'false', 0, 1, '0', '1'], true),
            'array' => is_array($value),
            default => true,
        };

        if (!$isValid) {
            $this->addError($field, "must be of type $type");
        }
    }

    /**
    * Adds error message if value does not
    * match the confirmation field value.
    *
    * @param string $field
    * @param array $data
    *
    * @return void
    *
    * @since 0.0.1
    */
    protected function confirmed(string $field, array $data): void
    {
        $confirmationField = $field . '_confirmation';
        if (!isset($data[$confirmationField]) || $data[$field] !== $data[$confirmationField]) {
            $this->addError($field, 'does not match confirmation');
        }
    }

    /**
    * $rule is in the format 'App\Models\YourModel:column'.
    * If no column is specified, it defaults to $field value.
    * Adds error if value is found in the database.
    *
    * @param string $field
    * @param mixed $value
    * @param string $rule
    *
    * @return void
    *
    * @since 0.0.1
    */
    protected function unique(string $field, mixed $value, string $rule): void
    {
        [$_, $modelName, $column] = explode(':', $rule . ':' . $field); // fallback to $field as column
        $model = $this->models[$modelName] ?? null;

        if ($model && method_exists($model, 'findBy')) {
            if ($model->findBy($column, $value)) {
                $this->addError($field, 'is already taken');
            }
        }
    }

    /**
    * Returns array of errors created by validations.
    *
    * @return array
    *
    * @since 0.0.1
    */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
    * Adds error message to errors array.
    *
    * @param string $field
    * @param string $message
    *
    * @return void
    *
    * @since 0.0.1
    */
    protected function addError(string $field, string $message): void
    {
        $this->errors[$field][] = ucfirst($field) . ' ' . $message;
    }
}
