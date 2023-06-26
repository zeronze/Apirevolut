// Récupérer les valeurs des champs d'e-mail du formulaire
var mail = document.getElementById('mail').value;
var mail2 = document.getElementById('mail2').value;

// Vérifier si les e-mails correspondent
if (mail !== mail2) {
  var errorMessage = "L'adresse e-mail et la confirmation de l'adresse e-mail ne correspondent pas.";
  // Afficher le message d'erreur dans la page
  var errorElement = document.getElementById('error-message');
  errorElement.innerHTML = errorMessage;
  // Arrêter l'exécution du script
  return;
}

// Récupérer les valeurs des champs de mot de passe du formulaire
var mdp = document.getElementById('mdp').value;
var mdp2 = document.getElementById('mdp2').value;

// Vérifier si les mots de passe correspondent
if (mdp !== mdp2) {
  var errorMessage = "Le mot de passe et la confirmation du mot de passe ne correspondent pas.";
  // Afficher le message d'erreur dans la page
  var errorElement = document.getElementById('error-message');
  errorElement.innerHTML = errorMessage;
  // Arrêter l'exécution du script
  return;
}

// Récupérer la valeur du champ de pseudo du formulaire
var pseudo = document.getElementById('pseudo').value;

// Vérifier si le pseudo contient des chiffres ou des caractères spéciaux
if (/\d/.test(pseudo) || /[^\w\s]/.test(pseudo)) {
  var errorMessage = "Le pseudo ne doit contenir que des lettres.";
  // Afficher le message d'erreur dans la page
  var errorElement = document.getElementById('error-message');
  errorElement.innerHTML = errorMessage;
  // Arrêter l'exécution du script
  return;
}

// Vérifier la taille du pseudo
if (pseudo.length > 10) {
  var errorMessage = "La taille du pseudo ne doit pas dépasser 10 caractères.";
  // Afficher le message d'erreur dans la page
  var errorElement = document.getElementById('error-message');
  errorElement.innerHTML = errorMessage;
  // Arrêter l'exécution du script
  return;
}



