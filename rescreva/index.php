<?php
    // Inclui os arquivos PHP necessários para as classes e a conexão com o banco de dados
    require_once('classes/Crud.php');
    require_once('conexao/conexao.php');

    // Cria uma instância da classe Database para gerenciar a conexão com o banco de dados
    $database = new Database();
    $db = $database->getConnection();

    // Cria uma instância da classe Crud, que será usada para realizar operações CRUD no banco de dados
    $crud = new Crud($db);

    // Verifica se a variável 'action' está definida na URL
    if(isset($_GET['action'])){
        // Inicia um switch com base no valor da variável 'action' na URL
        switch($_GET['action']){
            case 'create':
                // Se 'action' for 'create', chama o método 'create' da classe Crud para criar um novo registro com os dados enviados via POST
                $crud->create($_POST);
                // Em seguida, lê todos os registros no banco de dados e armazena-os na variável 
                $rows = $crud->read();
                break;
            case 'read':
                // Se 'action' for 'read', simplesmente lê todos os registros no banco de dados e armazena-os na variável 
                $rows = $crud->read();
                break;
            case 'update':
                // Se 'action' for 'update', verifica se a variável 'id' está definida nos dados enviados via POST
                if(isset($_POST['id'])){
                    // Se 'id' estiver definida, chama o método 'update' da classe Crud para atualizar um registro no banco de dados
                    $crud->update($_POST);
                }
                // Em seguida, lê todos os registros no banco de dados e armazena-os na variável 
                $rows = $crud->read();
                break;
            default:
                // Se 'action' não corresponder a nenhum dos casos acima, lê todos os registros no banco de dados e armazena-os na variável 
                $rows = $crud->read();
                break;
        }
    }else{
        // Se a variável 'action' não estiver definida na URL, lê todos os registros no banco de dados e armazena-os na variável 
        $rows = $crud->read();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <style>
        /* Estilos CSS para a formatação da página */
        
    </style>
</head>
<body>
        <?php
            // Verifica se a variável 'action' está definida na URL e se é igual a 'update', e se a variável 'id' também está definida
            if(isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id'])){
                // Recupera o valor da variável 'id' da URL
                $id = $_GET['id'];
                // Lê os dados de um registro específico com base no 'id'
                $result = $crud->readOne($id);

                // Verifica se o registro foi encontrado
                if(!$result){
                    // Se o registro não for encontrado, exibe uma mensagem e encerra o script
                    echo "Registro não encontrado";
                    exit();
                }
                // Atribui os valores dos campos do registro às variáveis locais
                $modelo = $result['modelo'];
                $marca = $result['marca'];
                $placa = $result['placa'];
                $cor = $result['cor'];
                $ano = $result['ano'];
        ?>

       
        <form action="?action=update" method="POST">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <label for="modelo">Modelo</label>
            <input type="text" name="modelo" value="<?php echo $modelo?>">

            // Campos de entrada semelhantes para outros atributos do registro 

            <input type="submit" value="Atualizar" name="enviar">
        </form>

        <?php
        }else{
            // Se 'action' não for 'update' ou 'id' não estiver definida na URL, exibe um formulário para criar um novo registro
        ?>

        // Exibe um formulário para criar um novo registro
        <form action="?action=create" method="POST">
            // Campos de entrada para criar um novo registro 
        </form>

        <?php
        }
        ?>

        //Exibe uma tabela para listar os registros de carros 
        <table>
            <tr>
                <td>Id</td>
                <td>Modelo</td>
                <td>Marca</td>
                <td>Placa</td>
                <td>Cor</td>
                <td>Ano</td>
            </tr>

            <?php
            // Verifica se o número de registros na variável $ é igual a zero
            if($rows->rowCount() == 0){
                // Se não houver registros, exibe uma mensagem informando que nenhum dado foi encontrado
                echo "<tr>";
                echo "<td colspan='7'>Nenhum dado encontrado</td>";
                echo "</tr>";
            } else {
                // Se houver registros, percorre-os e exibe-os na tabela
                while($row = $rows->fetch(PDO::FETCH_ASSOC)){
                    echo "<tr>";
                    // Exibe os valores dos campos do registro nas colunas da tabela
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['modelo'] . "</td>";
                    echo "<td>" . $row['marca'] . "</td>";
                    echo "<td>" . $row['placa'] . "</td>";
                    echo "<td>" . $row['cor'] . "</td>";
                    echo "<td>" . $row['ano'] . "</td>";
                    echo "<td>";
                    // Exibe links 'Editar' e 'Deletar' para cada registro
                    echo "<a href='?action=update&id=" . $row['id'] . "'>Editar</a>";
                    echo "<a href='?action=delete&id=" . $row['id'] . "' onclick='return confirm(\"Tem certeza que quer apagar esse registro?\")' class='delete'>Deletar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </body>
</html>

