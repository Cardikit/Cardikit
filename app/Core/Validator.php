<?php

namespace App\Core;

class Validator
{
    protected array $errors = [];

    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleList) {
            $rules = explode('|', $ruleList);
            $value = $data[$field] ?? null;

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

    protected function required(string $field, mixed $value): void
    {
        if (empty($value)) {
            $this->addError($field, 'is required');
        }
    }

    protected function email(string $field, mixed $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'is invalid');
        }
    }

    protected function min(string $field, mixed $value, int $length): void
    {
        if (strlen((string) $value) < $length) {
            $this->addError($field, 'is too short');
        }
    }

    protected function max(string $field, mixed $value, int $length): void
    {
        if (strlen((string) $value) > $length) {
            $this->addError($field, 'is too long');
        }
    }

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

    protected function confirmed(string $field, array $data): void
    {
        $confirmationField = $field . '_confirmation';
        if (!isset($data[$confirmationField]) || $data[$field] !== $data[$confirmationField]) {
            $this->addError($field, 'does not match confirmation');
        }
    }

    protected function unique(string $field, mixed $value, string $rule): void
    {
        [$_, $table, $column] = explode(':', $rule . ':' . $field); // fallback to $field as column

        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE {$column} = :value");
        $stmt->execute(['value' => $value]);

        if ($stmt->fetchColumn() > 0) {
            $this->addError($field, 'is already taken');
        }
    }

    public function errors(): array
    {
        return $this->errors;
    }

    protected function addError(string $field, string $message): void
    {
        $this->errors[$field][] = ucfirst($field) . ' ' . $message;
    }
}
