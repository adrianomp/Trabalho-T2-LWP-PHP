<?php
//Conexão com o banco
include_once('conectar.php');


// Recebe os dados do post, recebe a senha com hash, cria um user id randomico(Token para validar o email),gera a data atual para fazer com que o token expire e define a conta como inativa
if (isset($_POST['enviar'])) {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $senha = md5($_POST['senha']);
    $uid = uniqid(rand(), true);
    date_default_timezone_set('America/Sao_Paulo');
    $data_ts = date('Y-m-d H:i');
    $ativo = 0;


    $sql = "insert into cadastro ( id_cadastro, nome, email, login, senha,
data_ts, uid,
ativo ) ";
    $sql .= "values
('', '$nome','$email','$login','$senha','$data_ts','$uid','$ativo')";


    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $id = $pdo->lastInsertId();


// Criar as variaveis para validar o email
    $url = sprintf('id=%s&email=%s&uid=%s&key=%s', $id, md5($email), md5($uid), md5($data_ts));

    $mensagem = 'Para confirmar seu cadastro acesse o link:' . "\n";
    $mensagem .= sprintf('http://pswfaeterj2015.16mb.com/T2/ativar.php?%s', $url);


// O remetente deve ser um e-mail do seu domínio conforme determina a RFC 822.
// O return-path deve ser ser o mesmo e-mail do remetente.
    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
    $headers .= "From: cadastro@pswfaeterj2015.16mb.com\r\n"; // remetente
    $headers .= "Return-Path: cadastro@pswfaeterj2015.16mb.com"; // return-path
    $envio = mail($email, "Confirmacao de cadastro", $mensagem, $headers);

    if ($envio)
        echo "Mensagem enviada com sucesso";
    else
        echo "A mensagem não pode ser enviada";
}
?>

