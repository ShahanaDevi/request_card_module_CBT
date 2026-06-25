<?php
require_once 'Card.php';
require_once 'ArrayConvertion.php';

class DebitCard extends Card implements ArrayConvertion
{
	public static function fromArray(array $data): DebitCard
	{
		return new DebitCard($data['cardNumber'], $data['isactive'], $data['expirationDate']);
	}

	public function getCardType(): string
	{
		return 'Debit';
	}

	public function toArray(): array
	{
		return [
			'cardType' => 'Debit',
			'cardNumber' => $this->card_number,
			'isactive' => $this->is_active,
			'expirationDate' => $this->expiration_date
		];
	}
}
