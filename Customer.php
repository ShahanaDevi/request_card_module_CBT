<?php

class customer
{
	public const ACCOUNT_NUMBER = 'accountNo';
	public const NAME = 'name';
	public const MOBILE = 'mobile';
	public const AADHAR = 'aadhar';
	public const PAN = 'pan';
	public const CARDS = 'cards';
	

	private string $account_number;
	private string $name;
	private string $mobile;
	private string $aadhar;
	private string $pan;
	private array $cards;

	public function __construct(string $account_number, string $_name, string $_mobile, string $_aadhar, string $_pan, array $_cards = [])
	{
		$this->account_number = $account_number;
		$this->name = $_name;
		$this->mobile = $_mobile;
		$this->aadhar = $_aadhar;
		$this->pan = $_pan;
		$this->cards = $_cards;
	}

	public static function fromArray(string $account_number, array $data): Customer
	{
		return new Customer($account_number, $data[self::NAME], $data[self::MOBILE], $data[self::AADHAR], $data[self::PAN ], $data[self::CARDS]);
	}

	public function toArray(): array
	{
		return [
			self::NAME => $this->name,
			self::MOBILE => $this->mobile,
			self::AADHAR => $this->aadhar,
			self::PAN => $this->pan,
			self::CARDS => $this->cards
		];
	}

	/**
	 * Getter ans Setter
	 */
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
