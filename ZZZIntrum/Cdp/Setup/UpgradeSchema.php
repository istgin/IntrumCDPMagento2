<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 08.12.2016
 * Time: 22:10
 */

namespace  ZZZIntrum\Cdp\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        //handle all possible upgrade versions

        if(!$context->getVersion()) {
            //no previous version found, installation, InstallSchema was just executed
            //be careful, since everything below is true for installation !
        }

        if (version_compare($context->getVersion(), '1.1.0') < 0) {
            $orderTable = 'sales_order';
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable($orderTable),
                    'intrum_status',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Intrum status'
                    ]
                );
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable($orderTable),
                    'intrum_credit_rating',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Intrum credit rating'
                    ]
                );
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable($orderTable),
                    'intrum_credit_level',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Intrum credit level'
                    ]
                );
        }

        $setup->endSetup();
    }
}