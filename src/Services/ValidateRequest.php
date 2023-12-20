<?php

namespace App\Services;

use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class ValidateRequest
{
    private ValidatorInterface $validator;


    public function __construct()
    {
        $this -> validator = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();
    }

    /**
     * @throws Exception
     */
    public function execute($request): true
    {
        $errors = $this -> validator -> validate($request);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error -> getMessage();
            }

            throw new Exception(implode("<br>", $messages));
        }
        return true;
    }
}