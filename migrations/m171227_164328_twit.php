<?php

use yii\db\Migration;
use yii\db\Schema;


/**
 * Class m171227_164328_twit
 */
class m171227_164328_twit extends Migration
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
        echo "m171227_164328_twit cannot be reverted.\n";

        return false;
    }

    public function up()
    {

        $this->execute('ALTER TABLE user ADD password_reset_token VARCHAR(255) NOT NULL;');
        $this->execute('ALTER TABLE user ADD status INT(10) NOT NULL;');


        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('fk-auth-user_id-user-id', 'auth', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->execute("ALTER TABLE auth DROP FOREIGN KEY `fk-auth-user_id-USER-id`");
        $this->dropTable('auth');
        $this->execute('ALTER TABLE user DROP password_reset_token');
        $this->execute('ALTER TABLE user DROP status');
    }
}
