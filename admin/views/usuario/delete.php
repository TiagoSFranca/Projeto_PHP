<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Deletar Conta';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-password">


    <div class="usuario-form" xmlns="http://www.w3.org/1999/html">

        <?php $form = ActiveForm::begin(); ?>
        <div class="form">
            <div class="panel container-fluid">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                                <?= $form->field($model,'senha_atual')->textInput(['type' =>'password']) ?>
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                                <?= $form->field($model,'usu_confirm_senha')->textInput(['type' =>'password']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <?= Html::submitButton('Deletar Conta', ['class' => 'btn btn-danger btn-block',
                                    'data' => [
                                        'confirm' => 'Tem Certeza que deseja excluir sua conta?',
                                    ],]) ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>