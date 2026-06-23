<?php

abstract class Card
{

	protected string $card_number;
	protected bool $is_active;
	protected string $expiration_date;

	public function __construct(string $card_number, bool $is_active, string $expiration_date)
	{
		$this->card_number = $card_number;
		$this->is_active = $is_active;
		$this->expiration_date = $expiration_date;
	}

	public static function generateCardNumber(): String
	{
		return (string) rand(1000000000000000, 9999999999999999);
	}

	abstract public function getCardType(): string;

	abstract public function toArray(): array;

	/**
	 * Getter and Setter
	 */
	public function getCardNumber(): string
	{
		return $this->card_number;
	}

	public function isActive(): bool
	{
		return $this->is_active;
	}

	public function getExpirationDate(): string
	{
		return $this->expiration_date;
	}

	public function setActive(bool $status): void
	{
		$this->is_active = $status;
	}
}
