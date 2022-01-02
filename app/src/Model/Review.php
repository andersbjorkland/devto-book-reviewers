<?php

namespace App\Model;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TabSet;
use App\Admin\ReviewAdmin;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;

class Review extends DataObject
{
    private static $table_name = "Review";

    private static $db = [
        'Title' => 'Varchar',
        'Rating' => 'Int',
        'Review' => 'Text'
    ];

    private static $has_one = [
        'Book' => Book::class,
        'Member' => Member::class
    ];

    private static $owns = [
        'Book',
        'Member'
    ];

    private static $summary_fields = [
        'Title',
        'Book.Title'
    ];

    public function populateDefaults()
    {
        $this->Member = Security::getCurrentUser();
        parent::populateDefaults();
    }

    public function validate()
    {
        $result = parent::validate();

        if ($this->Rating < 1 || $this->Rating > 5) {
            $result->addError('Rating must be between 1 and 5');
        }


        if ($this->Member != Security::getCurrentUser()) {
            $result->addError('Only you may be the reviewer of a book that YOU review.');
        }

        return $result;
    }

    public function canView($member = null) 
    {
        return Permission::check('CMS_ACCESS_' . ReviewAdmin::class, 'any', $member);
    }

    public function canEdit($member = null) 
    {
        $reviewer = $this->Member()->ID;
        $currentUser = Security::getCurrentUser()->ID;
        if ($reviewer === $currentUser) {
            return true;
        } else {
            return false;
        }
    }

    public function canDelete($member = null) 
    {
        $reviewer = $this->Member()->ID;
        $currentUser = Security::getCurrentUser()->ID;
        if ($reviewer === $currentUser) {
            return true;
        } else {
            return false;
        }
    }

    public function canCreate($member = null, $context = []) 
    {
        return Permission::check('CMS_ACCESS_' . ReviewAdmin::class, 'any', $member);
    }

    public function getCMSActions()
    {
        $actions = parent::getCMSActions();
        $buttons = $actions->fieldByName('MajorActions');
        $buttons->push($newFields = FormAction::create('searchGoogleBooks', 'Search'));
 
        $newFields ->addExtraClass('btn-outline-primary font-icon-tick')
            ->setUseButtonTag(true);
        return $actions;
    }

}