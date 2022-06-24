<?php

use app\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%rss}}`.
 */
class m220622_160629_create_rss_table extends Migration
{
    public $db = 'db';

    public $tableName = 'rss';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->notNull(),
            'rssUrl' => $this->string()->notNull(),
            'createdBy' => $this->integer(11)->notNull(),
        ]);
    }

    public function safeDown()
    {

        $this->dropTable($this->tableName);
    }
}
