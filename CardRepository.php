<?php

class CardRepository
{
    private mysqli $connection;
    

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getCardsByCustomerId(int $customer_id): array
    {
         $query = "SELECT * FROM cards WHERE customer_id = ?";
         
         $statement = $this->connection->prepare($query);
         
         $statement->bind_param('i', $customer_id);
         
         $statement->execute();

        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function saveCard(int $customer_id, Card $card): void
    {
        $query = "INSERT INTO cards(customer_id, card_type, card_number, is_active, expiration_date) VALUES(?, ?, ?, ?, ?)";

        $statement = $this->connection->prepare($query);

        $card_type = $card->getCardType();
        $card_number = $card->getCardNumber();
        $is_active = $card->isActive();
        $expiration_date = $card->getExpirationDate();

        $statement->bind_param('issis', $customer_id, $card_type, $card_number, $is_active, $expiration_date);
        $statement->execute();
    }
}
?>