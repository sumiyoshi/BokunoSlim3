<?php

namespace Core\Validator;

interface ValidatorInterface
{
    public function validate($data);
    public function getMessages();
}