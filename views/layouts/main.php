<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <div>
            <div>
                <?php
                NavBar::begin([
                    'brandLabel' => Yii::$app->name,
                    'brandUrl' => Url::home() . 'site/index',
                    'options' => [
                        'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
                    ],
                ]); ?>
            </div>
            <div>
                <?= Nav::widget([
                    'options' => ['class' => 'navbar-nav'],
                    'items' => [
                        ['label' => 'Home', 'url' => ['/site/index']],
                        ['label' => 'RSS', 'url' => ['/rss/index'], 'visible' => !Yii::$app->user->isGuest],
                        ['label' => 'Notícias Classificadas', 'url' => ['/rss/noticias'], 'visible' => !Yii::$app->user->isGuest],
                        ['label' => 'Sobre', 'url' => ['/site/sobre']],
                        // ['label' => 'Contact', 'url' => ['/site/contact']],
                        Yii::$app->user->isGuest ? (['label' => 'Login', 'url' => ['/site/login']]
                        ) : ('<li>'
                            . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                            . Html::submitButton(
                                'Logout (' . Yii::$app->user->identity->email . ')',
                                ['class' => 'btn btn-link logout']
                            )
                            . Html::endForm()
                            . '</li>'
                        )
                    ],
                ]);
                NavBar::end();
                ?>
                <div>

                </div>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container">
            <!-- <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?> -->
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <p class="text-center">&copy; Case RSS - Sistema FIEP <?= date('Y') ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>