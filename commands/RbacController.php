<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        
        die();
        
        // add "news" permission
        $manageNews = $auth->createPermission('manageNews');
        $manageNews->description = 'manage News';
        $auth->add($manageNews);
        
        // add "news" permission
        $manageClient = $auth->createPermission('manageClient');
        $manageClient->description = 'manage Client';
        $auth->add($manageClient);
        
        // add "district" permission
        $manageDistrict = $auth->createPermission('manageDistrict');
        $manageDistrict->description = 'manage District';
        $auth->add($manageDistrict);
        
        // add "dealer" permission
        $manageDealer = $auth->createPermission('manageDealer');
        $manageDealer->description = 'manage Dealer';
        $auth->add($manageDealer);
        
        // add "dealerControl" permission
        $manageDealer = $auth->createPermission('manageDealerControl');
        $manageDealer->description = 'manage Dealer Control';
        $auth->add($manageDealer);
        
        // add "user" permission
        $manageUser = $auth->createPermission('manageUser');
        $manageUser->description = 'manage User';
        $auth->add($manageUser);
        
        // add "invoice" permission
        $manageInvoice = $auth->createPermission('manageInvoice');
        $manageInvoice->description = 'manage Invoice';
        $auth->add($manageInvoice);
        


        // roles
        $newsManager = $auth->createRole('newsManager');
        $auth->add($newsManager);
        $auth->addChild($newsManager, $manageNews);
        
        $clientManager = $auth->createRole('clientManager');
        $auth->add($clientManager);
        $auth->addChild($clientManager, $manageClient);
        
        $districtManager = $auth->createRole('districtManager');
        $auth->add($districtManager);
        $auth->addChild($districtManager, $manageDistrict);
        
        $dealerManager = $auth->createRole('dealerManager');
        $auth->add($dealerManager);
        $auth->addChild($dealerManager, $manageDealer);
        
        $dealerControlManager = $auth->createRole('dealerControlManager');
        $auth->add($dealerControlManager);
        $auth->addChild($dealerControlManager, $manageDealerControl);
        
        $userManager = $auth->createRole('userManager');
        $auth->add($userManager);
        $auth->addChild($userManager, $manageUser);
        
        $invoiceManager = $auth->createRole('invoiceManager');
        $auth->add($invoiceManager);
        $auth->addChild($invoiceManager, $manageInvoice);
        
        
        
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $newsManager);
        $auth->addChild($admin, $clientManager);
        $auth->addChild($admin, $districtManager);
        $auth->addChild($admin, $dealerManager);
        $auth->addChild($admin, $dealerControlManager);
        $auth->addChild($admin, $userManager);
        $auth->addChild($admin, $invoiceManager);
        

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($admin, 1);
    }
}