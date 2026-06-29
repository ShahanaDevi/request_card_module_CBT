<?php

require_once '../Database.php';
require_once '../CustomerRepository.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$accountNumber = $data['account_number'] ?? '';
$name = $data['name'] ?? '';
$mobile = $data['mobile'] ??'';

$database = new Database();
$customerRepository = new CustomerRepository($database->getConnection());

$customer = $customerRepository->findByAccountNumber($accountNumber);

if ($customer == null) {

    echo json_encode(["status" => false,"message" => "Customer not found"]);
    exit;
}

$customer->setName($name);
$customer->setMobile($mobile);

$customerRepository->updateCustomer($customer);

echo json_encode(["status"=>true, "message"=>"Customer updated successfully"]);

?>

