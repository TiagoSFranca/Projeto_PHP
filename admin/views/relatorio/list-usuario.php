<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = 'Relatório de Usuários';
$this->registerCss("
.gi{font-size: 2em;}");
$opcao = 0;
if($users!= null)
    $opcao = $op;
?>
<div class="usuario-config">
    <div class="usuario-form" xmlns="http://www.w3.org/1999/html">
        <?php $form = ActiveForm::begin(); ?>
        <div class="form">
            <div class="panel container-fluid">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                                <div class="selectContainer">
                                    <select class="form-control" name="data">
                                        <option value="1" <?=$opcao==1?'select':'';?>>
                                            Data de Nascimento
                                        </option>
                                        <option value="2" <?=$opcao==2?'select':'';?>>
                                            Data de Cadastro
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                                <?= $form->field($model,'usu_data_inicial')->textInput(['type' =>'date'])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                                <?= $form->field($model,'usu_data_final')->textInput(['type' =>'date'])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-info btn-block']) ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php ActiveForm::end();
            if($users != null){
        ?>
        <div class="fixed-table-container">
            <?=
            Html::a(
                '<i class="glyphicon glyphicon-print gi"></i>',
                ['foto/list'],
                ['class' => 'btn-lg pull-right',
                    'data' => [
                        'method' => 'post',
                        'params' => ['id' => 3]
                    ],
                ]
            );
            ?>

            <div class="fixed-table-body">
                <table id="grid" class="table table-striped table-hover" data-toolbar="#toolbargrid"
                       data-pagination="true" data-search="true">
                    <thead>
                    <tr>
                        <th class="text-left" style="">
                            <div class="th-inner sortable">#</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th class="text-left" style="">
                            <div class="th-inner sortable">Id</div>
                            <div class="fht-cell"></div>
                        <th class="text-left" style="">
                            <div class="th-inner sortable">Nome</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th class="text-left" style="">
                            <div class="th-inner sortable">Login</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th class="text-left" style="">
                            <div class="th-inner sortable">Email</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th class="text-left" style="">
                            <div class="th-inner sortable">Data de Nascimento</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th class="text-left" style="">
                            <div class="th-inner sortable">Sexo</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th class="text-left" style="">
                            <div class="th-inner sortable">Data de Cadastro</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th class="text-left" style="">
                            <div class="th-inner sortable">Fotos</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th class="text-left" style="">
                            <div class="th-inner sortable">Download</div>
                            <div class="fht-cell"></div>
                        </th>
                    </tr>
                    </thead>
                    <?php
                    foreach ($users as $key => $user) {
                        ?>
                        <tbody>
                        <tr data-index="0">
                            <td class="text-left" style=""><?= $key + 1 ?></td>
                            <td class="text-left" style=""><?= $user->usu_id ?></td>
                            <td class="text-left" style=""><?= $user->usu_nome ?></td>
                            <td class="text-left" style=""><?= $user->usu_login ?></td>
                            <td class="text-left" style=""><?= $user->usu_email ?></td>
                            <td class="text-left" style=""><?= $user->usu_data_nascimento ?></td>
                            <td class="text-left" style=""><?= $user->usu_sexo ?></td>
                            <td class="text-left" style=""><?= $user->usu_data_cadastro ?></td>
                            <td class="text-left" style=""><?= $user->fotos ?></td>
                            <td class="text-left" style=""><?= $user->downloads ?></td>
                        </tr>
                        </tbody>
                        <?php
                    }
                    ?>
                    <tfoot></tfoot>
                </table>
            </div>
            <?php
            }
            ?>
    </div>
