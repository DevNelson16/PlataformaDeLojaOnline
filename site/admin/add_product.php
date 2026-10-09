<?php

session_start();
require_once __DIR__ . '/../../conexao/db.php';

// Verifica admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {

    header('Location: login_admin.php');
    exit;

}


// Adicionar produto

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $name = $_POST['name'] ?? '';

    $category = $_POST['category'] ?? '';

    $price = $_POST['price'] ?? '';

    $description = $_POST['description'] ?? '';

    $image = $_POST['image'] ?? '';



    if (empty($name) || empty($price)) {


        $error = "Nome e preço são obrigatórios.";


    } else {


        $stmt = $pdo->prepare("
            INSERT INTO products
            (
                name,
                category,
                price,
                description,
                image
            )

            VALUES
            (?,?,?,?,?)
        ");


        $stmt->execute([

            $name,

            $category,

            $price,

            $description,

            $image

        ]);



        header('Location: admin.php');

        exit;


    }

}


?>



<!DOCTYPE html>
<html lang="pt">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Adicionar Produto</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="stylesheet" href="../css/admin.css">
</head>



<body class="pg-add-product">



<div class="container mt-5">


<div class="card p-4 mx-auto" style="max-width:600px;">



<h2 class="text-center mb-4">

Adicionar Produto

</h2>



<?php if(isset($error)): ?>

<div class="alert alert-danger">

<?= htmlspecialchars($error) ?>

</div>

<?php endif; ?>




<form method="POST">



<div class="mb-3">

<label class="form-label">

Nome do produto

</label>


<input 
type="text"
name="name"
class="form-control"
required>

</div>





<div class="mb-3">

<label class="form-label">

Categoria

</label>


<select name="category" class="form-control" required>


<option value="Camisolas">

Camisolas

</option>


<option value="Álbuns">

Álbuns

</option>


<option value="Acessórios">

Acessórios

</option>


</select>


</div>






<div class="mb-3">

<label class="form-label">

Preço (€)

</label>


<input 
type="number"
step="0.01"
name="price"
class="form-control"
required>


</div>







<div class="mb-3">

<label class="form-label">

Descrição

</label>


<textarea 
name="description"
class="form-control"
rows="4"></textarea>


</div>







<div class="mb-3">

<label class="form-label">

Imagem

</label>


<input 
type="text"
name="image"
class="form-control"
placeholder="ex: camisola.jpg">


</div>







<div class="d-flex gap-2">


<button class="btn btn-warning w-50">

Adicionar

</button>



<a href="admin.php" class="btn btn-dark w-50">

Cancelar

</a>



</div>




</form>


</div>


</div>




</body>

</html>