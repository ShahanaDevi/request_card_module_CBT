<?php

require_once '../Database.php';
require_once '../Customer.php';
require_once '../CustomerRepository.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$accountNumber = $data['account_number'] ?? '';
$name = $data['name'] ?? '';

$database = new Database();
$customerRepository = new CustomerRepository($database->getConnection());

$customer = $customerRepository->findByAccountNumber($accountNumber);

if ($customer == null) {

    echo json_encode(["status" => false,"message" => "Customer not found"]);
    exit;
}

if (strcasecmp($customer->getName(), $name) != 0) {

    echo json_encode(["status" => false,"message" => "Name does not match"]);
    exit;
}

echo json_encode(["status" => true, "message" => "Customer verified","customer" => ["id" => $customer->getId(),"account_number" => $customer->getAccountNumber(),"name" => $customer->getName(),"mobile" => $customer->getMobile()]]);

?>