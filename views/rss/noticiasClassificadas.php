<?php

use app\models\Noticias;
use app\models\Rss;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RssSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$checkNoticiasData = Noticias::find()->where(['userId' => Yii::$app->user->id])->count();
$this::registerJsVar('noData', !empty($checkNoticiasData));

function countItemsClassificados($classificacao)
{
    return Noticias::find()->where(['classificacao' => $classificacao, 'userId' => Yii::$app->user->id])->count();
}
$dataPoints = [
    ["y" => countItemsClassificados('Sem Polaridade'), "label" => 'Sem Polaridade'],
    ["y" => countItemsClassificados('Completamente Negativa'), "label" => 'Completamente Negativa'],
    ["y" => countItemsClassificados('Negativa'), "label" => 'Negativa'],
    ["y" => countItemsClassificados('Neutra'), "label" => 'Neutra'],
    ["y" => countItemsClassificados('Positiva'), "label" => 'Positiva'],
    ["y" => countItemsClassificados('Completamente Positiva'), "label" => 'Completamente Positiva']
];

$this->title = 'Listagem de Notícias';
$this->params['breadcrumbs'][] = $this->title;

$content = ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => 'item',
    'layout' => "{items} \n {pager}"
]);


?>
<div class="rss-noticias-classificadas">

    <h1 class="my-2"><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['timeout' => false, 'enablePushState' => false]); ?>


    <?php echo Html::tag(
        'div',
        $content,
        ['class' => 'rss-wrap']
    );
    ?>

    <?php
    if (empty($checkNoticiasData)) {
        echo Html::tag('p', 'Cadastre um RSS para exibir os dados. ' . Html::a('Adicionar RSS URL', ['create'], ['class' => 'btn btn-success']));
    }
    ?>

    <?php Pjax::end(); ?>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</div>

<script>
    window.onload = function() {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "Classificação das Notícias"
            },
            axisY: {
                title: "Número de Notícias",
                interval: 1,
            },
            data: [{
                type: "bar",
                yValueFormatString: "0",
                indexLabel: "{y}",
                indexLabelPlacement: "inside",
                indexLabelFontWeight: "bolder",
                indexLabelFontColor: "white",
                dataPoints: <?= json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        if (noData) {
            chart.render();
        }

    }
</script>