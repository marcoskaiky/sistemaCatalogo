<?php
require_once 'Database.php';

class Catalogo {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function cadastrarLivro($titulo, $autor, $genero, $avaliacao) {
        if (empty($titulo) || empty($autor)) {
            return false;
        }
        try {
            $stmt = $this->db->prepare("INSERT INTO livros (titulo, autor, genero, avaliacao) VALUES (:titulo, :autor, :genero, :avaliacao)");
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':autor', $autor);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':avaliacao', $avaliacao, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao cadastrar livro: " . $e->getMessage());
        }
    }

    public function listarLivros() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM livros");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Erro ao listar livros: " . $e->getMessage());
        }
    }

    public function detalharLivro($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM livros WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            die("Erro ao detalhar livro: " . $e->getMessage());
        }
    }

    public function alterarLivro($id, $titulo, $autor, $genero, $avaliacao) {
        if (empty($titulo) || empty($autor)) {
            return false;
        }
        try {
            $stmt = $this->db->prepare("UPDATE livros SET titulo = :titulo, autor = :autor, genero = :genero, avaliacao = :avaliacao WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':autor', $autor);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':avaliacao', $avaliacao, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao alterar livro: " . $e->getMessage());
        }
    }

    public function excluirLivro($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM livros WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao excluir livro: " . $e->getMessage());
        }
    }
}
?>