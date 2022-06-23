<?php

use yii\helpers\Html;

?>
<div class="well clearfix shadow p-3 mb-3 bg-white rounded">
    <p class="h4">
        <a href="<?= $model['link'] ?>" target="_new"><?= $model['title']; ?></a>
    </p>
    <p class="text-muted">
        <?= Yii::$app->formatter->asDatetime($model['pubDate']); ?>
    </p>
    <p>
        <?= $model['decription']; ?>
    </p>
    <small class="d-block">Fonte: <cite title="<?= $model['rssTitle'] ?>"><?= $model['rssTitle'] ?></cite></small>
    <?= Html::tag('span', $model['classificacao'], ['class' => 'badge badge-' . $model['badgeColor']]) ?>
</div>