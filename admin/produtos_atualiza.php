<?php
include 'acesso_com.php';
include '../conn/connect.php';

//Toda esse conjunto de código serve para evitar conflito de nomes entre arquivos e serve para alterar a imagem atual pela imagem nova.
//Se o usuário clicou no botão atualizar.
if($_FILES['imagemfile']['name']){//Se o usuário escolher uma imagem.
    unlink("../images/".$_POST['imagem_atual']); //Apaga a imagem atual.
    $nome_img = $_FILES['imagemfile']['name'];
    $tmp_img = $_FILES['imagemfile']['tmp_name'];
    $rand = rand(100001, 999999);
    $dir_img = "../images/".$rand.$nome_img;
    move_uploaded_file($tmp_img, $dir_img); //TRansfere a imagem do php para o servidor de arquivos.
    $nome_img = $rand.$nome_img;
}else{
    $nome_img = $_POST['imagem_atual'];
}


//Seleciona os dados do produto atual ao iniciar a página.
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_produto = $conn->query("SELECT * FROM produtos WHERE id = $id");
    $produto = $sql_produto->fetch_assoc();
}

// Verifica se o formulário foi enviado via POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verifica se o ID foi passado na URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $tipo_id = $_POST['tipo_id'];
        $descricao = $_POST['descricao'];
        $resumo = $_POST['resumo'];
        $valor = $_POST['valor'];
        $imagem = $_POST['imagem'];
        $destaque = $_POST['destaque'] === 'Sim' ? 'Sim' : 'Não';


        // Tenta atualizar o usuário no banco de dados
        $sql = $conn->query("UPDATE produtos SET  tipo_id = '$tipo_id', descricao = '$descricao', resumo = '$resumo', valor = '$valor', imagem = '$imagem', destaque = '$destaque' WHERE id = $id");

        // Verifica se a atualização foi bem-sucedida
        if ($sql) {
            // Mensagem de sucesso
            echo "<script>
            alert('Produto atualizado com sucesso!');
            window.location.href='produtos_lista.php';
          </script>";
        } else {
            // Mensagem de erro
            echo "<script>
            alert('Erro ao tentar atualizar o produto.');
            window.location.href='produtos_atualiza.php';
          </script>";
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Produtos - Atualizar</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/meu_estilo.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <?php include "menu_adm.php"; ?>
    <main class="container">
        <div class="row">
         <div class="col-xs-12 col-sm-offset-2 col-sm-6  col-md-8">
                <h2  class="thumbnail alert-danger">
                    <a href="produtos_lista.php">
                        <button class="btn btn-alert-danger" type="button"  style="background-color: #d9534f; color: white;">
                            <span class="fas fa-chevron-left" aria-hidden="true" ></span>
                        </button>
                    </a>
                    Atualizando Produto
                </h2>
                <div class="thumbnail" style="padding: 20px;">

                <div class="alert alert-danger" role="alert">
                        <form action="produtos_atualiza.php?id=<?php echo $_GET['id']; ?>" method="POST" name="form_atualiza_produto" id="form_atualiza_produto">
                            <!-- Campo do tipo de produto -->
                            <label for="tipo_id">Tipo de Produto</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-tags" aria-hidden="true"></span>
                                </span>
                                <select name="tipo_id" id="tipo_id" class="form-control" required>
                                    <!-- Essa sintaxe é um operador ternário (? :), que é uma forma simplificada de escrever um if-else em PHP. -->
                                    <!-- Essa linha verifica se o tipo_id do produto é 1 (Churrasco). Se for verdadeiro (true), adiciona o atributo selected para marcar a opção no <select>.
                                     Caso contrário, não adiciona nada (''). -->
                                    <option value="1" <?php echo $produto['tipo_id'] == 1 ? 'selected' : '' ?>>Churrasco</option>
                                    <option value="2" <?php echo $produto['tipo_id'] == 2 ? 'selected' : '' ?>>Sobremesa</option>
                                    <option value="3" <?php echo $produto['tipo_id'] == 3 ? 'selected' : '' ?>>Bebida</option>
                                </select>
                            </div>
                            <br>

                            <!-- Campo  do nome do produto -->
                            <label for="descricao">Produto</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </span>
                                <input type="text" name="descricao" value="<?php echo $produto['descricao']; ?>" id="descricao" maxlength="30" placeholder="Digite o nome do produto" class="form-control" required autocomplete="off">
                            </div>
                            <br>

                            <!-- Campo do resumo -->
                            <label for="resumo">Resumo</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </span>
                                <input type="text" name="resumo" value="<?php echo $produto['resumo']; ?>" id="resumo" maxlength="80" placeholder="Digite o resumo" class="form-control" required autocomplete="off">
                            </div>
                            <br>

                            <!-- Campo do valor -->
                            <label for="valor">Valor</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                                </span>
                                <input type="number" name="valor" value="<?php echo $produto['valor']; ?>" id="valor" maxlength="8" placeholder="0.00" class="form-control" required autocomplete="off" step="0.01" min="0">
                            </div>
                            <br>

                            <label for="imagem_atual">Imagem Atual:</label>
                            <div class="input-group">
                               <span class="input-group-addon">
                               <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                               </span>
                                    <img src="../images/<?php echo $produto['imagem'] ?>" alt="Imagem Atual do Produto" srcset="" style = "width: 300px; height: 200px; padding: 1px; ">
                                    <input type="hidden" name="imagem_atual" id="imagem_atual" value="<?php echo $row['imagem'] ?>">
                                    
                                    
                                </div>
                            <br>

                            <!-- Campo da imagem -->
                            <label for="imagem">Imagem</label>
                            <div class="input-group" style="align-items: center; justify-content: flex-start; gap: 40px;">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                                </span>
                                <label for="imagem" style="background-color: #d9534f;  color: white; border: none; padding: 10px 15px; cursor: pointer;">Escolher Imagem</label>
                                <input type="file" name="imagem" id="imagem" class="form-control" required style="display: none;">
                            </div>
                            <br>



                            <!-- Campo do destaque -->
                            <label for="destaque">Destaque</label>
                            <div class="input-group" style="align-items: center; justify-content: flex-start; gap: 20px;">
                                <!-- <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
                                </span> -->
                              
                                    <label for="destaque" class="radio-inline">
                                        <input type="radio" name="destaque" id="destaque" value="Sim"
                                            <?php echo $produto['destaque'] == "Sim" ? "checked" : null; ?> style = "accent-color:#d9534f;">Sim
                                    </label>
                                    <label for="destaque" class="radio-inline">
                                        <input type="radio" name="destaque" id="destaque" value="Não"
                                            <?php echo $produto['destaque'] == "Não" ? "checked" : null; ?> style = "accent-color:#d9534f;">Não
                                    </label>
                           
                            </div>
                            <br>




                            <input type="submit" value="Atualizar" role="button" name="enviar" id="enviar" class="btn btn-block btn-danger" style="background-color: #d9534f;  color: white; border: none; padding: 10px 15px; cursor: pointer;">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>