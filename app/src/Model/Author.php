<?php

namespace App\Model;

use App\Admin\ReviewAdmin;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

class Author extends DataObject
{
    private static $table_name = "Author";

    private static $db = [
        'Name' => 'Varchar(255)',
        'GivenName' => 'Varchar(255)',
        'AdditionalName' => 'Varchar(255)',
        'FamilyName' => 'Varchar(255)',
    ];

    private static $belongs_many_many = [
        'Books' => Book::class
    ];

    private static $summary_fields = [
        'GivenName',
        'FamilyName',
    ];

    public function validate()
    {
        $result = parent::validate();

        if (!$this->GivenName) {
            $result->addError('GivenName is required');
        }

        if (!$this->FamilyName) {
            $result->addError('FamilyName is required');
        }

        return $result;
    }


    /**
    * @return string
    */
    public function getTitle()
    {
        $givenName = "";
        $familyName = "";

        $schema = static::getSchema();
        if ($schema->fieldSpec($this, 'GivenName')) {
            $givenName = $this->getField('GivenName');
        }
        if ($schema->fieldSpec($this, 'FamilyName')) {
            $familyName = $this->getField('FamilyName');
        }

        if ($givenName && $familyName) {
            return $givenName . ' ' . $familyName;
        } 

        return parent::getTitle();
    }

    public function canView($member = null) 
    {
        return Permission::check('CMS_ACCESS_' . ReviewAdmin::class, 'any', $member);
    }

    public function canEdit($member = null) 
    {
        return Permission::check('CMS_ACCESS_' . ReviewAdmin::class, 'any', $member);
    }

    public function canDelete($member = null) 
    {
        return Permission::check('CMS_ACCESS_' . ReviewAdmin::class, 'any', $member);
    }

    public function canCreate($member = null, $context = []) 
    {
        return Permission::check('CMS_ACCESS_' . ReviewAdmin::class, 'any', $member);
    }
}