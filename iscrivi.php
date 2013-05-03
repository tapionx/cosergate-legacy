<?php
session_start();
include_once '/var/www/cosergate.it/securimage/securimage.php';
$securimage = new Securimage();

if ((isset($_POST["captcha_code"])) && ($securimage->check($_POST['captcha_code']) == false)) {
	echo "Hai sbagliato ad inserire il codice. Riprova :)<br /><br />";
}
elseif ((isset($_POST["nome_ambiente"])) && (is_writable("/var/dati/cosergate/".$_POST['nome_ambiente'].".csv")))
	echo "Questo ambiente esiste gia', cambia il nome.";
elseif (isset($_POST["captcha_code"])) {
	$_POST["nome_ambiente"] = strtolower($_POST["nome_ambiente"]);
	$numero_utenti = count($_POST['nome_utente']);	
	$header = $numero_utenti.",";
	foreach ($_POST["nome_utente"] as $nome)
		$header .= $nome.",";
	foreach ($_POST["email_utente"] as $email)
		$header .= $email.",";
	$header .= $_POST["password"]."\n";
	// SCRIVE IL FILE
	$filename = "/var/dati/cosergate/".$_POST['nome_ambiente'].".csv";
    $output = "";
        if (!$handle = fopen($filename, 'w')) {
             $output .= "Impossibile aprire file";
             exit;
        }
        if (fwrite($handle, $header) === FALSE) {
            $output .= "Impossibile scrivere file $filename";
            exit;
        }
        fclose($handle);
        $output .= "Modifica completata :)";    
//    echo $output;
	// INVIA EMAIL
	$to = null;
	foreach ($_POST["email_utente"] as $email)
		$to  .= $email.",";
	$to .= "cosergate@tapion.it";	
    $headers = 'From: CoserGate <cosergate@tapion.it>' . "\r\n";
    $subject = '[CoserGate] iscrizione '.$_POST["nome_ambiente"];
	$nome_utente = $_POST["nome_ambiente"];    
	$password = $_POST["password"];	
	$message = "
Ciao!
Ho aperto il vostro Cosergate :)

lo trovate all'indirizzo

http://cosergate.it/

Per accedere clicca su 'ACCEDI' e inserisci queste credenziali:

nome utente: $nome_utente
password:    $password

E' ancora molto sperimentale ma funziona! Sarei contento se mi contattaste per suggerimenti, critiche e miglioramenti da fare.

Inoltre tengo a precisare che non vendero' i vostri dati personali a multinazionali assetate di sangue :)

Riccardo.  ";
mail($to, $subject, $message, $headers);
echo "<meta charset='utf-8' />Il tuo CoserGate è stato aperto con successo.<br/> Ti è stata inviata un'email con tutte le informazioni per accedere.";
}
