<?php

require __DIR__.'/vendor/autoload.php';

use App\Entity\Vaga;

//validação do ID
if(!isset($_GET['id']) or !is_numeric($_GET['id'])){
    header('location: index.php?status=error');
    exit;
}

//consulta a vaga
$obVaga = Vaga::getVaga($_GET['id']);

//validação da vaga
if(!$obVaga){
    header('location: index.php?status=error');
    exit;
}


//Validação do post
if(isset($_POST['excluir'])){
 
    
    $obVaga->excluir();
    header('location: index.php?status=success');
    exit;
}

include __DIR__.'/includes/header.php'; 
include __DIR__.'/includes/confirmar-exclusao.php';
include __DIR__.'/includes/footer.php';


 