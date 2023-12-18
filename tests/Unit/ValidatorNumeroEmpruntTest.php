<?php

namespace App\Tests\Unit;

use App\Services\ValidatorNumeroEmprunt;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ValidatorNumeroEmpruntTest extends TestCase
{
    private ValidatorNumeroEmprunt $validator;

    #[test]
    public function Validate_ValidNumeroEmprunt_True()
    {
        $result = (new ValidatorNumeroEmprunt) -> validate("EM-000000001");
        $this->assertTrue($result);
    }

    #[test]
    public function Validate_TropCourtNumeroEmprunt_Exception()
    {
        $this->expectExceptionMessage("12 caractères");
        $result = (new ValidatorNumeroEmprunt) -> validate("EM-00000001");
    }

    #[test]
    public function Validate_CommencePasParEMNumeroEmprunt_Exception()
    {
        $this->expectExceptionMessage("doit commencer par");
        $result = (new ValidatorNumeroEmprunt) -> validate("EM/000000001");
    }

    #[test]
    public function Validate_NeFinisPasPar9ChiffresNumeroEmprunt_Exception()
    {
        $this->expectExceptionMessage("doit finir par");
        $result = (new ValidatorNumeroEmprunt) -> validate("EM-0000a0001");
    }
}

