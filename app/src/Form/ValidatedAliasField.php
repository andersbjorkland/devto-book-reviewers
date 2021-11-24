<?php 

namespace App\Form;

use SilverStripe\Forms\TextField;
use SilverStripe\Security\Member;

class ValidatedAliasField extends TextField
{
    public function validate($validator)
    {
        $alias = $this->Value();
        $member = Member::get()->filter(['FirstName' => $alias])->first();

        if ($member) {
            $validator->validationError(
                $this->name,
                'Alias is already in use',
                'validation'
            );
            return false;
        }
        return true;
    }
}