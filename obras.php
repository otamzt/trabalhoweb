<?php
// Conectar ao banco de dados MySQL
$host = 'localhost';
$dbname = 'seculus';
$username = 'root';  // Ajuste conforme o seu usuário do banco de dados
$password = '';      // Ajuste conforme a senha do seu banco de dados

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Consulta para obter as obras por tipo (Quadras, Galpões, Residências)
$query = "SELECT * FROM obras WHERE tipo IN ('quadra', 'galpão', 'residência')";
$stmt = $pdo->query($query);

// Organizar as obras por tipo
$obras = [
    'quadra' => [],
    'galpão' => [],
    'residência' => []
];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $obras[$row['tipo']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seculus Construtora LTDA - Obras</title>
    <link rel="icon" type="image/x-icon" href="logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header class="text-center w-100 position-relative" style="background-color: #79838b; padding: 20px; padding-bottom: 60px;">
        <img src="logo.jpg" alt="Logo Seculus" class="img-fluid" style="max-width: 300px;">
        <div class="bg-secondary">
            <div class="btn-group shadow-lg position-absolute w-100" style="bottom: 0; left: 0;">
                <a href="index.html" class="btn btn-secondary w-100" id="index-btn">Página Principal</a>
                <a href="empresa.html" class="btn btn-secondary w-100" id="empresa-btn">Sobre a Empresa</a>
                <a href="#" class="btn btn-secondary w-100" id="obras-btn">Obras</a>
                <a href="contato.html" class="btn btn-secondary w-100" id="contato-btn">Informações de Contato</a>
                <a href="admin.php" class="btn btn-warning w-100" id="admin-btn" target="_blank"> Pagina do admninstrador </a>
                <a href="login.html" class="btn btn-primary w-100" id="login-btn">Login</a>
            </div>
        </div>

        <!-- Submenu de Obras -->
        <div id="obras-submenu" class="position-absolute text-center btn-group-vertical" style="display: none;">
            <a href="#quadras" class="btn btn-dark">Quadras</a>
            <a href="#galpoes" class="btn btn-dark">Galpões</a>
            <a href="#residências" class="btn btn-dark">Residências</a>
        </div>
        <!-- Submenu de Sobre a Empresa -->
        <div id="empresa-submenu" class="position-absolute text-center btn-group-vertical" style="display: none;">
            <a href="#historia" class="btn btn-dark">História</a>
            <a href="#valores" class="btn btn-dark">Valores</a>
            <a href="#missao" class="btn btn-dark">Missão</a>
        </div>

        <!-- Submenu de Informações para Contato -->
        <div id="contato-submenu" class="position-absolute text-center btn-group-vertical" style="display: none;">
            <a href="https://www.google.com/maps/place/Séculus+Construtora/@-18.4744919,-47.2022963,17z/data=!3m1!4b1!4m6!3m5!1s0x94af41bd58e079f5:0xc939a844f3c4504d!8m2!3d-18.474497!4d-47.199716!16s%2Fg%2F1pt_2g5g_?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D" 
               class="btn btn-dark" target="_blank">Localização</a>
            <a href="mailto:seculus@netcoro.com.br" class="btn btn-dark">E-mail</a>
            <a href="tel:+553438411001" class="btn btn-dark">Telefone</a>
        </div>
        
    </header>

    <div class="container mt-4">
        <h1 class="text-center">Obras Realizadas</h1>

        <!-- Seção Quadras -->
        <section id="quadras" class="mt-5">
            <h2 class="text-center">Quadras</h2>
            <div class="row">
                <?php foreach ($obras['quadra'] as $obra): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($obra['img_obras']); ?>" class="card-img-top" alt="Imagem da obra">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($obra['titulo']); ?></h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($obra['descricao'])); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Seção Galpões -->
        <section id="galpoes" class="mt-5">
            <h2 class="text-center">Galpões</h2>
            <div class="row">
                <?php foreach ($obras['galpão'] as $obra): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($obra['img_obras']); ?>" class="card-img-top" alt="Imagem da obra">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($obra['titulo']); ?></h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($obra['descricao'])); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Seção Residências -->
        <section id="residências" class="mt-5">
            <h2 class="text-center">Residências</h2>
            <div class="row">
                <?php foreach ($obras['residência'] as $obra): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($obra['img_obras']); ?>" class="card-img-top" alt="Imagem da obra">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($obra['titulo']); ?></h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($obra['descricao'])); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
    <!-- Rodapé -->
    <footer class="text-center text-white mt-5" style="background-color: #79838b; padding: 20px;">
        <div class="container">
            <p class="mb-1">© 2024 Seculus Construtora LTDA - Todos os direitos reservados.</p>
            <p class="mb-1">Endereço: Rua Arthur Bernades, 455 - Coromandel, MG</p>
            <p class="mb-1">Telefone: <a href="tel:+553438411001" class="text-white">+55 (34) 3841-1001</a></p>
            <p class="mb-1">Email: <a href="mailto:seculus@netcoro.com.br" class="text-white">seculus@netcoro.com.br</a></p>
            <div class="mt-3">
                <a href="https://www.instagram.com/seculus_construtora" target="_blank" class="text-white me-3">Instagram</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       $(document).ready(function() {
    function toggleSubmenu(buttonId, submenuId) {
        $(buttonId).hover(
            function() {
                var btnOffset = $(this).offset();
                var btnWidth = $(this).outerWidth();
                $(submenuId).css({
                    top: btnOffset.top + $(this).outerHeight(),
                    left: btnOffset.left,
                    width: btnWidth
                }).stop().slideDown();
            },
            function() {
                $(submenuId).stop().slideUp();
            }
        );

        $(submenuId).hover(
            function() {
                $(this).stop().slideDown();
            },
            function() {
                $(this).stop().slideUp();
            }
        );
    }

    toggleSubmenu('#obras-btn', '#obras-submenu');
    toggleSubmenu('#empresa-btn', '#empresa-submenu');
    toggleSubmenu('#contato-btn', '#contato-submenu');
    
    $('a[href^="#"]').click(function(event) {
        event.preventDefault();
        var target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top
            }, 800);
        }
    });
    $('#admin-btn').hide();
            $.ajax({
                url: 'check_login.php', 
                type: 'GET',
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $.ajax({
                            url: 'check_admin.php', 
                            type: 'GET',
                            success: function(adminResponse) {
                                var adminData = JSON.parse(adminResponse);
                                if (adminData.status === 'success') {
                                    $('#admin-btn').show();
                                } 
                            },
                            error: function() {
                                console.error('Erro ao verificar status de administrador.');
                            }
                        });
                        $('#login-btn').text('Deslogar').removeClass('btn-primary').addClass('btn-danger');
                        $('#login-btn').click(function() {
                            $.ajax({
                                url: 'logout.php',
                                type: 'GET',
                                success: function(logoutResponse) {
                                    var logoutData = JSON.parse(logoutResponse);
                                    if (logoutData.status === 'success') {                           
                                        window.location.href = 'login.html';
                                    } else {
                                        alert('Erro ao deslogar.');
                                    }
                                }
                            });
                        });

                    } 
                    else {
                        $('#login-btn').text('Login').removeClass('btn-danger').addClass('btn-primary');
                    }
                },
                error: function() {
                    console.error('Erro ao verificar status de login.');
                }
            });
});
    </script>
</body>
</html>
