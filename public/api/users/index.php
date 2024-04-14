<?php
include('../../../src/UserIndex.php');

$data = json_decode(file_get_contents('../../../data/MOCK_DATA.json'));
$userIndex = new UserIndex($data, $_GET);

header('Content-Type: Application/JSON');
echo json_encode([
    '_links' => $userIndex->getLinks(),
    'users' => $userIndex->getData(),
]);
