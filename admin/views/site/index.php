<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Início';
$script = <<<CSS
.well {
    min-height: 20px;
    padding: 0px;
    margin-bottom: 20px;
    background-color: #D9D9D9;
    border: 1px solid #D9D9D9;
    border-radius: 0px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    padding-left: 15px;
    border:0px;
}
.thumbnail .caption {
    padding: 9px;
    color: #333;
    padding-left: 0px;
    padding-right: 0px;
}
.icon-style{
    margin-right:15px;
    font-size:18px;
    margin-top:20px;
}
p{
    margin:3px;
}
.well-add-card{
    margin-bottom:10px;
}
.btn-add-card{
    margin-top:20px;
}
.thumbnail {
    display: block;
    padding: 1px;
    margin-bottom: 20px;
    line-height: 1.42857143;
    background-color: #fff;
    border: 3px solid #D9D9D9;
    border-radius: 15px;
    -webkit-transition: border .2s ease-in-out;
    -o-transition: border .2s ease-in-out;
    transition: border .2s ease-in-out;
    padding-left: 0px;
    padding-right: 0px;
}
.btn
{
    border-radius:0px;
}
.btn-update
{
    margin-left:15px;
}
.gi{
    font-size: 1.1em;
}
.gi-4x{font-size: 4em;}
CSS;

$this->registerCss($script);

?>
<div class="site-index">
    <div class="container" id="tourpackages-carousel">
            <?php
            $form = ActiveForm::begin([
                'action' => ['site/index'],
                'method' => 'get',
            ]);
            ?>
        <div class="col-sm-5 col-md-5 col-lg-12">
            <form class="navbar-form" role="search">
                <div class="input-group col-sm-5 col-md-5 pull-right">
                    <input type="text" class="form-control" placeholder="Pesquisar" name="q" >
                    <div class="input-group-btn">
                        <?= Html::submitButton('<i class="glyphicon glyphicon-search gi"></i>', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </form>
        </div>

            <?php ActiveForm::end(); ?>

        <div class="row">
            <div class="col-lg-12">
                <h4>
                    <strong>
                        Usuários
                    </strong>
                </h4>
                <p class="<?= $users==null?'alert-danger':'alert-success'?>" style="display: flex">
                    <?php
                    if($param!= null){
                        echo 'Parâmetro de pesquisa: '.$param;
                    }
                    ?>
                </p>
            </div>
            <?php
            if(sizeof($users)>0) {
                foreach ($users as $user) {
                    ?>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <div class='col-lg-12 text-primary'>
                                    <i class="glyphicon glyphicon-picture"> <?= $user->fotos ?></i>
                                    <i class="glyphicon glyphicon-download pull-right"> <?= $user->downloads ?></i>
                                </div>
                                <div class='col-lg-12 well well-add-card'>
                                    <h4>
                                        <strong>
                                            <?= $user->usu_nome ?>
                                        </strong>
                                    </h4>
                                </div>
                                <div class='col-lg-12'>
                                    <p>
                                        <strong>
                                            Email:
                                        </strong>
                                        <?= $user->usu_email ?>
                                    </p>
                                    <p>
                                        <strong>
                                            Login:
                                        </strong>
                                        <?= $user->usu_login ?>
                                    </p>
                                    <p>
                                        <strong>
                                            Nascimento:
                                        </strong>
                                        <?= $user->usu_data_nascimento ?>
                                    </p>
                                </div>

                                <?php
                                $user->fotos > 0 ? $class = 'btn btn-primary' : $class = 'hidden';

                                echo Html::a(
                                    'Ver Fotos',
                                    ['foto/list'],
                                    ['class' => $class . ' btn-xs btn-update btn-add-card',
                                        'data' => [
                                            'method' => 'post',
                                            'params' => ['id' => $user->usu_id]
                                        ],
                                    ]
                                );

                                echo Html::a(
                                    'Excluir Usuário',
                                    ['usuario/delete', 'id' => $user->usu_id],
                                    ['class' => 'btn btn-danger btn-xs btn-update btn-add-card',
                                        'data' => [
                                            'confirm' => 'Deseja excluir esta foto?',
                                            'method' => 'post',
                                        ],
                                    ]
                                );
                                echo Html::a(
                                    '<i class="glyphicon glyphicon-list"></i> Relatório',
                                    ['relatorio/usuario'],
                                    ['class' => $class . ' btn-xs btn-update btn-add-card',
                                        'data' => [
                                            'params' => [
                                                'id' => $user->usu_id,
                                                'nome' => $user->usu_nome,
                                                'login' => $user->usu_login,
                                            ],
                                            'method' => 'post',
                                        ],
                                    ]
                                )
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }else{

                ?>
                <i class="glyphicon glyphicon-info-sign gi-4x text-danger"></i>
                <h3 class="error text-danger">
                    Não há fotos para exibir.
                </h3>
                <?php
            }
            ?>
        </div><!-- End row -->
    </div><!-- End container -->

</div>
