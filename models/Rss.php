<?php

namespace app\models;

use SimpleXMLElement;
use Yii;

/**
 * This is the model class for table "rss".
 *
 * @property int $id
 * @property string $rssUrl
 * @property int $createdBy
 *
 * @property User $createdBy0
 */
class Rss extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rss';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rssUrl', 'createdBy'], 'required'],
            [['createdBy'], 'integer'],
            [['rssUrl'], 'string', 'max' => 255],
            [['createdBy'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['createdBy' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rssUrl' => 'Rss Url',
            'createdBy' => 'Created By',
        ];
    }

    /**
     * Gets query for [[CreatedBy0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'createdBy']);
    }
}
