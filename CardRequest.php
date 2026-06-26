<?php

require_once 'Customer.php';
require_once 'Card.php';
require_once 'ValidationTrait.php';
require_once 'Database.php';
require_once 'CustomerRepository.php';
require_once 'CardRepository.php';


class CardRequest
{

	use ValidationTrait;
	private array $accounts = [];
	private mysqli $connection;
	private CustomerRepository $customerRepository;
    private CardRepository $cardRepository;

	/**
	 * loads Account_details.json into $accounts
	 * @return void
	 */
	public function __construct()
	{

	    $database = new Database();
		$connection = $database->getConnection();
		echo "Database Connected\n";

        $this->customerRepository = new CustomerRepository($connection);
        $this->cardRepository = new CardRepository($connection);
		// $content = file_get_contents('Account_details.json');
		// // get data from DB
		// $customers = json_decode($content, true) ?? [];

		// foreach ($customers as $customer) {
		// 	// $customer
		// 	$account_number = $customer[Customer::ACCOUNT_NUMBER];
		// 	unset($customer[Customer::ACCOUNT_NUMBER]);

		// 	$this->accounts[$account_number] = $customer;
		// }
	}

	/**
	 * Executes and process the card request
	 * @return void
	 */
	public function run(): void
	{
		$_user = $this->getUserInput();

		$_customer = $this->validateCustomer($_user);

		// var_dump($_customer);die;

		$this->displayCustomer($_customer);

		$choice = $this->showMenu();

		switch ($choice) {
			case 1:
				$this->requestDebitCard($_customer);
				break;

			case 2:
				$this->customerRepository->updateCustomer($_customer);
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
		$customer = $this->customerRepository->findByAccountNumber($_user[Customer::ACCOUNT_NUMBER]);

    	if ($customer === null)
		{
			exit("Account not found.\n");
    	}

    	if (!$this->isNameMatch($_user[Customer::NAME], $customer->getName())) 
		{
			exit("Name does not match our records.\n");
		}
		
		return $customer;
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

		$new_card = new DebitCard(0, Card::generateCardNumber(), true, date('Y-m-d', strtotime('+3 years')));

		$cards[] = $new_card;

		$_customer->setCards($cards);

		$this->cardRepository->saveCard($_customer->getID(), $new_card);

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

		$new_card = new CreditCard(0, Card::generateCardNumber(), true, date('Y-m-d', strtotime('+3 years')));

		$cards[] = $new_card;

		$this->cardRepository->saveCard($_customer->getId(), $new_card);

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

		$customers = [];
		foreach($this->accounts as $account_number => $customer_data){
			$customer_data[CUSTOMER::ACCOUNT_NUMBER] = $account_number;
			$customers[] = $customer_data;
		}
		file_put_contents('Account_details.json', json_encode($customers, JSON_PRETTY_PRINT));
	}
}
