<?php
include 'db.php';
session_start();

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Vérifier si l'identifiant du candidat est présent
if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$candidate_id = $_GET['id'];

// Récupérer les informations du candidat
$stmt = $conn->prepare("SELECT * FROM candidates WHERE id = :id");
$stmt->bindParam(':id', $candidate_id, PDO::PARAM_INT);
$stmt->execute();
$candidate = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidate_name = $_POST['candidate_name'];

    // Mettre à jour le candidat
    $stmt = $conn->prepare("UPDATE candidates SET name = :name WHERE id = :id");
    $stmt->bindParam(':name', $candidate_name);
    $stmt->bindParam(':id', $candidate_id, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Candidat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <style>
        .btn-primary {
            background-color: #6f42c1;
            border-color: #6f42c1;
        }
        .btn-primary:hover {
            background-color: #5a31a5;
            border-color: #5a31a5;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Modifier le Candidat</h2>
    <form action="edit.php?id=<?= $candidate_id ?>" method="post">
        <div class="form-group">
            <label for="candidate_name">Nom du Candidat:</label>
            <input type="text" class="form-control" id="candidate_name" name="candidate_name" value="<?= htmlspecialchars($candidate['name']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Sauvegarder</button>
    </form>
</div>
</body>
</html>
