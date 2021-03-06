<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;

if(!Yii::$app->user->isGuest && Yii::$app->user->identity->ace_id != 2)
    Yii::$app->user->logout();

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
    NavBar::begin([
        'brandLabel' => 'Cimatec Photo',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ])?>
    <?php
    if(Yii::$app->user->isGuest){
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => '<i class="glyphicon glyphicon-home"></i> Inicio', 'url' => ['/site/index'],'img'],
                ['label' => '<i class="glyphicon glyphicon-floppy-saved"></i> Cadastrar', 'url' => ['/usuario/create']],
                ['label' => '<i class="glyphicon glyphicon-log-in"></i> Login', 'url' => ['/usuario/login']]

            ],
            'encodeLabels' => false
        ]);
    }else{
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => '<i class="glyphicon glyphicon-home"></i> Inicio', 'url' => ['/site/index']],
                ['label' => '<i class="glyphicon glyphicon-user"></i> Perfil', 'url' => ['/usuario/index']],
                ['label' => '<i class="glyphicon glyphicon-upload"></i> Enviar Fotos', 'url' => ['/foto/send']],
                ['label' => '<i class="glyphicon glyphicon-wrench"></i> Editar', 'icon' => 'user', 'items' => [
                    ['label' => '<i class="glyphicon glyphicon-cog"></i> Alterar Dados', 'url' => ['/usuario/config'],'class'=>'modPass'],
                    ['label' => '<i class="glyphicon glyphicon-lock"></i> Alterar Senha', 'url' => ['/usuario/password']],
                    ['label' => '<i class="glyphicon glyphicon-envelope"></i> Alterar Email', 'url' => ['/usuario/mail']],
                    ['label' => '',
                        'options'=> ['class'=>'divider']],
                    ['label' => '<i class="glyphicon glyphicon-trash"></i> Excluir Conta', 'url' => ['/usuario/delete']],
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

    }
    NavBar::end();
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
        <p class="pull-left">&copy; Cimatec Photo <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
