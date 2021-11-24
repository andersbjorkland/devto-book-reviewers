<?php

namespace App\Controller;

use App\Form\ValidatedAliasField;
use App\Form\ValidatedEmailField;
use App\Form\ValidatedPasswordField;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Security\Member;

class RegistrationController extends ContentController
{   
    private static $allowed_actions = [
        'registerForm'
    ];
    
    public function registerForm()
    {
        $fields = new FieldList(
            ValidatedAliasField::create( 'alias', 'Alias')->addExtraClass('text'),
            ValidatedEmailField::create('email', 'Email'),
            ValidatedPasswordField::create('password', 'Password'),
        );

        $actions = new FieldList(
            FormAction::create(
                'doRegister',   // methodName
                'Register'      // Label
            )
        );

        $required = new RequiredFields('alias', 'email', 'password');

        $form = new Form($this, 'RegisterForm', $fields, $actions, $required);

        return $form;
    }

    public function doRegister($data, Form $form)
    {
        $alias = $data['alias'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        $validationResults = $form->validationResult();

        if ($validationResults->isValid()) {
            $member = Member::create();
            $member->FirstName = $alias;
            $member->Email = $email;
            $member->Password = $password;
            $member->write();

            $member->addToGroupByCode("reviewers");
            $member->write();

            $form->sessionMessage('Registration successful', 'good');
        }

        return $this->redirectBack();
    }
}