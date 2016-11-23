<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = 'Enviar Fotos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="foto-send">


    <div class="foto-form" xmlns="http://www.w3.org/1999/html">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="form">
            <div class="panel container-fluid">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <?= $form->field($model,'foto_nome')->textInput(['autofocus'=>true]) ?>
                            </div>

                            <div class="form-group">
                                <?= $form->field($model,'foto_tag')->textInput()?>
                            </div>
                            <div class="form-group">
                                <?= $form->field($model,'foto_caminho')->fileInput() ?>
                            </div>
                            <div class="form-group">
                                <?= Html::submitButton('Enviar', ['class' => 'btn btn-info btn-block']) ?>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
