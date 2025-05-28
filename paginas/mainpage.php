<?php
session_start();

if (headers_sent($file, $line)) {
    die("Headers already sent in $file on line $line");
}

include("/var/www/html/Projeto/conecxao.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost/Projeto/paginas/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $remove_id = intval($_POST['remove_id']);

    $stmtRemove = $conn->prepare("DELETE FROM books WHERE id = ? AND user_id = ?");
    $stmtRemove->bind_param("ii", $remove_id, $user_id);
    if ($stmtRemove->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=removed");
        exit();
    } else {
        $msg = "<div class='alert alert-danger'>Erro ao remover: {$stmtRemove->error}</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['remove_id'])) {
    $title = htmlspecialchars($_POST['title'] ?? '');
    $current_page = intval($_POST['current_page'] ?? 0);
    $total_pages = intval($_POST['total_pages'] ?? 0);
    $rating = intval($_POST['rating'] ?? 0);
    $review = htmlspecialchars($_POST['review'] ?? '');
    $cover_url = '';

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $pasta = 'uploads/';
        if (!is_dir($pasta)) mkdir($pasta, 0755, true);

        $nome_tmp = $_FILES['imagem']['tmp_name'];
        $nome_final = $pasta . time() . '-' . uniqid() . '-' . basename($_FILES['imagem']['name']);

        if (move_uploaded_file($nome_tmp, $nome_final)) {
            $cover_url = $nome_final;
        }
    }

    $stmt = $conn->prepare("INSERT INTO books (title, current_page, total_pages, rating, review, cover_url, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiissi", $title, $current_page, $total_pages, $rating, $review, $cover_url, $user_id);

    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>Livro salvo com sucesso!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Erro ao salvar: {$stmt->error}</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MybookList</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
            margin-bottom: 125px;
        }
        .navbar-bg {
            background-color: #5E8E40;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-bg sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">MybookList</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="http://localhost/Projeto/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="http://localhost/Projeto/paginas/registerpage.php">Sign up</a></li>
                    <li class="nav-item"><a class="nav-link" href="http://localhost/Projeto/paginas/loginpage.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


<div class="container mt-4">
    <?= $msg ?>
    <h2>Adicionar livro</h2>
    <form method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="mb-3">
            <label>Título</label>
            <input type="text" name="title" class="form-control" required />
        </div>
        <div class="mb-3">
            <label>Página atual</label>
            <input type="number" name="current_page" class="form-control" min="0" required />
        </div>
        <div class="mb-3">
            <label>Total de páginas</label>
            <input type="number" name="total_pages" class="form-control" min="1" required />
        </div>
        <div class="mb-3">
            <label>Nota (0 a 5)</label>
            <input type="number" name="rating" class="form-control" min="0" max="5" required />
        </div>
        <div class="mb-3">
            <label>Review</label>
            <textarea name="review" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label>Capa do livro</label>
            <input type="file" name="imagem" id="imagem1" class="form-control" accept="image/*" />
        </div>
        <img id="preview1" style="display:none; width:100px;" class="img-thumbnail mb-3" />
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>

    <h2 class="mt-5">Meus livros</h2>
    <?php
    $stmtList = $conn->prepare("SELECT * FROM books WHERE user_id = ? ORDER BY id DESC");
    $stmtList->bind_param("i", $user_id);
    $stmtList->execute();
    $result = $stmtList->get_result();

    if ($result->num_rows === 0) {
        echo "<p>Nenhum livro adicionado ainda.</p>";
    } else {
        while ($row = $result->fetch_assoc()) {
            $cover = htmlspecialchars($row['cover_url']);
            $title = htmlspecialchars($row['title']);
            $review = htmlspecialchars($row['review']);
            $ratingStars = str_repeat("⭐", $row['rating']);
            $currentPage = intval($row['current_page']);
            $totalPages = intval($row['total_pages']);
            $bookId = intval($row['id']);

            echo "
            <div class='card my-3 shadow' style='max-width: 600px;'>
                <div class='row g-0'>
                    <div class='col-md-4'>
                        <img src='{$cover}' class='img-fluid rounded-start' alt='Capa do livro' />
                    </div>
                    <div class='col-md-8'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$title}</h5>
                            <p class='card-text'><strong>Review:</strong> {$review}</p>
                            <p class='card-text'>Nota: {$ratingStars}</p>
                            <p class='card-text'>Progresso: {$currentPage} / {$totalPages}</p>
                            <progress value='{$currentPage}' max='{$totalPages}' class='w-100'></progress>
                            <form method='post' class='mt-3' onsubmit=\"return confirm('Remover o livro \'{$title}\'?');\">
                                <input type='hidden' name='remove_id' value='{$bookId}' />
                                <button type='submit' class='btn btn-danger btn-sm'>Remover</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            ";
        }
    }
    ?>
</div>

<footer class="bg-dark text-white fixed-bottom py-3">
    <ul class="d-flex justify-content-evenly mb-0 list-unstyled">
        <li><p class="mb-0">© MybookList</p></li>
        <li><a href="#" class="text-white text-decoration-none">Contact</a></li>
        <li><p class="mb-0">CNPJ: 00.000.000/0000-00</p></li>
    </ul>
</footer>

<script>
function setupImagePreview(inputId, previewId) {
    document.getElementById(inputId).addEventListener("change", function() {
        const file = this.files[0];
        const preview = document.getElementById(previewId);
        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = "none";
        }
    });
}
setupImagePreview("imagem1", "preview1");
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
