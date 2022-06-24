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
            'userId' => $this->integer(11)->notNull(),
            'title' => $this->text()->notNull(),
            'link' => $this->text()->notNull(),
            'description' => $this->text()->notNull(),
            'pubDate' => $this->date()->notNull(),
            'classificacao' => $this->string()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
