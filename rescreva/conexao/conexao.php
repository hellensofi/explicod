<?php
// Define uma classe chamada Database
class Database{
    // Define propriedades privadas que armazenam informações de conexão com o banco de dados
    private $host = "localhost";  // Nome do servidor de banco de dados
    private $db_name = "aula3crud";  // Nome do banco de dados
    private $username = "root";  // Nome de usuário do banco de dados
    private $senha = "";  // Senha do banco de dados
    private $conn;  // Variável que armazena a conexão com o banco de dados

    // Método público para estabelecer uma conexão com o banco de dados
    public function getConnection(){
        $this->conn = null;  // Inicializa a variável de conexão como nula

        try{
            // Tenta criar uma instância do objeto PDO para estabelecer a conexão com o banco de dados
            $this->conn = new PDO("mysql:host=". $this->host.";dbname=".$this->db_name, $this->username, $this->senha);
            // Configura o PDO para lançar exceções em caso de erros
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            // Captura exceções que podem ocorrer durante a tentativa de conexão
            echo "Erro na conexão: ". $e->getMessage();  // Exibe uma mensagem de erro
        }

        return $this->conn;  // Retorna a conexão estabelecida ou nula em caso de falha
    }
}
?>

