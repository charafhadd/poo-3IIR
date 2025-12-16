<?php
session_start();
if(!isset($_SESSION['patient_id'])){
    header("Location: login.php");
    exit();
}

require_once '../back-end/config/Connection.php';
require_once '../back-end/classes/RendezVous.php';

$connection = new Connection();
$connection->selectDatabase("gestion_rdv_medical");

$rendezvous = RendezVous::selectRendezVousByPatient("rendez_vous", $connection->conn, $_SESSION['patient_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Rendez-vous - RDV Médical</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .rdv-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .rdv-card:hover {
            transform: translateX(5px);
        }
        .status-en_attente {
            border-color: #ffc107;
        }
        .status-confirme {
            border-color: #28a745;
        }
        .status-annule {
            border-color: #dc3545;
        }
        .status-termine {
            border-color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-heartbeat"></i> RDV Médical
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="medecins.php">Médecins</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="rendezvous.php">Mes RDV</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-calendar-alt text-primary"></i> Mes Rendez-vous</h1>
        <a href="medecins.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau RDV
        </a>
    </div>

    <?php if(empty($rendezvous)): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Vous n'avez aucun rendez-vous.
            <br>
            <a href="medecins.php" class="btn btn-primary mt-3">Prendre un rendez-vous</a>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach($rendezvous as $rdv): 
                $statusClass = "status-" . $rdv['statut'];
                $statusText = [
                    'en_attente' => 'En attente',
                    'confirme' => 'Confirmé',
                    'annule' => 'Annulé',
                    'termine' => 'Terminé'
                ];
                $statusBadge = [
                    'en_attente' => 'warning',
                    'confirme' => 'success',
                    'annule' => 'danger',
                    'termine' => 'secondary'
                ];
            ?>
                <div class="col-md-6">
                    <div class="card rdv-card <?php echo $statusClass; ?> shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user-md text-primary"></i>
                                    Dr. <?php echo $rdv['medecin_prenom'] . ' ' . $rdv['medecin_nom']; ?>
                                </h5>
                                <span class="badge bg-<?php echo $statusBadge[$rdv['statut']]; ?>">
                                    <?php echo $statusText[$rdv['statut']]; ?>
                                </span>
                            </div>
                            <p class="text-muted mb-2">
                                <small><?php echo $rdv['specialite']; ?></small>
                            </p>
                            <hr>
                            <p class="mb-2">
                                <i class="fas fa-calendar text-primary"></i>
                                <strong>Date:</strong> <?php echo date('d/m/Y', strtotime($rdv['date_rdv'])); ?>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-clock text-success"></i>
                                <strong>Heure:</strong> <?php echo date('H:i', strtotime($rdv['heure_rdv'])); ?>
                            </p>
                            <?php if($rdv['telephone']): ?>
                                <p class="mb-2">
                                    <i class="fas fa-phone text-info"></i>
                                    <strong>Tél:</strong> <?php echo $rdv['telephone']; ?>
                                </p>
                            <?php endif; ?>
                            <?php if($rdv['motif']): ?>
                                <p class="mb-2">
                                    <i class="fas fa-comment text-warning"></i>
                                    <strong>Motif:</strong> <?php echo $rdv['motif']; ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if($rdv['statut'] == 'en_attente' || $rdv['statut'] == 'confirme'): ?>
                                <hr>
                                <a href="../back-end/rendezvous/delete.php?id=<?php echo $rdv['id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous?');">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>