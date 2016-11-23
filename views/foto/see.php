<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Visualizar Foto';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="foto-update">
    <div class="panel container-fluid">
        <div>
            <div class="form-group">
                <h2 class="text-center ">
                    <strong>
                        <?= $model->foto_nome?>
                    </strong>
                </h2>
                Tags:
                <h6>
                    <?= $model->foto_tag?>
                </h6>
            </div>

            <div class="form-group">
                <?=
                Html::a(
                    '<img class="img-responsive" src="../..'.$model->foto_caminho.'">',
                    ['foto/download', 'id' => $model->foto_id],
                    ['class' => 'text-center']
                )
                ?>
            </div>

        </div>
    </div>
</div>