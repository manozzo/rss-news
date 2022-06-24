<?php

use app\models\Rss;
use app\models\User;
use yii\db\Migration;

/**
 * Class m220623_215354_add_noticias_rss_foreign_key
 */
class m220623_215354_add_noticias_rss_foreign_key extends Migration
{
    public $db = 'db';

    public $tableName = 'noticias';

    public function safeUp()
    {
        $this->addForeignKey(
            'fk-noticias-rssId-rss-id',
            $this->tableName,
            'rssId',
            Rss::tableName(),
            Rss::primaryKey(),
            'CASCADE',
        );

        $this->addForeignKey(
            'fk-noticias-userId-user-id',
            $this->tableName,
            'userId',
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
            'fk-noticias-rssId-rss-id',
            $this->tableName
        );

        $this->dropForeignKey(
            'fk-noticias-userId-user-id',
            $this->tableName
        );
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220623_215354_add_noticias_rss_foreign_key cannot be reverted.\n";

        return false;
    }
    */
}
