<?php
namespace App\core;

class Validation
{
    private array $errors = [];

    public function validate(array $data, array $rules): bool
    {
        foreach ($rules as $field => $fieldRules) {
            $value = trim($data[$field] ?? '');

            foreach ($fieldRules as $rule) {
                if ($rule === 'required') $this->required($field, $value);
                elseif ($rule === 'string') $this->string($field, $value);
                elseif ($rule === 'email') $this->email($field, $value);
                elseif ($rule === 'numeric') $this->numeric($field, $value);
                elseif ($rule === 'phone') $this->phone($field, $value);
                elseif ($rule === 'date') $this->date($field, $value);
                elseif (str_starts_with($rule, 'min:')) $this->min($field, $value, $rule);
                elseif (str_starts_with($rule, 'match:')) $this->match($field, $value, $data, $rule);
                elseif (str_starts_with($rule, 'in:')) $this->in($field, $value, $rule);
            }
        }

        return empty($this->errors);
    }

    private function required(string $field, $value): void
    {
        if (empty($value)) {
            $this->addError($field,"$field is required");
        }
    }

    private function string(string $field, $value): void
    {
        if (is_numeric($value)) {
            $this->addError($field, "$field must be a valid string");
        }
    }

    private function email(string $field, $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "Invalid email address");
        }
    }

    private function numeric(string $field, $value): void
    {
        if (!is_numeric($value)) {
            $this->addError($field,"$field must be numeric");
        }
    }

    private function phone(string $field, $value): void
    {
        if (!preg_match('/^01[0125][0-9]{8}$/', $value)) {
            $this->addError($field, "Invalid phone number format");
        }
    }

    private function min(string $field, $value, $rule): void
    {
        $min = (int)explode(':', $rule)[1];
        if (strlen($value) < $min) {
            $this->addError($field,"$field  must be at least $min characters");
        }
    }

    private function date(string $field, $value): void
    {
        $d = \DateTime::createFromFormat('Y-m-d', $value);
        if (!$d || $d->format('Y-m-d') !== $value) {
            $this->addError($field," $field must be a valid date (YYYY-MM-DD)");
        }
    }

    private function match(string $field, $value, $data, $rule): void
    {
        $matchField = explode(':', $rule)[1];
        if ($value !== ($data[$matchField] ?? null)) {
            $this->addError($field,"$field must match $matchField");
        }
    }

    private function in(string $field, $value, $rule): void
    {
        $allowed = explode(',', explode(':', $rule)[1]);
        if (!in_array($value, $allowed)) {
            $this->addError($field,"$field must be one of: " . implode(', ', $allowed));
        }
    }

    public function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
