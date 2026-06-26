<?php

class CustomerRepository
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
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

        return Customer::fromArray($data['account_number'], $data);
       
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