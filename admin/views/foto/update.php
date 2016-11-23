<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Editar Foto';
$this->params['breadcrumbs'][] = ['label' => 'Perfil', 'url' => ['usuario/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="foto-update">


    <div class="foto-form" xmlns="http://www.w3.org/1999/html">

        <?php $form = ActiveForm::begin(); ?>
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1 class="text-center ">
                        <strong>
                            <?=$this->title?>
                        </strong>
                    </h1>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <?= $form->field($model,'foto_nome')->textInput(['autofocus'=>true]) ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model,'foto_tag')->textInput()?>
                    </div>

                    <div class="form-group">
                        <img class="img-responsive" src="../..<?=$model->foto_caminho?>">
                    </div>

                    <?= Html::submitButton('Atualizar Foto', ['class' => 'btn btn-info btn-block']) ?>

                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>