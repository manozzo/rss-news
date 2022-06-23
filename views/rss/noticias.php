<?php

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
$classificacaoArray = [
    'P+' => 'Completamente Positiva',
    'P' => 'Positiva',
    'NEU' => 'Neutra',
    'N' => 'Negativa',
    'N+' => 'Completamente Negativa',
    'NONE' => 'Sem Polaridade'
];

$dataPoints = array(
    array("y" => 2, "label" => 'Sem Polaridade'),
    array("y" => 10, "label" => 'Completamente Negativa'),
    array("y" => 5, "label" => 'Negativa'),
    array("y" => 3, "label" => 'Neutra'),
    array("y" => 2, "label" => 'Positiva'),
    array("y" => 1, "label" => 'Completamente Positiva')
);

$this->title = 'Listagem de Notícias';
$this->params['breadcrumbs'][] = $this->title;

$content = ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => 'item',
    'layout' => "{items} \n {pager}"
]);


?>
<div class="rss-noticias">

    <h1 class="my-2"><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php echo Html::tag(
        'div',
        $content,
        ['class' => 'rss-wrap']
    );
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
                includeZero: true,
            },
            data: [{
                type: "bar",
                yValueFormatString: "#,##0",
                indexLabel: "{y}",
                indexLabelPlacement: "inside",
                indexLabelFontWeight: "bolder",
                indexLabelFontColor: "white",
                dataPoints: <?= json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

    }
</script>