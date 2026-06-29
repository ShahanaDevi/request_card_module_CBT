<?php

require_once 'Customer.php';
require_once 'CardRepository.php';

class CustomerRepository
{
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

        $statement = $this->connection->prepare($query);
        $statement->bind_param('s', $account_number);
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
         
        $statement = $this->connection->prepare($query);

        $name = $customer->getName();
        $mobile = $customer->getMobile();
        $account_number = $customer->getAccountNumber();

        $statement->bind_param('sss',$name, $mobile, $account_number);
        
        $statement->execute();

    }
}
?>