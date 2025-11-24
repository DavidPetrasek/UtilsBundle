<?php 
namespace Psys\UtilsBundle\Service;

use Psys\Utils\Result;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as ValConstr;

class Misc
{    
    function __construct
    (
        private ValidatorInterface $validator,
    )
    {}

    public function isEmailValid(string $email) : Result
    {
        $emailConstraint = new ValConstr\Email();        
        $errors = $this->validator->validate($email, $emailConstraint);
        
        if ($errors->count() === 0)
        {
            return new Result(true);
        }
        else
        {
            return new Result(false, $errors[0]->getMessage());
        }
    }
}

?>