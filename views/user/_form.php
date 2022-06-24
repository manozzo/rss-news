<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form w-50 mx-auto">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
        ]
    ]); ?>

    <?= $form->field($model, 'email')->textInput([
        'maxlength' => true,
        'placeholder' => 'Digite o email...',
        'type' => 'email'
    ]) ?>

    <?= $form->field($model, 'name')->textInput([
        'maxlength' => true,
        'placeholder' => 'Digite o nome...'
    ]) ?>

    <?= $form->field($model, 'password')->passwordInput([
        'maxlength' => true,
        'placeholder' => 'Digite a senha...'
    ]) ?>

    <div class="form-group float-right">
        <?= Html::a('Voltar', Url::home() . 'site/login', ['class' => 'btn btn-secondary']); ?>
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>