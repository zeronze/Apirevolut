
<?php
// Informations d'identification
$dsn = 'mysql:dbname=espace_membre;host=localhost';
$user = 'root';
$password = '';

try {
      $pdo = new PDO($dsn, $user, $password);
}
catch( PDOException $Exception ) {
    
    echo 'Échec lors de la connexion : '.$Exception->getMessage( );
}
/*Paramétrage de la liaison PHP <-> MySQL, les données sont encodées en UTF-8.*/
	$pdo->exec('SET NAMES UTF8');