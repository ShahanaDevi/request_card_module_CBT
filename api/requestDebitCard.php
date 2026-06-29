<?php

require_once '../Database.php';
require_once '../Customer.php';
require_once '../CustomerRepository.php';
require_once '../CardRepository.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$accountNumber = $data['account_number'] ?? '';
$name = $data['name'] ?? '';

$database = new Database();
$customerRepository = new CustomerRepository($database->getConnection());
$cardRepository = new CardRepository($database->getConnection());

$customer = $customerRepository->findByAccountNumber($accountNumber);

if ($customer == null) {

    echo json_encode(["status" => false,"message" => "Customer not found"]);
    exit;
}

if (strcasecmp($customer->getName(), $name) != 0) {

    echo json_encode(["status" => false,"message" => "Name does not match"]);
    exit;
}

foreach($customer->getCards() as $card){
    if ($card->getCardType() === 'Debit' && $card->isActive()) {
				$days_left = floor((strtotime($card->getExpirationDate()) - time()) / (60 * 60 * 24));

				if ($days_left > 30) {
					echo json_encode(["status"=>true, "message"=>"You already have an active  Debit Card."]);
					exit;
				}

				$card->setActive(false);
			}
}

$new_card = new DebitCard(0, Card::generateCardNumber(), true, date('Y-m-d', strtotime('+3 years')));

$cardRepository->saveCard($customer->getID(), $new_card);

echo json_encode(["status"=>true, "message"=>"Debit Card Issued Successfully"]);

?>

