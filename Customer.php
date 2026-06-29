<?php

require_once 'DebitCard.php';
require_once 'CreditCard.php';

class Customer 
{
	public const ACCOUNT_NUMBER = 'account_number';
	public const NAME = 'name';
	public const MOBILE = 'mobile';
	public const AADHAR = 'aadhar';
	public const PAN = 'pan';
	public const CARDS = 'cards';


	private int $id;
	private string $account_number;
	private string $name;
	private string $mobile;
	private string $aadhar;
	private string $pan;
	private array $cards;

	public function __construct(int $id, string $account_number, string $_name, string $_mobile, string $_aadhar, string $_pan, array $_cards = [])
	{
		$this->id = $id;
		$this->account_number = $account_number;
		$this->name = $_name;
		$this->mobile = $_mobile;
		$this->aadhar = $_aadhar;
		$this->pan = $_pan;
		$this->cards = $_cards;
	}

	public static function fromArray(array $data, array $cards = []): Customer
	{
		
		return new Customer($data['id'], $data[self::ACCOUNT_NUMBER], $data[self::NAME], $data[self::MOBILE], $data[self::AADHAR], $data[self::PAN], $cards);
	}

	public function toArray(): array
	{
		$cards = [];

		foreach ($this->cards as $card) {
			$cards[] = $card->toArray();
		}
		return [
			'id' => $this->id,
			self::NAME => $this->name,
			self::MOBILE => $this->mobile,
			self::AADHAR => $this->aadhar,
			self::PAN => $this->pan,
			self::ACCOUNT_NUMBER => $this->account_number,
			self::CARDS => $cards
		];
	}

	/**
	 * Getter ans Setter
	 */
	public function getId(): int
	{
		return $this->id;
	}

	public function getAccountNumber(): string
	{
		return $this->account_number;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getMobile(): string
	{
		return $this->mobile;
	}

	public function getCards(): array
	{
		return $this->cards;
	}


	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function setMobile(string $mobile): void
	{
		$this->mobile = $mobile;
	}

	public function setCards(array $cards): void
	{
		$this->cards = $cards;
	}
}
