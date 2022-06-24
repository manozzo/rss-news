<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Rss */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rss-form">

    <?php $form = ActiveForm::begin([
        'options' => ['id' => Yii::$app->controller->id . '-form'],
        'enableClientValidation' => true,
        'enableAjaxValidation' => true
    ]); ?>
    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'rssUrl')->textInput(['maxlength' => true, 'placeholder' => 'Insira a URL do RSS...',]) ?>

    <!-- <?= $form->field($model, 'createdBy')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>