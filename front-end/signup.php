<?php
session_start();
$nomValue = "";
$prenomValue = "";
$emailValue = "";
$telephoneValue = "";
$dateNaissanceValue = "";
$adresseValue = "";
$errorMessage = "";
$successMessage = "";

if(isset($_POST["submit"])){
    $nomValue = $_POST["nom"];
    $prenomValue = $_POST["prenom"];
    $emailValue = $_POST["email"];
    $telephoneValue = $_POST["telephone"];
    $dateNaissanceValue = $_POST["date_naissance"];
    $adresseValue = $_POST["adresse"];
    $passwordValue = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if(empty($nomValue) || empty($prenomValue) || empty($emailValue) || empty($passwordValue)){
        $errorMessage = "Tous les champs obligatoires doivent être remplis!";
    } else if(strlen($passwordValue) < 8){
        $errorMessage = "Le mot de passe doit contenir au moins 8 caractères";
    } else if($passwordValue !== $confirmPassword){
        $errorMessage = "Les mots de passe ne correspondent pas";
    } else {
        require_once '../back-end/config/Connection.php';
        require_once '../back-end/classes/Patient.php';
        
        $connection = new Connection();
        $connection->selectDatabase("gestion_rdv_medical");
        
        $patient = new Patient($nomValue, $prenomValue, $emailValue, $passwordValue, $telephoneValue, $dateNaissanceValue, $adresseValue);
        $patient->insertPatient("patients", $connection->conn);
        
        $successMessage = Patient::$successMsg;
        $errorMessage = Patient::$errorMsg;
        
        if($successMessage){
            $nomValue = "";
            $prenomValue = "";
            $emailValue = "";
            $telephoneValue = "";
            $dateNaissanceValue = "";
            $adresseValue = "";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - RDV Médical</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .signup-container {
            margin-top: 50px;
            margin-bottom: 50px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container signup-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-plus fa-3x text-primary"></i>
                            <h2 class="mt-3">Créer un compte</h2>
                            <p class="text-muted">Rejoignez-nous pour prendre vos rendez-vous</p>
                        </div>

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
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $nomValue; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="prenom" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $prenomValue; ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $emailValue; ?>" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo $telephoneValue; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_naissance" class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?php echo $dateNaissanceValue; ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <textarea class="form-control" id="adresse" name="adresse" rows="2"><?php echo $adresseValue; ?></textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Mot de passe *</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <small class="text-muted">Au moins 8 caractères</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="confirm_password" class="form-label">Confirmer mot de passe *</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" name="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus"></i> S'inscrire
                                </button>
                            </div>

                            <div class="text-center">
                                <p>Vous avez déjà un compte? <a href="login.php">Se connecter</a></p>
                                <a href="index.php" class="text-muted">
                                    <i class="fas fa-home"></i> Retour à l'accueil
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