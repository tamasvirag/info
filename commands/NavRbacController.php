<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class NavRbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        die();

        // add "list" permission
        $listInvoice = $auth->createPermission('listInvoice');
        $listInvoice->description = 'List invoices';
        $auth->add($listInvoice);

        // add "export" permission
        $exportInvoice = $auth->createPermission('exportInvoice');
        $exportInvoice->description = 'Export invoices';
        $auth->add($exportInvoice);


        // create navInvoiceManager role
        $navInvoiceManager = $auth->createRole('navInvoiceManager');
        $auth->add($navInvoiceManager);
        $auth->addChild($navInvoiceManager, $listInvoice);
        $auth->addChild($navInvoiceManager, $exportInvoice);
    }
}
