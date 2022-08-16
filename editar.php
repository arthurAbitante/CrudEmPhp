<?php
session_start();
ob_start();
include_once './conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if(empty($id)){
    $_SESSION['msg'] = "Erro, usuário não encontrado";
    header("Location: index.php");
    exit();
}

$query_usuario = "SELECT id, nome, email FROM usuarios WHERE id = $id LIMIT 1";
$result_usuario = $conn->prepare($query_usuario);
$result_usuario->execute();

if(($result_usuario) AND ($result_usuario->rowCount() != 0)){
    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
}else{
    $_SESSION['msg'] = "Usuário não encontrado";
    header("Location: index.php");
    exit();
}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar</title>
    </head>
    <body>
        <a href="index.php">Voltar</a><br>

        <h1>Editar</h1>

        <?php 
        
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(!empty($dados['EditUsuario'])){
            $empty_input = false;
            $dados = array_map('trim', $dados);
            if(in_array("", $dados)){
                $empty_input = true;
                echo "Necessário preencher os campos!";
            }elseif(!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)){
                $empty_input = true;
                echo "Corrija o email para um email válido!";
            }

            if(!$empty_input){
                $query_up_usuario = "UPDATE usuarios SET nome:=nome, email=:email WHERE id=:id";
                $edit_usuario = $conn->prepare($query_up_usuario);
                $edit_usuario->bindParam(':nome', $dados['nome', PDO::PARAM_STR]);
                $edit_usuario->bindParam(':email', $dados['email', PDO::PARAM_STR]);
                $edit_usuario->bindParam(':id', $dados['id', PDO::PARAM_INT]);
                if($edit_usuario->execute()){
                    $_SESSION['msg'] = "Editado com sucesso";
                    header("Location: index.php");
                }else{
                    echo "Erro ao editar";
                }

                echo "Editar";
            }
        }
        ?>

        <form id="edit-usuario" method="POST" action="">
            <label>Nome: </label>
            <input type="text" name="nome" id="nome" placeholder="Nome completo"value="
            <?php 
            if(isset($dados['nome'])){
                echo $dados['nome'];
            }elseif(isset($row_usuario['nome'])){
                echo $row_usuario['nome'];
            }
            ?>"><br><br>

            <label>Email: </label>
            <input type="text" name="email" id="email" placeholder="Email"value="
            <?php 
            if(isset($dados['email'])){
                echo $dados['email'];
            }elseif(isset($row_usuario['email'])){
                echo $row_usuario['email'];
            }
            ?>"><br><br>

            <input type="submit" value="Salvar" name="EditUsuario">
        </form>

        <?php 

        ?>
    </body>
</html>