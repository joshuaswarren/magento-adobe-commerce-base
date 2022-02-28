<?php

namespace Creatuity\Base\Setup\Type;


interface PropertyValidatorInterface
{
    public function validate();
    public function validateIfNotEmpty();
}