<?php
session_start();
if(!isset($_SESSION['patient_id'])){
    header("Location: login.php");
    exit();
}

$errorMessage = "";
$successMessage = "";
$medecinValue = "";
$dateValue = "";
$heureValue = "";
$motifValue = "";

require_once '../back-end/config/Connection.php';
require_once '../back-end/classes/Medecin.php';
require_once '../back-end/classes/RendezVous.php';

$connection = new Connection();
$connection->selectDatabase("gestion_rdv_medical");

$medecins = Medecin::selectAllMedecins("medecins", $connection->conn);

if(isset($_GET['medecin_id'])){
    $medecinValue = $_GET['medecin_id'];
}

if(isset($_POST["submit"])){
    $medecinValue = $_POST["medecin_id"];
    $dateValue = $_POST["date_rdv"];
    $heureValue = $_POST["heure_rdv"];
    $motifValue = $_POST["motif"];
    $patient_id = $_SESSION['patient_id'];

    if(empty($medecinValue) || empty($dateValue) || empty($heureValue)){
        $errorMessage = "Tous les champs obligatoires doivent être remplis!";
    } else {
        $rdv = new RendezVous($patient_id, $medecinValue, $dateValue, $heureValue, $motifValue);
        $rdv->insertRendezVous("rendez_vous", $connection->conn);
        
        $successMessage = RendezVous::$successMsg;
        $errorMessage = RendezVous::$errorMsg;
        
        if($successMessage){
            $medecinValue = "";
            $dateValue = "";
            $heureValue = "";
            $motifValue = "";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prendre Rendez-vous - RDV Médical</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <function_calls>
<invoke name="artifacts">
<parameter name="command">update</parameter>
<parameter name="id">frontend_files_medical</parameter>
<parameter name="old_str">                <ul class="navbar-nav ms-auto">
</parameter>
<parameter name="new_str">                <ul class="navbar-nav ms-auto">
<li class="nav-item">
<a class="nav-link" href="index.php">Accueil</a>
</li>
<li class="nav-item">
<a class="nav-link" href="medecins.php">Médecins</a>
</li>
<li class="nav-item">
<a class="nav-link" href="rendezvous.php">Mes RDV</a>
</li>
<li class="nav-item">
<a class="nav-link" href="logout.php">Déconnexion</a>
</li>
</ul>
</div>
</div>
</nav>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-calendar-plus text-primary"></i> Prendre un Rendez-vous
                    </h2>

                    <?php if(!empty($errorMessage)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $errorMessage; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($successMessage)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?php echo $successMessage; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label for="medecin_id" class="form-label">Choisir un médecin *</label>
                            <select class="form-select" id="medecin_id" name="medecin_id" required>
                                <option value="">-- Sélectionnez un médecin --</option>
                                <?php foreach($medecins as $medecin): ?>
                                    <option value="<?php echo $medecin['id']; ?>" 
                                            <?php echo ($medecinValue == $medecin['id']) ? 'selected' : ''; ?>>
                                        Dr. <?php echo $medecin['prenom'] . ' ' . $medecin['nom']; ?> 
                                        - <?php echo $medecin['specialite']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_rdv" class="form-label">Date du rendez-vous *</label>
                                <input type="date" class="form-control" id="date_rdv" name="date_rdv" 
                                       value="<?php echo $dateValue; ?>" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="heure_rdv" class="form-label">Heure du rendez-vous *</label>
                                <select class="form-select" id="heure_rdv" name="heure_rdv" required>
                                    <option value="">-- Sélectionnez une heure --</option>
                                    <option value="08:00">08:00</option>
                                    <option value="09:00">09:00</option>
                                    <option value="10:00">10:00</option>
                                    <option value="11:00">11:00</option>
                                    <option value="14:00">14:00</option>
                                    <option value="15:00">15:00</option>
                                    <option value="16:00">16:00</option>
                                    <option value="17:00">17:00</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="motif" class="form-label">Motif de la consultation</label>
                            <textarea class="form-control" id="motif" name="motif" rows="3" 
                                      placeholder="Décrivez brièvement le motif de votre consultation..."><?php echo $motifValue; ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check"></i> Confirmer le rendez-vous
                            </button>
                            <a href="medecins.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>