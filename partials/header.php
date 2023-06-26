<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Super Blog ECF3 AFPA</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap’s CSS and JS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <!-- google font -->
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  <!-- favicon -->
  <link rel="icon" href="../app/assets/img/favicon.ico">
  <!-- mon css -->
  <link rel="stylesheet" type="text/css" href="../app/assets/css/global.css">
  <!-- icons8 -->
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg   navbar navbar-dark bg-primary">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="../public/accueil.php">Accueil<span class="sr-only">(current)</span></a>
      </li>
      <li><a class="nav-link" href="../public/panier.php">Panier</a></li>

      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>

    </ul>
    <ul class="navbar-nav ">
      <?php if (isset($_SESSION['user_id'])) : ?>
        <?php if (isset($_SESSION['avatar'])) : ?>
          <li class="nav-item">
            <img src="data:image/jpeg;base64,<?= $_SESSION['avatar'] ?>" alt="avatar">
          </li>
        <?php else : ?>
 
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="../public/profil.php"><i class="las la-sign-out-alt"></i>Profil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../partials/deconnexion.php"><i class="las la-sign-out-alt"></i> Déconnexion</a>
        </li>
      <?php else : ?>
        <li class="nav-item">
          <a class="nav-link" href="../partials/inscription.php">S'inscrire</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../partials/connexion.php"><i class="las la-sign-in-alt"></i> Connexion</a>
        </li>
      <?php endif; ?>
    </ul>

  </div>
</nav>
</body>
</html>
