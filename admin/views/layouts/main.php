<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php
    $this->beginBody();

?>

<div class="wrap">
    <?php
    if(!Yii::$app->user->isGuest) {
        NavBar::begin([
            'brandLabel' => Yii::$app->user->identity->usu_nome,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]) ?>
        <?php
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => '<i class="glyphicon glyphicon-home"></i> Inicio', 'url' => ['/site/index']],
                    ['label' => '<i class="glyphicon glyphicon-list"></i> Relatórios', 'items' => [
                        ['label' => '<i class="glyphicon glyphicon-user"></i> Usuários', 'url' => ['/usuario/config']],
                        ['label' => '<i class="glyphicon glyphicon-picture"></i> Fotos', 'url' => ['/usuario/password']],
                        ['label' => '<i class="glyphicon glyphicon-eye-open"></i> Visualizações', 'url' => ['/usuario/mail']],
                        ['label' => '<i class="glyphicon glyphicon-download"></i> Downloads', 'url' => ['/usuario/mail']],
                    ]],
                    ['label' => '<i class="glyphicon glyphicon-user"></i> Perfil', 'items' => [
                        ['label' => '<i class="glyphicon glyphicon-cog"></i> Alterar Dados', 'url' => ['/usuario/config']],
                        ['label' => '<i class="glyphicon glyphicon-lock"></i> Alterar Senha', 'url' => ['/usuario/password']],
                        ['label' => '<i class="glyphicon glyphicon-envelope"></i> Alterar Email', 'url' => ['/usuario/mail']],
                        ['label' => '<i class="glyphicon glyphicon-floppy-saved"></i> Novo Admin', 'url' => ['/usuario/create']],
                    ]],
                    ' <li>'
                    . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                    . Html::submitButton(
                        '<i class="glyphicon glyphicon-log-out"></i> Sair (' . Yii::$app->user->identity->usu_login . ')',
                        ['class' => 'btn btn-link']
                    )
                    . Html::endForm()
                    . '</li>'
                ],
                'encodeLabels' => false
            ]);
        NavBar::end();
    }
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
