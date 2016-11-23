<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = 'Editar Dados';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-config">


    <div class="usuario-form" xmlns="http://www.w3.org/1999/html">

        <?php $form = ActiveForm::begin(); ?>
        <div class="form">
            <div class="panel container-fluid">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <?= $form->field($model,'usu_nome')->textInput(['autofocus'=>true]) ?>
                            </div>

                            <div class="form-group">
                                <?= $form->field($model,'usu_login')->textInput(['readonly' => true])?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-5 col-sm-5 col-md-5">
                            <div class="form-group">
                                <?= $form->field($model,'usu_data_nascimento')->textInput(['type' =>'date']) ?>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <?=$form->field($model,'usu_sexo')->radioList(['M' => 'M','F' => 'F']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <?= Html::submitButton('Atualizar Dados', ['class' => 'btn btn-info btn-block']) ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
