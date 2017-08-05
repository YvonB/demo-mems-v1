<?php
/**
 * Enregistrer une entrée dans la BDD
 *
 * @author Yvon Benahita
 */
//On crée une session, pouvoir utiliser les sessions
session_start();

// Pour notre lib
require_once('../vendor/autoload.php');

// Pour que ce fichier connâit $_POST['nom'] etc
// require_once 'main.php';

// Définitions des constantes modèles pour l'accès au datatore
define('GDS_ACCOUNT', ' !! your service account name here !! ');
define('GDS_KEY_FILE', dirname(__FILE__) . '/key.p12');
define('POST_LIMIT', 10);

// Récupération des entrées venant du formulaire
$mail = htmlspecialchars($_POST['mailconnect']);
$mdp = sha1($_POST['passconnect']);

// On vérifie que les 02 champs ne sont pas vides
if (!empty($_POST['mailconnect']) AND !empty($_POST['passconnect'])) 
{
	// on verifie que l'internaute entre una adresse mail valide
	if (filter_var($mail, FILTER_VALIDATE_EMAIL)) 
	{	
		
		// Est-ce que ce mail existe dans la base?? ie la personne est-elle inscrite?

		$obj_member = new \GDS\Demo\Member(); // on crée une nouvelle instance de la classe Member

		$arr_mail = $obj_member->getAllMemberMail(); // pour pouvoir appeler cette methode
		
		/* on compare une à une chaque entrée avec ce que l'utilisateur a entreé comme mail*/
		while($arr_mail)
		{	
			if($mail == $arr_mail->mail)
			{	
				//nikel
				// On compare le mdp saisie avec celles de la base
				$arr_mdp = $obj_member->getAllMemberMdp();
				
				while($arr_mdp) 
				{
					if($mdp == $arr_mdp->mdp)
					{
						echo "nikel";
						exit;
					}
					else
					{
						// mot de passe incorrecte
						?>
							<script type="text/javascript">
								alert('Mot de passe incorrecte !');
							</script>
						<?php
						exit;
					}
				}
			}
			else
			{
				// utilisateur qui n'existe pas
					?>
						<script type="text/javascript">
							alert("Ce compte n'existe pas !");
						</script>
					<?php
					exit;
			}
		}

	}
	else
	{
		?>
			<script type="text/javascript">
				alert('Veuillez entrer une adresse mail valide !');
			</script>
		<?php
		exit;
	}
}
else
{
	?>
		<script type="text/javascript">
			alert('Veuillez remplir tous les champs s\'il vous plaît !');
		</script>
	<?php
	exit;
}

