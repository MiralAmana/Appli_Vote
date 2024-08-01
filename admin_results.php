<?php
include 'db.php';
session_start();

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

// Récupérer les résultats des votes
$results = $conn->query("
    SELECT candidates.name, COUNT(votes.id) as vote_count 
    FROM candidates 
    LEFT JOIN votes ON candidates.id = votes.candidate_id 
    GROUP BY candidates.id 
    ORDER BY vote_count DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Identifier le vainqueur
$winner = !empty($results) ? $results[0]['name'] : 'Aucun vote';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats du Vote</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        /* Navbar styling */
        .navbar {
            background-color: #6f42c1; /* Couleur violet pour la navbar */
        }
        .navbar-nav .nav-item + .nav-item {
            margin-left: 20px;
        }
        .navbar .nav-link {
            color: #fff;
        }
        .navbar .nav-link:hover {
            color: #e1e1e1;
        }

        /* Button styling */
        .btn-primary {
            background-color: #6f42c1; /* Couleur violet pour les boutons */
            border: none;
        }
        .btn-primary:hover {
            background-color: #5a2d91; /* Couleur violet plus foncé au survol */
        }

        /* Table styling */
        table.table-bordered {
            border: 2px solid #6f42c1; /* Bordure de la table en violet */
        }
        table.table-bordered th, table.table-bordered td {
            border-color: #6f42c1; /* Bordure des cellules en violet */
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Admin Pannel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
            <li class="nav-item">
        <a class="nav-link" href="admin.php">Admin</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Deconnex.php">Déconnexion</a>
      </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">Résultats du Vote</h2>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Candidat</th>
                    <th>Nombre de Votes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                <tr>
                    <td><?= htmlspecialchars($result['name']) ?></td>
                    <td><?= $result['vote_count'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="mt-5">Vainqueur: <strong><?= htmlspecialchars($winner) ?></strong></h3>

        <!-- Formulaire pour mettre le vainqueur en évidence -->
        <form action="admin_results.php" method="post">
            <input type="hidden" name="winner" value="<?= htmlspecialchars($winner) ?>">
            <button type="submit" class="btn btn-primary">Annoncer le Vainqueur</button>
        </form>

        <?php
        // Si le formulaire est soumis, mettre à jour la base de données avec le vainqueur
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['winner'])) {
            $winner = $_POST['winner'];
            
            // Assurez-vous que le vainqueur n'est pas "Aucun vote"
            if ($winner !== 'Aucun vote') {
                $stmt = $conn->prepare("UPDATE election_results SET winner = :winner WHERE id = 1");
                $stmt->bindParam(':winner', $winner, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Le vainqueur a été annoncé.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Erreur lors de l'annonce du vainqueur.</div>";
                }
            } else {
                echo "<div class='alert alert-warning'>Aucun vote n'a été enregistré.</div>";
            }
        }
        ?>
    </div>
</body>
</html>
