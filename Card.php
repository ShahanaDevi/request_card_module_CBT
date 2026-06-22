<?php

class Card
{
	private string $card_type;
	private string $card_number;
	private bool $is_active;
	private string $expiration_date;

	public function __construct(string $card_type, string $card_number, bool $is_active, string $expiration_date)
	{
		$this->card_type = $card_type;
		$this->card_number = $card_number;
		$this->is_active = $is_active;
		$this->expiration_date = $expiration_date;
	}

	public static function fromArray(array $card): Card
	{
		return new Card($card['cardType'], $card['cardNumber'], $card['isactive'], $card['expirationDate']);
	}

	public function toArray(): array
	{
		return [
			'cardType' => $this->card_type,
			'cardNumber' => $this->card_number,
			'isactive' => $this->is_active,
			'expirationDate' => $this->expiration_date
		];
	}

	/**
	 * Getter and Setter
	 */
	public function getCardType(): string
	{
		return $this->card_type;
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

?>