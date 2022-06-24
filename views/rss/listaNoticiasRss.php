<?php

use yii\helpers\Html;
use yii\helpers\Url;
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
            [
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model) use ($noticiasSelected) {
                    $existeNoticia = in_array($model['title'], $noticiasSelected);
                    return ['checked' => $existeNoticia];
                }
            ],
            'title' => [
                'header' => 'Notícias',
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model['title'], $model['link'], ['target' => '_blank']);
                }
            ],
        ],
    ]); ?>

    <?= Html::hiddenInput('rssId', Yii::$app->controller->actionParams['rssId']); ?>
    <div class="float-right">
        <?= Html::a('Voltar', Url::home() . 'rss/index', ['class' => 'btn btn-secondary']); ?>

        <?= Html::submitButton('Classificar Notícias', ['class' => 'btn btn-success']); ?>
    </div>


    <?= Html::endForm(); ?>


    <?php Pjax::end(); ?>

</div>