<?php

require_once("funzioni.php");

// HTML -------------------------------------------------------------------------------------------
//if (!isset($_SERVER['PHP_AUTH_USER'])) authentication();
//elseif (!check_user($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])) authentication();

session_start();
if(!isset($_SESSION['loggato']) || $_SESSION['loggato'] != true){
        if($_POST['username'] && $_POST['password']) {
                if(check_user($_POST['username'], $_POST['password'])){
                        $_SESSION['loggato'] = true;
                        $_SESSION['username'] = $_POST['username'];
                } else {
			//echo 'Autenticazione fallita';
                        header("Location: ..");
			die();
                }
        } else {
		//echo 'Necessaria autenticazione';
        	header("Location: ..");
		die();
        }
}
if(isset($_POST['logout'])){
        session_unset();
        session_destroy();
	//echo 'logout effettuato';
	header("Location: ..");
	die();
}
$_SERVER['PHP_AUTH_USER'] = $_SESSION['username'];
//my_debug();
?>
<?php echo file_get_contents("header.php");?>
	<div id="wrap">
		<div id="sidebar">
			<div class="riquadro-sidebar" id="messaggi">
				<?php
					$db = load_data(); 
					echo controlli_messaggi($db);
					$db = load_data(); // Carico di nuovo il file perchè prima è stato modificato forse
				?>
			</div>
			<div class="riquadro-sidebar">
                <legend>Clicca qui per uscire</legend>
				<form method="post" action="">
					<input type="submit" name="logout" value="Logout" />
				</form>
			</div>
			<div class="riquadro-sidebar">
				<?php echo info_utenti();?>
			</div>
			<div class="riquadro-sidebar">
				<?php echo statistiche($db); ?>
			</div>
			<div class="riquadro-sidebar">
				<legend>Help!</legend>
				<p>Non funziona?<br />
				Critiche?<br />
				Suggerimenti?</p>
				<img src="img/email_supporto.png" height="20" weight="30">
			</div>
		</div>
		<div id="main">
			<?php if (isset($_POST['modifica'])) echo form_modifica($db);?> 
			<?php if (isset($_POST['modifica_utenti'])) echo form_modifica_utenti();?> 
			<?php if (isset($_POST['aggiungi_riparatore_form'])) echo aggiungi_riparatore_form($db); ?>
			<?php echo tabella_resoconto($db,0); ?>
			<form action="" class="blank" method="post">
				<table>
					<tr>    
					<td><input type="submit" name="inserisci" value="Inserisci"></td>
					<td><input onClick="return confirmSubmit()" name="cancella" type="submit" value="Elimina"></td>
					<td><input name="modifica" type="submit" value="Modifica"></td>
					<td><input id="email" value="1" name="parte" type="image" src="img/mail.gif" height="25" weight="30"></td>
					</tr>
					<table class="tabella">
					<tr>
					<th></th>
					<th>#</th>
					<?php 
					$users = load_users();
					echo "
					<th>Chi spende</td/>";
					for($i=1;$i<=$users[0];$i++)
					{
					echo "<th>".$users[$i]."</th>";
					}
					?>
					<th>Descrizione</td>
					<th>Importo</td>
					<th>Data</td/>
					</tr>
					<?php 
					echo form_inserimento($db);
					$lista = lista_db(array_reverse($db),"img");
					if($lista === NULL) echo "<h3>Chi inserirà la prima spesa?</h3>";
					else echo $lista;
					?>
				</table>
			</form>
		</div>
	</div>
<?php echo file_get_contents("footer.php");?>
