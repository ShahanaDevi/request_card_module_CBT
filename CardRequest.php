<?php

require_once 'Customer.php';
require_once 'Card.php';

class CardRequest
{

	private array $accounts = [];

	/**
	 * loads Account_details.json into $accounts
	 * @return void
	 */
	public function __construct()
	{
		$content = file_get_contents('Account_details.json');
		$this->accounts = json_decode($content, true) ?? [];
	}

	/**
	 * Executes and process the card request
	 * @return void
	 */
	public function run(): void
	{
		$_user = $this->getUserInput();

		$_customer = $this->validateCustomer($_user);

		$this->displayCustomer($_customer);

		$choice = $this->showMenu();

		switch ($choice) {
			case 1:
				$this->requestDebitCard($_customer);
				break;

			case 2:
				$this->updateCustomer($_customer);
				break;

			case 3:
				$this->requestCreditCard($_customer);
				break;

			default:
				echo "Invalid choice.\n";
				return;
		}

		$this->saveCustomer($_customer);

		echo "Process completed successfully.\n";
	}

	/**
	 * gets the input from the user
	 * @return array 
	 */
	private function getUserInput(): array
	{
		echo "Enter Account Details\n";

		return [
			Customer::ACCOUNT_NUMBER => trim(readline("Enter Account Number: ")),
			Customer::NAME => trim(readline("Enter Name: "))
		];
	}

	/**
	 * Validates the input data with the existing account data
	 * @param array $_user 
	 * @return Customer
	 */
	private function validateCustomer(array $_user): Customer
	{

		if (!isset($this->accounts[$_user[Customer::ACCOUNT_NUMBER]])) {
			exit("Account not found.\n");
		}

		$_record = $this->accounts[$_user[Customer::ACCOUNT_NUMBER]];

		if (strtolower(trim($_user[Customer::NAME])) !== strtolower(trim($_record[Customer::NAME]))) {
			exit("Name does not match our records.\n");
		}

		return Customer::fromArray($_user[Customer::ACCOUNT_NUMBER], $_record);
	}

	/**
	 * Displays customer data
	 * @param Customer $_customer customer data
	 * @return void
	 */
	private function displayCustomer(Customer $_customer): void
	{
		echo "\nUser verified successfully.\n";

		echo "\nCustomer Details\n";
		echo "Account Number : " . $_customer->getAccountNumber() . "\n";

		echo "Customer Name : " . $_customer->getName() . "\n";
	}

	/**
	 * @return int
	 */
	private function showMenu(): int
	{
		echo "\n1. New Debit Card Request\n";
		echo "2. Update Customer Details\n";
		echo "3. New Credit Card Request\n";

		return (int) readline("Enter your choice: ");
	}

	/**
	 * Handles the Debit card logic
	 * @param Customer $_customer
	 * @return void
	 */
	private function requestDebitCard(Customer $_customer): void
	{
		$cards = $_customer->getCards();

		foreach ($cards as $card) {

			if ($card->getCardType() === 'Debit' && $card->isActive()) {
				$days_left = floor((strtotime($card->getExpirationDate()) - time()) / (60 * 60 * 24));

				if ($days_left > 30) {
					echo "You already have an active Debit Card. \n";
					return;
				}

				$card->setActive(false);
			}
		}

		$cards[] = new Card('Debit', (string) rand(1000000000000000, 9999999999999999), true, date('Y-m-d', strtotime('+3 years')));

		$_customer->setCards($cards);

		echo "New Debit Card Issued Successfully.\n";
	}

	/**
	 * Handles the Credit card logic
	 * @param Customer $_customer
	 * @return void
	 */
	private function requestCreditCard(Customer $_customer): void
	{
		$cards =  $_customer->getCards();

		foreach ($cards as  $card) {
			if ($card->getCardType() === 'Credit' && $card->isActive()) {
				$days_left = floor((strtotime($card->getExpirationDate()) - time()) / (60 * 60 * 24));

				if ($days_left > 30) {
					echo "You already have an active Credit Card. \n";
					return;
				}

				$card->setActive(false);
			}
		}

		$cards[] = new Card('Credit', (string) rand(1000000000000000, 9999999999999999), true, date('Y-m-d', strtotime('+3 years')));

		$_customer->setCards($cards);

		echo "New Credit Card Issued Successfully.\n";
	}

	/**
	 * updates the customer data
	 * @param Customer $_customer 
	 * @return void
	 */
	private function updateCustomer(Customer $_customer): void
	{
		$new_name = readline("Enter new name(Press Enter to skip): ");

		$new_mobile = readline("Enter new mobile(Press Enter to skip): ");

		if (!empty(trim($new_name))) {
			$_customer->setName($new_name);
		}

		if (!empty(trim($new_mobile))) {
			$_customer->setMobile($new_mobile);
		}

		echo "Customer details updated.\n";
	}

	/**
	 * saves the data back to JSON 
	 * @param Customer $_customer
	 * @return void
	 */
	private function saveCustomer(Customer $_customer): void
	{
		$this->accounts[$_customer->getAccountNumber()] = $_customer->toArray();

		file_put_contents('Account_details.json', json_encode($this->accounts, JSON_PRETTY_PRINT));
	}
}
