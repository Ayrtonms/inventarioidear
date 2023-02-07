<?php

class ValidationException extends Exception {
    public $errors;

    public function __construct($errors, $message = '', $code = 0) {
        parent::__construct($message, $code);

        $this->errors = $errors;
    }
}

?>