<?php

use app\models\Rss;
use yii\db\Migration;

/**
 * Handles the creation of table `noticias`.
 */
class m220623_215135_create_noticias_table extends Migration
{
    public $db = 'db';

    public $tableName = 'noticias';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->notNull(),
            'rssId' => $this->integer(11)->notNull(),
            'title' => $this->string()->notNull(),
            'link' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'classificacao' => $this->string()->notNull(),
        ], 'ENGINE=InnoDB');

    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
