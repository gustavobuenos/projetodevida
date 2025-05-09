<?php
session_start();
require_once "C:/Turma2/xampp/htdocs/EnerVision/config.php"; 

// Processamento do envio da foto de perfil
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["profile_picture"])) {
  $file = $_FILES["profile_picture"];
  $uploadDir = __DIR__ . "/img/";

  if ($file["error"] !== 0) {
    $errorMessages = [
      1 => "o arquivo excede o tamanho máximo permitido pelo servidor.",
      2 => "o arquivo excede o tamanho máximo permitido pelo formulario.",
      3 => "o upload do arquivo foi feito parcialmente",
      4 => "nenhum arquivo foi enviado",
      6 => "pagina temporária ausente",
      7 => "falha ao escrever arquivo no disco",
      8 => "uma extensão PHP interrompeu o upload do arquivo."
    ];
    $_SESSION['upload_error'] = $errorMessages[$file["error"]] ?? "Erro desconhecido no upload";
  } else {
    $allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
    $fileType = mime_content_type($file["tmp_name"]);

    if (in_array($fileType, $allowedTypes)) {
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
      }

      $fileName = uniqid() . "-" . basename($file["name"]);
      $filePath = "img/" . $fileName;

      if (move_uploaded_file($file["tmp_name"], $uploadDir . $fileName)) {
        $stmt = $pdo->prepare("UPDATE usuarios SET profile_picture = ? WHERE id = ?");
        $stmt->execute([$filePath, $id]);
        $_SESSION['success_message'] = "Foto de perfil atualizada com sucesso!";
        header("Location: meu-perfil.php");
        exit();
      } else {
        $_SESSION['upload_error'] = "Erro ao mover o arquivo.";
      }
    } else {
      $_SESSION['upload_error'] = "Formato inválido. Use JPG, PNG, ou GIF.";
    }
  }
  header("Location: meu-perfil.php");
  exit();
}

$profilePicture = !empty($usuarios['profile_picture']) ? $usuarios['profile_picture'] : "img/default.png";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style2.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <script src="https://kit.fontawesome.com/904bf533d7.js" crossorigin="anonymous"></script>
  <title>Projeto de Vida</title>
  <style>
    /* Estilo do body e fundo */
    body {
      font-family: Arial, sans-serif;
      background-image: url('img/vista-superior-mundo-coracao-dia-conceito.jpg'); /* Caminho para a imagem de fundo */
      background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
      color: #333;
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }

    /* Estilo do Header */
    header {
      background-color: #2784af;
      color: white;
      padding: 20px;
      text-align: center;
    }

    /* Estilo da navegação */
    .nav-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px;
      background-color: #333;
    }

    .nav-bar img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }

    .name-usuario h1 {
      color: white;
      margin-left: 10px;
    }

    .buttons-index button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin: 5px;
    }

    .buttons-index button:hover {
      background-color: #45a049;
    }

    /* Seções principais */
    .container {
      margin: 30px auto;
      background-color: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #333;
      margin-bottom: 10px;
    }

    .perfil-section p {
      color: #555;
      line-height: 1.6;
    }

    /* Footer */
    .footer {
      text-align: center;
      padding: 20px;
      background-color: #2784af;
      color: white;
      margin-top: 40px;
    }

    /* Estilo do conteúdo sobre o projeto de vida */
    .content-section {
      margin-bottom: 40px;
    }

    .content-section h2 {
      font-size: 28px;
      color: #333;
    }

    .content-section p {
      font-size: 18px;
      color: #555;
      margin-bottom: 20px;
    }

    .content-section ul {
      list-style-type: disc;
      margin-left: 20px;
    }

    .content-section ul li {
      font-size: 18px;
      color: #555;
      margin-bottom: 10px;
    }

    blockquote {
      background-color: #e9f5e9;
      border-left: 5px solid #4CAF50;
      padding: 15px;
      font-style: italic;
      font-size: 18px;
      color: #666;
      margin: 20px 0;
    }
  </style>
</head>

<body>

  <!-- Navegação -->
  <nav class="nav-bar">
    <a href="meu-perfil.php">
      <img src="<?php echo $profilePicture; ?>" alt="Foto do perfil">
      <label for="file-upload">
        <i class="fas fa-camera"></i>
      </label>
      <form method="POST" enctype="multipart/form-data" style="display: none;">
        <input id="file-upload" type="file" name="profile_picture" onchange="this.form.submit()">
      </form>
    </a>
    <div class="name-usuario">
      <h1><?php echo $_SESSION['nome_usuario'] ?? ''; ?></h1>
    </div>
    <div class="buttons-index">
      <?php if (!isset($_SESSION['id_usuario'])) { ?>
        <a href='teste-de-personalidade.html'><button class='buttons'><strong>Teste de Personalidade</strong></button></a>
        <a href='oque-quero-ser.html'><button class='buttons'><strong>Veja Sobre Mim</strong></button></a>
        <a href='view/login.php'><button class='buttons'><strong>Sair</strong></button></a>
      <?php } ?>
    </div>
  </nav>

  <!-- Conteúdo sobre o Projeto de Vida -->
  <div class="container">
    <section class="content-section">
      <h2>O que é um Projeto de Vida?</h2>
      <p>O Projeto de Vida é um plano pessoal que ajuda você a refletir sobre <strong>quem você é</strong>, <strong>o que deseja ser</strong> e <strong>como vai alcançar isso</strong>.</p>
      <p>Ele envolve pensar nos seus sonhos, metas, valores, interesses, habilidades e nas ações que você pode tomar para construir o futuro que deseja.</p>
      <p>Não é um documento fixo — ele pode (e deve) ser ajustado ao longo da vida, conforme você cresce, aprende e enfrenta novos desafios.</p>
    </section>

    <section class="content-section">
      <h2>Para que serve o Projeto de Vida?</h2>
      <ul>
        <li><strong>Dar sentido às escolhas</strong>: ajuda a tomar decisões mais conscientes, tanto na vida pessoal quanto profissional.</li>
        <li><strong>Definir metas</strong>: transforma sonhos em objetivos alcançáveis.</li>
        <li><strong>Motivação</strong>: serve como um guia para manter o foco mesmo diante das dificuldades.</li>
        <li><strong>Autoconhecimento</strong>: permite que você entenda melhor seus valores, talentos e limitações.</li>
      </ul>
    </section>

    <section class="content-section">
      <h2>O que deve conter um Projeto de Vida?</h2>
      <ol>
        <li><strong>Quem sou eu?</strong> – Quais são meus valores, paixões, talentos?</li>
        <li><strong>O que quero?</strong> – Quais são meus sonhos, metas pessoais, acadêmicas e profissionais?</li>
        <li><strong>Como vou alcançar isso?</strong> – Que ações e decisões preciso tomar? Que obstáculos posso enfrentar?</li>
        <li><strong>Qual meu propósito?</strong> – Como quero impactar o mundo ou as pessoas ao meu redor?</li>
      </ol>
    </section>

    <section class="content-section">
      <h2>Exemplo Simples</h2>
      <blockquote>
        "Quero me tornar um profissional da área da saúde, porque gosto de ajudar pessoas. Para isso, vou me dedicar aos estudos, participar de projetos voluntários e buscar uma faculdade de Enfermagem. Sei que será difícil, mas vou persistir porque esse é meu propósito."
      </blockquote>
    </section>

    <section class="content-section">
      <h2>Por que começar cedo?</h2>
      <p>Quanto mais cedo você começar a pensar no seu projeto de vida, <strong>mais tempo terá para se preparar, testar caminhos e fazer escolhas alinhadas com o que realmente importa para você</strong>.</p>
      <p>Não é sobre planejar tudo com rigidez, mas sim <strong>ter direção e intenção</strong>.</p>
    </section>
  </div>

  <!-- Rodapé -->
  <div class="footer">
    <p>&copy; 2025 Meu Projeto de Vida</p>
  </div>

</body>

</html>
