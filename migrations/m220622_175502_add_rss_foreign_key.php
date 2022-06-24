<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m220622_175502_add_rss_foreign_key
 */
class m220622_175502_add_rss_foreign_key extends Migration
{
    public $db = 'db';

    public $tableName = 'rss';

    public function safeUp()
    {
        $this->addForeignKey(
            'fk-rss-createdBy-user-id',
            $this->tableName,
            'createdBy',
            User::tableName(),
            User::primaryKey(),
            'CASCADE',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-rss-createdBy-user-id',
            $this->tableName
        );
    }
}
