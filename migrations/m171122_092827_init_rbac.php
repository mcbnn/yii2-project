<?php

use yii\db\Migration;

/**
 * Class m171122_092827_init_rbac
 */
class m171122_092827_init_rbac extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171122_092827_init_rbac cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $auth = Yii::$app->authManager;

        $special = $auth->createRole('special');
        $auth->add($special);

        $client = $auth->createRole('client');
        $auth->add($client);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $special);
        $auth->addChild($admin, $client);

        $auth->assign($client, 3);
        $auth->assign($special, 2);
        $auth->assign($admin, 1);
    }

    public function down()
    {
        $this->execute('TRUNCATE auth_item_child;');
        $this->execute('TRUNCATE auth_assignment;');
        $this->execute('TRUNCATE auth_item;');
        $this->execute('TRUQNCATE auth_rule;');
    }

}
