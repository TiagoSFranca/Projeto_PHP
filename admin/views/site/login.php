<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;

$css = <<<CSS
  body
{
    background-color: #1b1b1b;
    padding-top: 40px;
}


.input-group-addon
{
    background-color: rgb(50, 118, 177);
    border-color: rgb(40, 94, 142);
    color: rgb(255, 255, 255);
}
.form-control:focus
{
    background-color: rgb(50, 118, 177);
    border-color: rgb(40, 94, 142);
    color: rgb(255, 255, 255);
}
.form-signup input[type="text"],.form-signup input[type="password"] { border: 1px solid rgb(50, 118, 177); }
CSS;


$this->registerCss($css);

AppAsset::register($this);
$this->title = 'Login Admin';
?>
<div class="usuario-mail">


    <div class="usuario-form" xmlns="http://www.w3.org/1999/html">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-default">
                        <p class="alert-success" style="display: flex">
                            <?=
                            Yii::$app->session->getFlash('sucess');
                            ?>
                        </p>
                        <div class="panel-body">
                            <h3 class="text-center">
                                ADMIN</h3>
                            <h4 class="text-center">
                                Login</h4>

                            <?php $form = ActiveForm::begin(); ?>
                            <form class="form form-signup" role="form">
                                <div class="form-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                        <?= $form->field($model,'username', [
                                            'inputOptions'=>[
                                                'class'=>'form-control',
                                                'placeholder'=>'Usuário'
                                            ]
                                        ])->label(false)?>
                                    </div>
                                <div class="form-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                        <?= $form->field($model,'password', [
                                            'inputOptions'=>[
                                                'class'=>'form-control',
                                                'placeholder'=>'Senha'
                                            ]
                                        ])->passwordInput()->label(false)?>
                                </div>
                        </div>
                            <?= Html::submitButton('Entrar', ['class' => 'btn btn-lg btn-primary btn-block h2', 'name' => 'login-button']) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>