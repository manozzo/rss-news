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

$this->title = 'Listagem RSS URL';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rss-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Adicionar RSS URL', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(['id' => 'pjax-' . Yii::$app->controller->id, 'timeout' => false, 'enablePushState' => false]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            'rssUrl' => [
                'header' => 'RSS URL',
                'attribute' => 'rssUrl',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->rssUrl, 'lista-noticias?rssId='.$model->id);
                }
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
                'header' => 'Ações',
                'urlCreator' => function ($action, Rss $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


    <?php Pjax::end(); ?>

</div>