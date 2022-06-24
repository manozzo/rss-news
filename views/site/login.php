<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="text-center">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>Por favor entre com seus dados para fazer o login, ou cadastre-se na plataforma.</p>
    </div>
    <div class="login-form w-50 mx-auto">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
            ],
        ]); ?>

        <?= $form->field($model, 'email')->textInput([
            'autofocus' => true,
            'type' => 'email',
            'placeholder' => Yii::t('app', 'Digite seu e-mail...')
        ]) ?>

        <?= $form->field($model, 'password')->passwordInput([
            'placeholder' => Yii::t('app', 'Digite a sua senha...')
        ]) ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"offset-lg-1 custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>

        <div class="form-group float-right">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

            <?= Html::a(Html::button('Cadastro', ['class' => 'btn btn-success']), '../user/create') ?>

        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>