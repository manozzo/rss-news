<?php

use app\models\Rss;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RssSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listagem Notícias RSS';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="lista-noticias-index">

    <?= Html::beginForm([Yii::$app->controller->id . '/classifica-noticias'], 'post'); ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['id' => 'pjax-lista-noticias', 'timeout' => false, 'enablePushState' => false]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            'title' => [
                'header' => 'Notícias',
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model['title'], $model['link'], ['target' => '_blank']);
                }
            ],
            [
                'class' => 'yii\grid\CheckboxColumn'
            ],
        ],
    ]); ?>

    <?= Html::hiddenInput('rssId', Yii::$app->controller->actionParams['rssId']); ?>

    <?= Html::submitButton('Classificar Notícias', ['class' => 'btn btn-success float-right']); ?>


    <?= Html::endForm(); ?>


    <?php Pjax::end(); ?>

</div>