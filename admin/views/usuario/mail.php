<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Alterar Email';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-mail">


    <div class="usuario-form" xmlns="http://www.w3.org/1999/html">

        <?php $form = ActiveForm::begin(); ?>
        <div class="form">
            <div class="panel ontainer-fluid">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <?= $form->field($model,'usu_email_atual')->input('email') ?>
                            </div>
                            <div class="form-group">
                                <?= $form->field($model,'usu_email')->input('email')?>
                            </div>
                            <div class="form-group">
                                <?= Html::submitButton('Atualizar Email', ['class' => 'btn btn-info btn-block']) ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>