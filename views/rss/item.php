<?php

use yii\helpers\Html;

$badgeColorArray = [
    'Completamente Positiva' => 'success',
    'Positiva' => 'primary',
    'Neutra' => 'secondary',
    'Negativa' => 'warning',
    'Completamente Negativa' => 'danger',
    'Sem Polaridade' => 'info'
];

?>
<div class="well clearfix shadow p-3 mb-3 bg-white rounded">
    <p class="h4">
        <a href="<?= $model['link'] ?>" target="_new"><?= $model['title']; ?></a>
    </p>
    <p>
        <?= $model['description']; ?>
    </p>
    <?= Html::tag('span', $model['classificacao'], ['class' => 'badge badge-' . $badgeColorArray[$model['classificacao']]]) ?>
</div>