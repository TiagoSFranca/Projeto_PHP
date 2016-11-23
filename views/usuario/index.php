<?php
use \yii\bootstrap\Html;
/* @var $this yii\web\View */

$this->title = 'Perfil';
$script = <<< CSS
.caption div {
    box-shadow: 0 0 5px #C8C8C8;
    transition: all 0.3s ease 0s;
}
.img-circle {
    border-radius: 50%;
}
.img-circle {
    border-radius: 0;
}

.ratio {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 0;
    padding-bottom: 100%;
    position: relative;
    width: 100%;
}
.img-circle {
    border-radius: 50%;
}
.img-responsive {
    display: block;
    height: auto;
    max-width: 100%;
}
.thumbnail {
    position:relative;
    overflow:hidden;
}
 
.caption {
    position:absolute;
    top:0;
    right:0;
    background:rgba(211, 211, 211, 0.75);
    width:100%;
    height:100%;
    padding:2%;
    display: none;
    text-align: left;
    color:#fff !important;
    z-index:2;
}
.caption-center{
    display:flex;
    justify-content:center;
    align-items:center;
}

.marginalizado{
    margin-left: 1em;
}
.gi-2x{font-size: 1em;}
.gi-3x{font-size: 3em;}
.gi-4x{font-size: 4em;}
.gi-5x{font-size: 5em;}
CSS;

$js = "$(document).ready(function() {
    $(\"[rel='tooltip']\").tooltip();    
 
    $('.thumbnail').hover(
        function(){
            $(this).find('.caption').slideDown(250); 
        },
        function(){
            $(this).find('.caption').slideUp(250);
        }
    ); 
 });";


$this->registerCss($script);
$this->registerJs($js);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-index">
    <p class="alert-success">
        <?=
            Yii::$app->session->getFlash('sucess');
        ?>
    </p>
    <div class="container">
        <div class="row">
            <?php
            if (sizeof($modelFoto) > 0) {
                foreach ($modelFoto as $foto) {
                    ?>
                    <div class="col-sm-2">
                        <div class="thumbnail">
                            <div class="caption">
                                <h5><?= $foto->foto_nome ?></h5>
                                <p class="caption-center">
                                    <?=
                                    Html::a(
                                        '<i class="glyphicon glyphicon-trash gi-2x"></i>',
                                        ['foto/delete', 'id' => $foto->foto_id],
                                        ['class' => 'btn btn-danger marginalizado',
                                            'data' => [
                                                'confirm' => 'Deseja excluir esta foto?',
                                                'method' => 'post',
                                            ],
                                        ]
                                    )
                                    ?>

                                    <?=
                                    Html::a(
                                        '<i class="glyphicon glyphicon-pencil gi-2x"></i>',
                                        ['foto/update', 'id' => $foto->foto_id],
                                        ['class' => 'btn btn-primary  marginalizado']
                                    )
                                    ?>

                                </p>
                                <p class="caption-center">
                                    <i class="glyphicon glyphicon-download gi-2x btn  btn-primary"
                                       title="Downloads"> <?= $foto->foto_downloads ?></i>
                                </p>
                            </div>
                            <a class="ratio img-responsive img-circle"
                               style="background-image: url(<?= "../.." . $foto->foto_caminho ?>);"></a>
                        </div>
                    </div>
                    <?php
                }
            }else{

                ?><i class="glyphicon glyphicon-info-sign gi-4x text-danger"></i>
                <h3 class="error text-danger">Não há fotos para exibir
                    <?php
                    if($teste != null)
                        echo "com o Parâmetro de Pesquisa: $teste"
                    ?>.</h3>
                <?php
            }
            ?>
        </div>
    </div>
</div>
