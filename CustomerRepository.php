<?php

require_once 'Customer.php';
require_once 'CardRepository.php';
require_once 'DatabaseTrait.php';

class CustomerRepository
{
    use DatabaseTrait;
    private mysqli $connection;
    private CardRepository $cardRepository;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
        $this->cardRepository = new CardRepository($connection);
    }

    public function findByAccountNumber(string $account_number): ?Customer
    {

        $query = "SELECT * FROM customers WHERE account_number = ?";

        $statement = $this->executeQuery($this->connection, $query, 's', [$account_number]);
        $statement->execute();

        $result = $statement->get_result();
        $data = $result->fetch_assoc();

        if(!$data){
            return null;
        }

        $cards = $this->cardRepository->getCardsByCustomerId($data['id']);

        return Customer::fromArray($data, $cards);
       
    }

    public function updateCustomer(Customer $customer): void
    {
        $query = "UPDATE customers SET name = ?, mobile = ? WHERE account_number = ?";

        
        $name = $customer->getName();
        $mobile = $customer->getMobile();
        $account_number = $customer->getAccountNumber();
        $statement = $this->executeQuery($this->connection, $query, 'sss',[$name, $mobile, $account_number]);


    }
}
?>