<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Sobre';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-sobre">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        This is the About page. You may modify the following file to customize its content:
    </p>

    <code><?= __FILE__ ?></code>
</div>
