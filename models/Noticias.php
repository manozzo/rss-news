<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "noticias".
 *
 * @property int $id
 * @property int $rssId
 * @property string $title
 * @property string $link
 * @property string $description
 * @property string $classificacao
 *
 * @property Rss $rss
 */
class Noticias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'noticias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rssId', 'title', 'link', 'description', 'classificacao'], 'required'],
            [['rssId'], 'integer'],
            [['title', 'link', 'description', 'classificacao'], 'string', 'max' => 255],
            [['rssId'], 'exist', 'skipOnError' => true, 'targetClass' => Rss::class, 'targetAttribute' => ['rssId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rssId' => 'Rss ID',
            'title' => 'Title',
            'link' => 'Link',
            'description' => 'Description',
            'classificacao' => 'Classificacao',
        ];
    }

    /**
     * Gets query for [[Rss]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRss()
    {
        return $this->hasOne(Rss::class, ['id' => 'rssId']);
    }
}
