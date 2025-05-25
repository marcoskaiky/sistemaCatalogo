<?php
require_once 'classes/Catalogo.php';
$catalogo = new Catalogo();

$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
$livroEditar = null;
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $genero = trim($_POST['genero']);
    $avaliacao = !empty($_POST['avaliacao']) ? (float)$_POST['avaliacao'] : null;

    if ($acao === 'alterar' && $id > 0) {
        if ($catalogo->alterarLivro($id, $titulo, $autor, $genero, $avaliacao)) {
            $mensagem = "Livro alterado com sucesso!";
        } else {
            $mensagem = "Erro ao alterar livro. Verifique os campos obrigatórios.";
        }
    } else {
        if ($catalogo->cadastrarLivro($titulo, $autor, $genero, $avaliacao)) {
            $mensagem = "Livro cadastrado com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar livro. Verifique os campos obrigatórios.";
        }
    }
}

if ($acao === 'excluir' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($catalogo->excluirLivro($id)) {
        $mensagem = "Livro excluído com sucesso!";
    } else {
        $mensagem = "Erro ao excluir livro.";
    }
}

if ($acao === 'alterar' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $livroEditar = $catalogo->detalharLivro($id);
}

$livros = $catalogo->listarLivros();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Livros</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        
        <?php if ($mensagem): ?>
            <p style="text-align: center; color: <?php echo strpos($mensagem, 'Erro') === false ? 'green' : 'red'; ?>;">
                <?php echo htmlspecialchars($mensagem); ?>
            </p>
        <?php endif; ?>

        <h1>Catálogo de Livros</h1>

        <div class="form-container">
            <form method="POST" action="index.php<?php echo $acao === 'alterar' ? '?acao=alterar&id=' . ($livroEditar['id'] ?? '') : ''; ?>">
                <?php if ($acao === 'alterar' && $livroEditar): ?>
                    <input type="hidden" name="id" value="<?php echo $livroEditar['id']; ?>">
                <?php endif; ?>
                <input type="text" name="titulo" placeholder="Título" value="<?php echo $livroEditar ? htmlspecialchars($livroEditar['titulo']) : ''; ?>" required>
                
                <input type="text" name="autor" placeholder="Autor" value="<?php echo $livroEditar ? htmlspecialchars($livroEditar['autor']) : ''; ?>" required>
                <select name="genero" required>
                    <option value="">Selecione um gênero</option>
                    <option value="Romance" <?php echo ($livroEditar && $livroEditar['genero'] === 'Romance') ? 'selected' : ''; ?>>Romance</option>
                    <option value="Ficção Científica" <?php echo ($livroEditar && $livroEditar['genero'] === 'Ficção Científica') ? 'selected' : ''; ?>>Ficção Científica</option>
                    <option value="Fantasia" <?php echo ($livroEditar && $livroEditar['genero'] === 'Fantasia') ? 'selected' : ''; ?>>Fantasia</option>
                    <option value="Suspense" <?php echo ($livroEditar && $livroEditar['genero'] === 'Suspense') ? 'selected' : ''; ?>>Suspense</option>
                    <option value="Terror" <?php echo ($livroEditar && $livroEditar['genero'] === 'Terror') ? 'selected' : ''; ?>>Terror</option>
                    <option value="Drama" <?php echo ($livroEditar && $livroEditar['genero'] === 'Drama') ? 'selected' : ''; ?>>Drama</option>
                    <option value="Aventura" <?php echo ($livroEditar && $livroEditar['genero'] === 'Aventura') ? 'selected' : ''; ?>>Aventura</option>
                    <option value="Biografia" <?php echo ($livroEditar && $livroEditar['genero'] === 'Biografia') ? 'selected' : ''; ?>>Biografia</option>
                    <option value="História" <?php echo ($livroEditar && $livroEditar['genero'] === 'História') ? 'selected' : ''; ?>>História</option>
                    <option value="Autoajuda" <?php echo ($livroEditar && $livroEditar['genero'] === 'Autoajuda') ? 'selected' : ''; ?>>Autoajuda</option>
                    <option value="Infantil" <?php echo ($livroEditar && $livroEditar['genero'] === 'Infantil') ? 'selected' : ''; ?>>Infantil</option>
                    <option value="Poesia" <?php echo ($livroEditar && $livroEditar['genero'] === 'Poesia') ? 'selected' : ''; ?>>Poesia</option>
                    <option value="Outro" <?php echo ($livroEditar && !in_array($livroEditar['genero'], ['Romance', 'Ficção Científica', 'Fantasia', 'Suspense', 'Terror', 'Drama', 'Aventura', 'Biografia', 'História', 'Autoajuda', 'Infantil', 'Poesia'])) ? 'selected' : ''; ?>>Outro</option>
                </select>
                
                <input type="number" name="avaliacao" placeholder="Avaliação (0-5)" step="0.1" min="0" max="5" value="<?php echo $livroEditar && $livroEditar['avaliacao'] !== null ? $livroEditar['avaliacao'] : ''; ?>">
                <div class="button-group">
                    <button type="submit"><?php echo $acao === 'alterar' ? 'Alterar' : 'Cadastrar'; ?></button>
                    <?php if ($acao === 'alterar'): ?>
                        <button type="button" onclick="window.location.href='index.php'" class="btn-voltar">Voltar</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="cards-container">
            <?php foreach ($livros as $livro): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                    <p><strong>Autor:</strong> <?php echo htmlspecialchars($livro['autor']); ?></p>
                    <p><strong>Gênero:</strong> <?php echo htmlspecialchars($livro['genero'] ?: 'N/A'); ?></p>
                    <p><strong>Avaliação:</strong> <?php echo $livro['avaliacao'] !== null ? $livro['avaliacao'] : 'N/A'; ?></p>
                    <div class="card-buttons">
                        <button onclick="window.location.href='index.php?acao=alterar&id=<?php echo $livro['id']; ?>'">Alterar</button>
                        <button class="delete-btn" onclick="if(confirm('Tem certeza que deseja excluir este livro?')) window.location.href='index.php?acao=excluir&id=<?php echo $livro['id']; ?>'">Excluir</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>