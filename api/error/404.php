<?php
header('Content-Type: application/json');
echo json_encode(array(
    'error' => '404',
    'message' => 'Not Found'
));

