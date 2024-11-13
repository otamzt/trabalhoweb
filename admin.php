<?php
$host = "localhost";
$dbname = "Seculus"; // Banco de dados
$user = "root"; // Nome do usuário
$password = ""; // Senha do usuário

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Definindo o modo de erro
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.html"); 
    exit();
}

$obraParaEditar = null;


if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $sql = "SELECT * FROM obras WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $obraParaEditar = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $table = isset($_GET['from']) ? $_GET['from'] : 'obras'; // Use um parâmetro para decidir a tabela
    $sql = "DELETE FROM $table WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header("Location: admin.php"); 
    exit();
}
// Lidar com a inserção ou atualização de obra
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = htmlspecialchars(trim($_POST['titulo']));
    $descricao = htmlspecialchars(trim($_POST['descricao']));
    $localizacao = htmlspecialchars(trim($_POST['localizacao']));
    $status = htmlspecialchars(trim($_POST['status']));
    $tipo = htmlspecialchars(trim($_POST['tipo']));
    $valid_status = ['Em andamento', 'Concluída', 'Planejada'];
    if (!in_array($status, $valid_status)) {
        die('Status inválido. O status deve ser "Em andamento", "Concluída" ou "Planejada".');
    }
    $valid_tipo = ['quadra', 'galpão', 'residência'];
    if (!in_array($tipo, $valid_tipo)) {
        die('Tipo inválido. O tipo deve ser "quadra", "galpão" ou "residência".');
    }
    $data_inicio = date('Y-m-d', strtotime(trim($_POST['data_inicio'])));
    $data_fim = date('Y-m-d', strtotime(trim($_POST['data_fim'])));
    if (!strtotime($data_inicio) || !strtotime($data_fim)) {
        die('Data inválida.');
    }
   
    if (isset($_FILES['img_obras']) && $_FILES['img_obras']['error'] === UPLOAD_ERR_OK) {
        $img_obras = file_get_contents($_FILES['img_obras']['tmp_name']);
    } else {
        $img_obras = null; // Permitir que a imagem não seja atualizada
    }

    if (isset($_POST['id'])) { // Atualizar obra existente
        $id = intval($_POST['id']);
        $sql = "UPDATE obras SET titulo = ?, descricao = ?, localizacao = ?, status = ?, tipo = ?, data_inicio = ?, data_fim = ?" . ($img_obras ? ", img_obras = ?" : "") . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $params = [$titulo, $descricao, $localizacao, $status, $tipo, $data_inicio, $data_fim];
        if ($img_obras) {
            $params[] = $img_obras; // Adiciona a nova imagem se fornecida
        }
        $params[] = $id;
        $stmt->execute($params);
        header("Location: admin.php"); // Redireciona após a atualização
        exit();
    } else { // Inserir nova obra
        if (!$img_obras) {
            die('Erro no upload da imagem ou imagem não fornecida.');
        }
        $sql = "INSERT INTO obras (titulo, descricao, localizacao, status, tipo, data_inicio, data_fim, img_obras) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titulo, $descricao, $localizacao, $status, $tipo, $data_inicio, $data_fim, $img_obras]);
        header("Location: admin.php"); // Redireciona após a inserção
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração - Seculus Construtora LTDA</title>
    <link rel="icon" type="image/x-icon" href="logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-5">Administração</h1>
        
        <div id="adminContent">
            <h2><?php echo $obraParaEditar ? 'Editar Obra' : 'Cadastro de Obra'; ?></h2>
            <form action="admin.php<?php echo $obraParaEditar ? '?edit=' . $obraParaEditar['id'] : ''; ?>" method="POST" enctype="multipart/form-data">
                <?php if ($obraParaEditar): ?>
                    <input type="hidden" name="id" value="<?php echo $obraParaEditar['id']; ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" value="<?php echo $obraParaEditar ? htmlspecialchars($obraParaEditar['titulo']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea name="descricao" id="descricao" class="form-control" required><?php echo $obraParaEditar ? htmlspecialchars($obraParaEditar['descricao']) : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="localizacao">Localização:</label>
                    <input type="text" name="localizacao" id="localizacao" class="form-control" value="<?php echo $obraParaEditar ? htmlspecialchars($obraParaEditar['localizacao']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="Em andamento" <?php echo $obraParaEditar && $obraParaEditar['status'] == 'Em andamento' ? 'selected' : ''; ?>>Em andamento</option>
                        <option value="Concluída" <?php echo $obraParaEditar && $obraParaEditar['status'] == 'Concluída' ? 'selected' : ''; ?>>Concluída</option>
                        <option value="Planejada" <?php echo $obraParaEditar && $obraParaEditar['status'] == 'Planejada' ? 'selected' : ''; ?>>Planejada</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo:</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="quadra" <?php echo $obraParaEditar && $obraParaEditar['tipo'] == 'quadra' ? 'selected' : ''; ?>>Quadra</option>
                        <option value="galpão" <?php echo $obraParaEditar && $obraParaEditar['tipo'] == 'galpão' ? 'selected' : ''; ?>>Galpão</option>
                        <option value="residência" <?php echo $obraParaEditar && $obraParaEditar['tipo'] == 'residência' ? 'selected' : ''; ?>>Residência</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="data_inicio">Data de Início:</label>
                    <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="<?php echo $obraParaEditar ? $obraParaEditar['data_inicio'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="data_fim">Data de Fim:</label>
                    <input type="date" name="data_fim" id="data_fim" class="form-control" value="<?php echo $obraParaEditar ? $obraParaEditar['data_fim'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="img_obras">Imagem:</label>
                    <input type="file" name="img_obras" id="img_obras" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary mt-3"><?php echo $obraParaEditar ? 'Atualizar Obra' : 'Cadastrar Obra'; ?></button>
            </form>

            <?php
            // Listar obras
            // Listar obras
            $sql = "SELECT * FROM obras";
            $stmt = $pdo->query($sql);
            $obras = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($obras):
                echo '<h2 class="mt-5">Obras Cadastradas</h2>';
                echo '<table class="table table-striped">';
                echo '<thead><tr><th>Título</th><th>Ações</th></tr></thead>';
                echo '<tbody>';
                foreach ($obras as $obra):
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($obra['titulo']) . '</td>';
                    echo '<td>
                            <a href="admin.php?edit=' . $obra['id'] . '" class="btn btn-warning">Editar</a>
                            <a href="admin.php?delete=' . $obra['id'] . '&from=obras" class="btn btn-danger" onclick="return confirm(\'Tem certeza que deseja excluir esta obra?\');">Excluir</a>
                        </td>';
                    echo '</tr>';
                endforeach;
                echo '</tbody>';
                echo '</table>';
            endif;
            ?>
        </div>

        
        <h2>Contatos Recebidos</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Listar contatos
                $sql = "SELECT nome, email, mensagem, id FROM contato";
                $stmt = $pdo->query($sql);
                $contatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($contatos as $contato):
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($contato['nome']) . '</td>';
                    echo '<td>
                            <button class="btn btn-info showDetails" data-id="' . $contato['id'] . '">+</button>
                            <a href="admin.php?delete=' . $contato['id'] . '&from=contato" class="btn btn-danger mt-2">Remover</a> 
                        </td>';
                    echo '</tr>';
                    echo '<tr class="details" id="details-' . $contato['id'] . '" style="display: none;">';
                    echo '<td colspan="2"><strong>Email:</strong> ' . htmlspecialchars($contato['email']) . '<br>';
                    echo '<strong>Mensagem:</strong> ' . nl2br(htmlspecialchars($contato['mensagem'])) . '<br>';
                    echo '</td>';
                    echo '</tr>';
                endforeach;
                ?>
            </tbody>
        </table>
    </div>

    <script>
    $(document).ready(function() {
        $(".showDetails").click(function() {
            var id = $(this).data('id');
            var detailsRow = $("#details-" + id);
            detailsRow.toggle(); 

            // Altera o símbolo do botão
            var button = $(this);
            if (detailsRow.is(":visible")) {
                button.text("-"); 
            } else {
                button.text("+");
            }
        });
    });
</script>
</body>
</html>
