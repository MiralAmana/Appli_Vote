<?php
include 'db.php';
session_start();

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Vérifier si l'identifiant du candidat est passé en paramètre
if (isset($_GET['id'])) {
    $candidate_id = intval($_GET['id']); // Assurez-vous que l'identifiant est un entier

    // Préparer et exécuter la suppression
    $stmt = $conn->prepare("DELETE FROM candidates WHERE id = :id");
    $stmt->bindParam(':id', $candidate_id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        // Suppression réussie, rediriger vers la page admin
        header('Location: admin.php');
        exit;
    } else {
        // En cas d'erreur, afficher un message
        echo "<div class='alert alert-danger'>Erreur lors de la suppression du candidat.</div>";
    }
} else {
    // Pas d'identifiant, rediriger vers la page admin
    header('Location: admin.php');
    exit;
}
?>
