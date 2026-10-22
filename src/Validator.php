<?php
/**
 * AMVAC 2027 — Input Validator & Sanitizer
 */

declare(strict_types=1);

class Validator {
    private array $errors = [];
    private array $sanitized = [];

    public function __construct(private array $data) {}

    public static function make(array $data): self {
        return new self($data);
    }

    public function sanitizeString(string $key, int $maxLength = 255): self {
        $val = trim((string)($this->data[$key] ?? ''));
        // Strip control characters while preserving UTF-8 letters
        $val = preg_replace('/[\x00-\x1F\x7F]/u', '', $val);
        if (mb_strlen($val) > $maxLength) {
            $val = mb_substr($val, 0, $maxLength);
        }
        $this->sanitized[$key] = $val;
        return $this;
    }

    public function required(string $key, string $message): self {
        $val = $this->sanitized[$key] ?? trim((string)($this->data[$key] ?? ''));
        if ($val === '') {
            $this->errors[$key] = $message;
        }
        return $this;
    }

    public function email(string $key, string $message): self {
        $val = $this->sanitized[$key] ?? trim((string)($this->data[$key] ?? ''));
        if ($val !== '' && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$key] = $message;
        } else {
            $this->sanitized[$key] = strtolower($val);
        }
        return $this;
    }

    public function inArray(string $key, array $allowed, string $message): self {
        $val = $this->sanitized[$key] ?? trim((string)($this->data[$key] ?? ''));
        if (!in_array($val, $allowed, true)) {
            $this->errors[$key] = $message;
        }
        return $this;
    }

    public function passes(): bool {
        return empty($this->errors);
    }

    public function errors(): array {
        return $this->errors;
    }

    public function value(string $key, mixed $default = null): mixed {
        return $this->sanitized[$key] ?? $this->data[$key] ?? $default;
    }

    public function sanitizedData(): array {
        return $this->sanitized;
    }
}
