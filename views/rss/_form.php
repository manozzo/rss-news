<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Rss */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rss-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rssUrl')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'createdBy')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
