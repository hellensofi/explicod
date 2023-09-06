<?php
include_once('Conexao/conexao.php'); // Inclui o arquivo que contém a classe de conexão com o banco de dados.

$db = new Database(); // Cria uma instância da classe Database para estabelecer a conexão com o banco de dados.

class Crud{
    private $conn;
    private $table_name = "carros"; // Define o nome da tabela do banco de dados com a qual esta classe irá interagir.

    public function __construct($db){
        $this->conn = $db; // O construtor recebe a conexão com o banco de dados como parâmetro e a armazena em uma propriedade privada ($conn).
    }

    public function create($postValues){
        // O método create recebe um array ($postValues) contendo os valores dos campos do novo registro a ser inserido na tabela "carros".
        // Os valores são obtidos a partir do array $postValues.

        // Prepara a consulta SQL para inserção de dados na tabela.
        $query = "INSERT INTO ". $this->table_name . " (modelo, marca, placa, cor, ano) VALUES (?,?,?,?,?)";
        $stmt = $this->conn->prepare($query); // Prepara a consulta usando a conexão.

        // Vincula os valores dos campos aos parâmetros na consulta SQL.
        $modelo = $postValues['modelo'];
        $marca = $postValues['marca'];
        $placa = $postValues['placa'];
        $cor = $postValues['cor'];
        $ano = $postValues['ano'];
        $stmt->bindParam(1, $modelo);
        $stmt->bindParam(2, $marca);
        $stmt->bindParam(3, $placa);
        $stmt->bindParam(4, $cor);
        $stmt->bindParam(5, $ano);

        // Chama o método read para recuperar os registros da tabela "carros" e, em seguida, executa a consulta de inserção.
        if($stmt->execute()){
            print "<script>alert('Cadastro Ok!')</script>"; // Exibe um alerta em JavaScript.
            print "<script> location.href='?action=read'; </script>"; // Redireciona para a página de leitura de registros.
            return true; // Retorna verdadeiro se a inserção for bem-sucedida.
        }else{
            return false; // Retorna falso se a inserção falhar.
        }
    }

    public function read(){
        // O método read consulta todos os registros na tabela "carros" e retorna o resultado.

        $query = "SELECT * FROM ". $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Retorna o resultado da consulta.
    }

    public function update($postValues){
        // O método update recebe um array ($postValues) contendo os valores dos campos a serem atualizados em um registro existente.

        // Obtém os valores dos campos e o ID do registro a ser atualizado.
        $id = $postValues['id'];
        $modelo = $postValues['modelo'];
        $marca = $postValues['marca'];
        $placa = $postValues['placa'];
        $cor = $postValues['cor'];
        $ano = $postValues['ano'];

        // Verifica se algum dos campos está vazio.
        if(empty($id) || empty($modelo) || empty($marca) || empty($placa) || empty($cor) || empty($ano)){
            return false; // Retorna falso se algum campo estiver vazio.
        }

        // Prepara a consulta SQL para atualização do registro com base no ID.
        $query = "UPDATE ". $this->table_name . " SET modelo = ?, marca = ?, placa = ?, cor = ?, ano = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        // Vincula os valores dos campos e o ID aos parâmetros na consulta SQL.
        $stmt->bindParam(1, $modelo);
        $stmt->bindParam(2, $marca);
        $stmt->bindParam(3, $placa);
        $stmt->bindParam(4, $cor);
        $stmt->bindParam(5, $ano);
        $stmt->bindParam(6, $id);

        // Executa a consulta de atualização.
        if($stmt->execute()){
            return true; // Retorna verdadeiro se a atualização for bem-sucedida.
        }else{
            return false; // Retorna falso se a atualização falhar.
        }
    }

    public function readOne($id){
        // O método readOne consulta um registro específico na tabela "carros" com base no ID.

        $query = "SELECT * FROM ". $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna o resultado da consulta como um array associativo.
    }

    public function delete($id){
        // O método delete exclui um registro na tabela "carros" com base no ID.

        $query = "DELETE FROM ". $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        return $stmt->execute(); // Executa a consulta de exclusão e retorna o resultado (verdadeiro ou falso).
    }
}
?>
