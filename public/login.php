<?php
use google\appengine\api\users\User;
use google\appengine\api\users\UserService;
// [START user]
# Looks for current Google account session
$user = UserService::getCurrentUser();
// [END user]
// [START ifuser]
if ($user) 
{
    // echo 'Hello, ' . htmlspecialchars($user->getNickname());
    header('Location: /home');
}
// [END ifuser]
// [START elseuser]
else 
{	

	// header('Location: ' . UserService::createLoginURL($_SERVER['REQUEST_URI']));

	?>	
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <title>Login-SDP</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/*body*/
body
{
   /*body de google*/
    background-color: #fafafa;
    color: rgba(0,0,0,.987);
    font-family: 'Roboto',sans-serif;
    font-size: 12px;
    font-weight: 400;
    letter-spacing: .01em;
    line-height: 16px;
    margin: 0;
    padding: 0;
    overflow: auto;

}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.7); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
   /* border: 1px solid #888;*/
    width: 80%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.4s;
    animation-name: animatetop;
    animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
    from {top:-300px; opacity:0} 
    to {top:0; opacity:1}
}

@keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}

/* The Close Button */
.close {
    color: white;
    float: right;
    font-size: 28px;
    font-weight: bold;
    margin-top: 14px;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.modal-header {
    padding: 2px 16px;
   	background-color: #37424b;
    color: #c8c8c8;

}

.modal-body 
{
	padding: 2px 16px;
	background-color: #fafafa;
	font: 400 16px/24px Roboto, sans-serif;
	color: #212121;
}

.modal-footer {
    padding: 2px 16px;
    background-color: #37424b;
    color: #c8c8c8;
}
</style>
</head>
<body>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      	<h2 align="center">SDP - IoT</h2>
    </div>
    <div class="modal-body">
      <p align="center">Une connexion à votre compte est requise pour voir le contenu de la page que vous avez demandée !</p>
      <p align="center">Fermez cette fenêtre ou cliquez en dehors de cette fenêtre et vous serez redirigé à votre espace connexion. Merci !</p>
    </div>
    <div class="modal-footer">
      <h3 align="center">© 2017, YY, All rights reserved</h3>
    </div>
  </div>

</div>
<a href="<?php echo UserService::createLoginURL($_SERVER['REQUEST_URI']) ?>" id="lien"></a>
<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
// var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
window.onload = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        document.getElementById('lien').click()
    }
}

</script>


</body>
</html>

<?php   
}
// [END elseuser]


