<?php

namespace app\models;

use DOMDocument;
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
            [['rssUrl'], 'checkUrlVersion'],
            [['rssUrl', 'createdBy'], 'unique', 'targetAttribute' => ['rssUrl', 'createdBy']],
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
            'rssUrl' => 'RSS URL',
            'createdBy' => 'Created By',
        ];
    }

    public function isXMLFileValid($xmlFilename, $version = '1.0', $encoding = 'utf-8')
    {
        $xmlContent = file_get_contents($xmlFilename);
        return $this->isXMLContentValid($xmlContent, $version, $encoding);
    }

    /**
     * @param string $xmlContent A well-formed XML string
     * @param string $version 1.0
     * @param string $encoding utf-8
     * @return bool
     */
    public function isXMLContentValid($xmlContent, $version = '1.0', $encoding = 'utf-8')
    {
        if (trim($xmlContent) == '') {
            $this->addError($this->rssUrl, 'Digite um RSS url válido.');
            return false;
        }

        libxml_use_internal_errors(true);

        $doc = new DOMDocument($version, $encoding);
        $doc->loadXML($xmlContent);

        $errors = libxml_get_errors();
        libxml_clear_errors();

        return empty($errors);
    }

    public function checkUrlVersion($attribute, $params)
    {
        if (!filter_var($this->rssUrl, FILTER_VALIDATE_URL)) {
            $this->addError($attribute, 'Digite uma url válida.');
            return false;
        }
        $xml = simplexml_load_file($this->rssUrl);
        $xmlToJson = json_encode($xml);
        $xmlToArray = json_decode($xmlToJson, true);
        if (!isset($xmlToArray['@attributes'])) {
            $this->addError($attribute, 'Digite um RSS válido de versão 2.0');
            return false;
        }
        $rssVersion = $xmlToArray['@attributes']['version'];
        if ($rssVersion !== '2.0') {
            return false;
        }

        return true;
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

    public function getNoticias()
    {
        return $this->hasMany(Noticias::class, ['rssId' => 'id']);
    }
}
