<?php

require_once 'Card.php';
require_once 'ArrayConvertion.php';

class CreditCard extends Card implements ArrayConvertion
{
	public static function fromArray(array $data): CreditCard
	{
		return new CreditCard($data['id'], $data['cardNumber'], $data['isactive'], $data['expirationDate']);
	}

	public function getCardType(): string
	{
		return 'Credit';
	}

	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'cardType' => 'Credit',
			'cardNumber' => $this->card_number,
			'isactive' => $this->is_active,
			'expirationDate' => $this->expiration_date
		];
	}
}
