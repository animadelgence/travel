<?php

namespace Plugin\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class modelplugin extends routeplugin {
    public $templateTable, $imageTable, $numberTable, $trackerTable, $userTemplateTable, $smartfanpageTagTable, $seoTable,$categoryTable, $dashboardfolderTable, $subscriptionDetailsTable, $adminTable, $usertemplatebackupTable, $publisherTable, $publisherbackupTable, $orderTable, $usertrackTable, $mailformTable, $confirmMailTable, $walnutapidetailsTable, $activecampaignsTable, $dineernugratissettingsTable, $mailchimpTable, $getmailcampaignsTable, $webinarjamTable, $webinargeekTable, $matchmakerAnswerTable, $matchmakerQuestionTable, $wordpressauthtokenTable;


    public function gettemplateTable() {
        if (!$this->templateTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->templateTable = $sm->get('Front\Model\templateTable');
        }
        return $this->templateTable;
    }

    public function getimageTable() {
        if (!$this->imageTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->imageTable = $sm->get('Editor\Model\imageTable');
        }
        return $this->imageTable;
    }

    public function getnumberTable() {
        if (!$this->numberTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->numberTable = $sm->get('Editor\Model\numberTable');
        }
        return $this->numberTable;
    }

    public function gettrackerTable() {
        if (!$this->trackerTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->trackerTable = $sm->get('Editor\Model\trackerTable');
        }
        return $this->trackerTable;
    }

    public function getuserTemplateTable() {
        if (!$this->userTemplateTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->userTemplateTable = $sm->get('Editor\Model\userTemplateTable');
        }
        return $this->userTemplateTable;
    }

    public function getsmartfanpageTagTable() {
        if (!$this->smartfanpageTagTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->smartfanpageTagTable = $sm->get('Admin\Model\smartfanpageTagTable');
        }
        return $this->smartfanpageTagTable;
    }

    public function getseoTable() {
        if (!$this->seoTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->seoTable = $sm->get('Editor\Model\seoTable');
        }
        return $this->seoTable;
    }

    public function getcategoryTable() {
        if (!$this->categoryTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->categoryTable = $sm->get('Front\Model\categoryTable');
        }
        return $this->categoryTable;
    }

    public function getdashboardfolderTable() {
        if (!$this->dashboardfolderTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->dashboardfolderTable = $sm->get('Dashboard\Model\dashboardfolderTable');
        }
        return $this->dashboardfolderTable;
    }

    public function getsubscriptionDetailsTable() {
        if (!$this->subscriptionDetailsTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->subscriptionDetailsTable = $sm->get('Payment\Model\subscriptionDetailsTable');
        }
        return $this->subscriptionDetailsTable;
    }

    public function getadminTable() {
        if (!$this->adminTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->adminTable = $sm->get('Admin\Model\adminTable');
        }
        return $this->adminTable;
    }

    public function getusertemplatebackupTable() {
        if (!$this->usertemplatebackupTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->usertemplatebackupTable = $sm->get('Dashboard\Model\usertemplatebackupTable');
        }
        return $this->usertemplatebackupTable;
    }

    public function getpublisherTable() {
        if (!$this->publisherTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->publisherTable = $sm->get('User\Model\publisherTable');
        }
        return $this->publisherTable;
    }

    public function getpublisherbackupTable() {
        if (!$this->publisherbackupTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->publisherbackupTable = $sm->get('User\Model\publisherbackupTable');
        }
        return $this->publisherbackupTable;
    }

    public function getorderTable() {
        if (!$this->orderTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->orderTable = $sm->get('Payment\Model\orderTable');
        }
        return $this->orderTable;
    }

    public function getusertrackTable() {
        if (!$this->usertrackTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->usertrackTable = $sm->get('Editor\Model\usertrackTable');
        }
        return $this->usertrackTable;
    }

    public function getmailformTable() {
        if (!$this->mailformTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->mailformTable = $sm->get('Editor\Model\mailformTable');
        }
        return $this->mailformTable;
    }

    public function getconfirmMailTable() {
        if (!$this->confirmMailTable) {
            $sm = $this->getController()->getServiceLocator();
            $this->confirmMailTable = $sm->get('Document\Model\confirmMailTable');
        }
        return $this->confirmMailTable;
    }

    public function getwalnutapidetailsTable() {
        if (!$this->walnutapidetailsTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->walnutapidetailsTable = $sm->get('Integration\Model\walnutapidetailsTable');
        }
        return $this->walnutapidetailsTable;
    }

    //activecampaign integration
    public function getactivecampaignsTable() {
        if (!$this->activecampaignsTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->activecampaignsTable = $sm->get('Integration\Model\activecampaignsTable');
        }
        return $this->activecampaignsTable;
    }

    //for dineernugratis integration
    public function getdineernugratissettingsTable() {
        if (!$this->dineernugratissettingsTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->dineernugratissettingsTable = $sm->get('Integration\Model\dineernugratissettingsTable');
        }
        return $this->dineernugratissettingsTable;
    }

    // for mailchimp integration
    public function getmailchimpTable() {
        if (!$this->mailchimpTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->mailchimpTable = $sm->get('Integration\Model\mailchimpTable');
        }
        return $this->mailchimpTable;
    }

    //for mail campaign integration
    public function getmailcampaignsTable() {
        if (!$this->mailcampaignsTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->mailcampaignsTable = $sm->get('Integration\Model\mailcampaignsTable');
        }
        return $this->mailcampaignsTable;
    }

    // for webinarjam integration
    public function getwebinarjamTable() {
        if (!$this->webinarjamTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->webinarjamTable = $sm->get('Integration\Model\webinarjamTable');
        }
        return $this->webinarjamTable;
    }

    //for webinargeek integration
    public function getwebinargeekTable() {
        if (!$this->webinargeekTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->webinargeekTable = $sm->get('Integration\Model\webinargeekTable');
        }
        return $this->webinargeekTable;
    }

    public function getmatchmakerAnswerTable() {
        if (!$this->matchmakerAnswerTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->matchmakerAnswerTable = $sm->get('Integration\Model\matchmakerAnswerTable');
        }
        return $this->matchmakerAnswerTable;
    }

    public function getmatchmakerQuestionTable() {
        if (!$this->matchmakerQuestionTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->matchmakerQuestionTable = $sm->get('Integration\Model\matchmakerQuestionTable');
        }
        return $this->matchmakerQuestionTable;
    }

    public function getwordpressauthtokenTable() {

        if (!$this->wordpressauthtokenTable) {

            $sm = $this->getController()->getServiceLocator();
            $this->wordpressauthtokenTable = $sm->get('Wordpress\Model\wordpressauthtokenTable');
        }
        return $this->wordpressauthtokenTable;
    }

}

?>
