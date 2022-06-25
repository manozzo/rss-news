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

$checkNoticiasData = Noticias::find()->where(['userId' => Yii::$app->user->id]);
$this::registerJsVar('noData', !empty($checkNoticiasData->count()));

function countItemsClassificados($classificacao)
{
    return Noticias::find()->where(['classificacao' => $classificacao, 'userId' => Yii::$app->user->id])->count();
}
$noticiasSelectDate = $checkNoticiasData->select(['pubDate', 'classificacao'])->asArray()->all();

$arrayGraficoByDatas = [];
foreach ($noticiasSelectDate as $key => $item) {
    $arrayGraficoByDatas[$item['pubDate']][$key] = $item['classificacao'];
}
$arrayClassificacaoContada = [];
foreach($arrayGraficoByDatas as $key => $itemByData){
    $arrayClassificacaoContada[$key] = array_count_values($itemByData);
}
$arrayFinal = [];
foreach($arrayClassificacaoContada as $key => $dataPoint){
    $montaArray = [];
    array_walk($dataPoint, function(&$item, $key2){
        [
            "label" => $key2,
            "y" => $item
        ];
    });
    ddd($dataPoint);
}
ddd($arrayFinal);
die;

// '2022-06-24' = [
//     ["label" => "Negativa", "y" => 2],
//     ["label" => "Positiva", "y" => 1],
//     ["label" => "Neutra", "y" => 2],
// ];

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
            title: {
                text: "Spending of Money Based on Household Composition"
            },
            theme: "light2",
            animationEnabled: true,
            toolTip: {
                shared: true,
                reversed: true
            },
            data: [{
                type: "stackedColumn100",
                name: 'Sem Polaridade',
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoints0, JSON_NUMERIC_CHECK); ?>
            }, {
                type: "stackedColumn100",
                name: 'Completamente Negativa',
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
            }, {
                type: "stackedColumn100",
                name: 'Negativa',
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
            }, {
                type: "stackedColumn100",
                name: 'Neutra',
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
            }, {
                type: "stackedColumn100",
                name: 'Positiva',
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoints4, JSON_NUMERIC_CHECK); ?>
            }, {
                type: "stackedColumn100",
                name: 'Completamente Positiva',
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoints5, JSON_NUMERIC_CHECK); ?>
            }]
        });

        chart.render();

    }
</script>