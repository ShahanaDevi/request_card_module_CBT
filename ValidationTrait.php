<?php

trait ValidationTrait
{
    public function normalizeString(string $_value): string
    {
        return strtolower(trim($_value));
    }

    public function isNameMatch(string $input_name, string $stored_name): bool
    {
        return $this->normalizeString($input_name) === $this->normalizeString($stored_name);
    }
}
