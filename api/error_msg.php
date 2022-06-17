<?php

function echo_error($error, $attempt, $correction) {
    echo json_encode([
        'error' => $error,
        'attempt' => $attempt,
        'correction' => $correction,
    ]);
}
