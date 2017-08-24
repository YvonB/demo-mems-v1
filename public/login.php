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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<style>
/*body*/
body
{
   /*body de google*/
    background-color: #fafafa !important;
    color: rgba(0,0,0,.987) !important;
    font-family: 'Roboto',sans-serif !important;
    font-size: 12px !important;
    font-weight: 400 !important;
    letter-spacing: .01em !important;
    line-height: 16px !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow: auto !important;

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
    margin-top: 14px !important;
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
	padding: 40px 16px;
	background-color: #fafafa;
	font: 400 16px/24px Roboto, sans-serif;
	color: #212121;
}
.okbtn
{
	float: right;
	margin-right: 15px;
}

.mark_ok
{
	background-color: yellow;
}

.mark_cancel
{
	background-color: #e74c3c;
}
.cancelbtn
{
	float: right;

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
      	<h3 align="center">SDP - IoT</h3>
    </div>
    <div class="modal-body">
      <p align="center">Une connexion à votre compte est requise pour voir le contenu de la page que vous avez demandée !</p>
      <p align="center">Cliuquer sur <mark class="mark_ok">"Ok"</mark> pour <mark class="mark_ok">accepter et continuer</mark> , sinon <mark class="mark_cancel">fermez</mark> cette fenêtre ou cliquez le bouton <mark class="mark_cancel">"Cancel" pour annuler</mark>. Merci !</p>
     
      <button type="button" onclick="document.location.href='javascript:history.back()'" class="btn btn-default cancelbtn">Cancel</button>
       <button type="button" id="ok_btn" class="btn btn-default okbtn">Ok</button>

    </div>
    <div class="modal-footer">
      <h4 align="center">© 2017, YvonB All rights reserved</h4>
    </div>
  </div>

</div>
<a href="<?php echo UserService::createLoginURL($_SERVER['REQUEST_URI']) ?>" id="lien"></a>
<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var ok = document.getElementById("ok_btn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
window.onload = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
    document.location.href='javascript:history.back()'; //page précédente
}

// When the user clicks anywhere outside of the modal, close it
ok.onclick = function() 
{
        modal.style.display = "none";
        document.getElementById('lien').click();
}

</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php   
}
// [END elseuser]


