<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Portal News Feelings';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h2>Bem vindo <?= !Yii::$app->user->isGuest ? Yii::$app->user->identity->name : '' ?></h2>
        <h3 class="lead">Case RSS para Analista Técnico PL – RP7056</h3>
        <p>
            Um portal de análise de notícias RSS. Adicione um link para começar.
        </p>
        <p>
            <?= Html::a('RSS', Url::home() . 'rss/index', ['class' => 'btn btn-primary']) ?>
        </p>

    </div>
</div>