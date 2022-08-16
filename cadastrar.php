<?php 
session_start();
ob_start();
include_once './conexao.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="index.php">Listar</a><br>
   <h1>Cadastrar</h1>
   <?php 
   $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

   if(!empty($dados['CadUsuario'])){
        $empty_input = false;

        $dados = array_map('trim', $dados);
        if(in_array("",$dados)){
            $empty_input = true;
            echo "Necessário preencher os campos";
        }elseif(!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)){
            $empty_input = true;
        }

        if(!$empty_input){
            $query_usuario = "INSERT INTO usuarios (nome, email) VALUES (:nome, :email)";
            $cad_usuario = $conn->prepare($query_usuario);
            $cad_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
            $cad_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
            $cad_usuario->execute();
            if($cad_usuario->rowCount()){
                unset($dados);
                $_SESSION['msg'] = "Usuário cadastrado com sucesso!<br>";

                header("Location: index.php");
            }else{
                echo "Erro ao cadastrar!<br>";
            }
        }
    }
   ?>
    <form name="cad-usuario" method="POST" action="processar.php">
        <label>Nome: </label>
        <input type="text" name="nome" id="nome" placeholder="Nome completo" value="<?php 
        if(isset($dados['nome'])){ 
            echo $dados['nome']; 
        } 
        ?>"><br><br> 
    
        <label>Email: </label>
        <input type="email" name="email" id="email" placeholder="Email" value="<?php 
        if(isset($dados['email'])){ 
            echo $dados['email']; 
        } 
        ?>"><br><br>
    
        <input type="submit" value="Cadastrar" name="CadUsuario">
    </form>


   <?php 
    
    ?>
</body>
</html>