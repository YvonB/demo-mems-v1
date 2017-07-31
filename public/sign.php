<?php
/**
 * Enregistrer une entrée dans la BDD
 *
 * @author Yvon Benahita
 */

// Pour notre lib
require_once('../vendor/autoload.php');

// Pour que ce fichier connâit $_POST['nom'] etc
require_once 'main.php';

// Définitions des constantes modèles pour l'accès au datatore
define('GDS_ACCOUNT', ' !! your service account name here !! ');
define('GDS_KEY_FILE', dirname(__FILE__) . '/key.p12');
define('POST_LIMIT', 10);

// Récupération des entrées venant du formulaire
$nom = htmlspecialchars($_POST['nom']);
$mail = htmlspecialchars($_POST['mail']);
$mdp = sha1($_POST['mdp']);
$mdp2 = sha1($_POST['mdp2']);

if (!empty($_POST['nom']) AND !empty($_POST['mail']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])) 
{
	// on verifie que l'internaute entre una adresse mail valide
	if (filter_var($mail, FILTER_VALIDATE_EMAIL)) 
	{
		// On vérifie l'égalité des 2 mots de passe
		if ($mdp == $mdp2) 
		{
			echo "On continu";
		}
		else
		{
			?>
			<script type="text/javascript">
				alert('Vos mots de passe ne correspondent pas !');
			</script>
			<?php
		}
	}
	else
	{
		?>
		<script type="text/javascript">
			alert('Votre adresses mail n\'est pas valide !');
		</script>
		<?php
	}
}
else
{
	?>
	<script type="text/javascript">
		alert('Veuillez remplir tous les champs s\'il vous plaît !');
	</script>
	<?php
}

