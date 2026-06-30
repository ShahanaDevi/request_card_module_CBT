<?php

require_once 'DebitCard.php';
require_once 'CreditCard.php';
require_once 'DatabaseTrait.php';

class CardRepository
{
    private mysqli $connection;
    use DatabaseTrait;


    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getCardsByCustomerId(int $customer_id): array
    {
        $query = "SELECT * FROM cards WHERE customer_id = ?";

        $statement = $this->executeQuery($this->connection,$query,'i',[$customer_id]);

        $result = $statement->get_result();

        $cards = [];

        while ($row = $result->fetch_assoc()) {
            if ($row['card_type'] == 'Debit') {
                $cards[] = DebitCard::fromArray(['id' => $row['id'], 'cardNumber' => $row['card_number'], 'isactive' => (bool)$row['is_active'], 'expirationDate' => $row['expiration_date']]);
            } else {
                $cards[] = CreditCard::fromArray(['id' => $row['id'], 'cardNumber' => $row['card_number'], 'isactive' => (bool)$row['is_active'], 'expirationDate' => $row['expiration_date']]);
            }
        }

        return $cards;
    }


    public function saveCard(int $customer_id, Card $card): void
    {
        $query = "INSERT INTO cards(customer_id, card_type, card_number, is_active, expiration_date) VALUES(?, ?, ?, ?, ?)";


        $card_type = $card->getCardType();
        $card_number = $card->getCardNumber();
        $is_active = $card->isActive();
        $expiration_date = $card->getExpirationDate();

        $statement = $this->executeQuery($this->connection,$query,'issis',[$customer_id, $card_type, $card_number, $is_active, $expiration_date]);
    }

    public function updateCardStatus(int $card_id,bool $status): void 
    {
        $query = "UPDATE cards SET is_active = ? WHERE id = ?";
        
        $this->executeQuery($this->connection, $query,'ii',[$status, $card_id]);
    }
}
