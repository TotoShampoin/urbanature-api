<?php
header('Content-Type: application/json');
echo json_encode(array(
    'error' => '500',
    'message' => 'Internal Server Error'
));

