<?php

// AUTHENTICATION --------------------------------------------------------------
// Output the login prompt

function authentication ()
{
	header('WWW-Authenticate: Basic realm="Dite amici ed entrate."');
	header('HTTP/1.0 401 Unauthorized');
	$output = "<pre>
	It truly makes the most beautiful music,
	everything it has to give...
	It's everywhere hiding the listener..
	Without it I could not live...

	... silence. </pre>";
	echo("<h1>Tu non puoi passareeeeeeeee!</h1><img src='img/gandalf_vs_balrog.jpg' height='500'>");
	exit;
}

// CHECK USER ------------------------------------------------------------------
// Check if USERNAME and PASSWORD are correct and returns TRUE or FALSE

function check_user($user, $password)
{
	$path = "/var/dati/cosergate/".$user.".csv";
	clearstatcache();
	if (is_writable($path))
	{
		$db = file ($path, FILE_IGNORE_NEW_LINES);
		$head = array_pop(array_reverse($db));
		$head = explode(",",$head);
		$password_db = 	$head[(($head[0] * 2) + 1)];
		if ($password == $password_db)
			return true;   	
	}
	else return false;	
}

// LOAD USERS
// load the header of db and fetch the numbers and the names of users
// Numbers of users,(Names of users),(emails of users),address

function load_users()
{
    $header = load_header();
    $header = str_getcsv($header);
    return $header;
}

// LOAD FILE NAME -------------------------------------------------------------
// Read $_SERVER["REQUEST_URI"] and build the complete uri of the db.

function load_file_name ()
{
	/*    
	$uri = $_SERVER["REQUEST_URI"];
    $uri = explode("/", $uri);
    $uri = $uri[2];
    // DA QUA PARTIRE PER ARRIVARE A /percorso/dei/db/($uri).db
    $uri = "/var/dati/cosergate/".$uri.".csv";
	*/    
	return "/var/dati/cosergate/".$_SERVER['PHP_AUTH_USER'].".csv";
}

// LOAD DB ------------------------------------------------------------------
// Choose the file, load the db and returns it in array.

function load_db()
{
    $uri = load_file_name();
    $db = file ($uri, FILE_IGNORE_NEW_LINES); // Inizializza il file
    return $db;
}

// LOAD HEADER
// Get the db, returns the first line, witch is the header and contains
// info about the environment (address, number of users, names of users, email
// of users)

function load_header()
{
    $db = load_db();
    return array_pop(array_reverse($db));   
}

// LOAD DATA
// Get the db, pop the header and returns the real data, the table of entries.

function load_data()
{
    $db = load_db();
    $db = array_reverse($db);
    array_pop($db);
    $db = array_reverse($db);
    return $db;
}

// MY DEBUG ------------------------------------------------------------------
// print all the $_POST for debugging

function my_debug()
{
    print("<pre>");
    print_r($_POST);
    print("</pre>");
}

// MODIFICA SPESA --------------------------------------------------------------
// modifica le righe del db che sono state modificate nella funzione 

function modifica_spesa($db)
{
    $users = load_users();
    $db_new = load_header()."\n";
    $output = "";
    foreach ($db as $entry) 
    {
        $linea = explode ('|', $entry);
        $numero_entry = $linea[0];
        $chi_spende = $linea[1];
        for($i=1;$i<=$users[0];$i++)
        {
            $x[$i] = $linea[1+$i]; 
        }
        $descrizione = $linea[$i+1];
        $importo = $linea[$i+2];
        $data = $linea[$i+3];
        $riparatore = $linea[$i+4]; 
        if(isset($_POST["chi_spende$numero_entry"]))
        {
            $chi_spende = $_POST["chi_spende$numero_entry"];
            for($i=1;$i<=$users[0];$i++)
            {
                $stringa = 'x'.$i.'-'.$numero_entry;
                if (isset($_POST['x'.$i.'-'.$numero_entry])) {
                    $x[$i] = $_POST['x'.$i.'-'.$numero_entry];
                    }    
            }
            $descrizione = htmlspecialchars($_POST["descrizione$numero_entry"]);
            $importo = htmlspecialchars($_POST["importo$numero_entry"]);
            $importo = str_replace(",",".",$importo);
            $data = htmlspecialchars($_POST["data$numero_entry"]);
            $form = "$numero_entry|$chi_spende|";
            for($i=1;$i<=$users[0];$i++)
            {
                $form .= $x[$i]."|";
            }
            $form .= "$descrizione|$importo|$data|$riparatore\n";
            $somma = 0;
            for($i=1;$i<=$users[0];$i++)
                $somma = $somma + $x[$i];
            //if (($somma) == 0) {
            //    $output = 'Per chi è la spesa?';
            //}
            //else 
            if ($descrizione == NULL) {
	            $output = 'Manca la descrizione';
            }
            else if ($importo == NULL) {
                $output = "Manca l'importo";
            }
            else if (!is_numeric($importo)) {
                $output = "L'importo non è numerico";
            }
            else if ($data == NULL) {
                $output = 'Devi inserire una data';
            }
            else $db_new .= $form;
        }
        else $db_new .= $entry."\n";   
    }
    if ($output == "") return scrivi_file($db_new,'w'); 
    else return $output;
}

// FORM_MODIFICA --------------------------------------------------------------
// visualizza il form per modificare le belle spesette

function form_modifica ($db)
{
    $users = load_users();
    $selezionato_almeno_una = 0;
    $output = '
        <legend>Modifica le spese</legend>
        <form action="" class="blank" method="post">
        <input type="hidden" name="damodificare" value="1">
        <table class="tabella">
            <tr>
	            <th>Chi spende</th>';
	            for($i=1;$i<=$users[0];$i++)
	                $output .= '<th>'.$users[$i].'</th>';
	            $output .= '
	            <th>Descrizione</th>
	            <th>Importo</th>
	            <th>Data</th>
            </tr>';
    foreach ($db as $linea) 
    {
        $linea = explode ('|', $linea);
        $numero_entry = $linea[0];
        $chi_spende = $linea[1];
        for($i=1;$i<=$users[0];$i++)
        {
            $x[$i] = $linea[1+$i]; 
        }
        $descrizione = $linea[$i+1];
        $importo = $linea[$i+2];
        $data = $linea[$i+3];
        $riparatore = $linea[$i+4]; 
        if (isset($_POST['modifica']) && (isset($_POST[$numero_entry])))
        {
            $selezionato_almeno_una = 1;
            $output .= '
            <tr>
                <td>
		            <select name="chi_spende'.$numero_entry.'">';
		            for($i=1;$i<=$users[0];$i++)
		            {
		                $output .= '<option value="'.$i.'"'; if($chi_spende==$i)$output.="selected"; $output .='>'.$users[$i].'</option>';
		            }
		        $output .= '</td>';
		        for($i=1;$i<=$users[0];$i++)
		        {
		            $output .= '
	                <td>
	                    <input type="hidden" name="x'.$i.'-'.$numero_entry.'" value="0">
	                    <input type="checkbox" name="x'.$i.'-'.$numero_entry.'"'; if($x[$i])$output.="checked"; $output .=' value="1">
	                </td>';
	            }
	            $output .= '
	            <td><input type="text" name="descrizione'.$numero_entry.'" maxlength="70" value="'.$descrizione.'"></td>
	            <td><input type="text" name="importo'.$numero_entry.'" maxlength="8" size="5" value="'.$importo.'"></td>
	            <td><input type="text" name="data'.$numero_entry.'" maxlength="8" size="5"value="'.$data.'"></td>
            </tr>
            ';
        }
    }
    $output .= '
    </table>
    <input type="submit" value="Modifica le spese">
    </form>
    ';
    if(!$selezionato_almeno_una) return "<h3>Devi selezionare almeno una spesa da modificare!</h3>";
    else return $output;
}

// FORM MODIFICA UTENTI
// appare il form per modificare la prima riga del db con gli utenti

function form_modifica_utenti()
{
    $users = load_users();
    $output = '
        <div class="riquadro-sidebar">
        <legend>Modifica gli utenti</legend>
        <form action="" class="blank" method="post">
        <table class="tabella-nuda">
            <tr>
                <th>Nome utente</th>
                <th>Email</th>
            </tr>';
        for($i=1;$i<=$users[0];$i++)
        {
            $nome_old = $users[$i];
            $email_old = $users[$users[0]+$i];
            $output .= "<tr>
                            <td><input type='text' name='nome_$i' maxlength='15' value='$nome_old'></td>
                            <td><input type='text' name='email_$i' maxlength='20' value='$email_old'></td>
                        </tr>";
        }
    $output .= <<<DH
<tr>
	<td colspanning='2'>Cambio password</td>
</tr>
<tr>
	<td>Nuova Password</td>
	<td><input type='password' name='form-utenti-password' maxlength='20'></td>
</tr>
<tr>
	<td><legend>Cambia sfondo:</legend></td>
	<td><input size="6" class="color" onchange="document.getElementsByTagName('HTML')[0].style.backgroundColor = '#'+this.color"></td>
</tr>
</table>
<input type="submit" name="damodificare_utenti" value="Modifica utenti">
</form>
</div>
DH;
    return $output;
}

// MODIFICA UTENTI -------------------------------------------------------------
// modifica la prima riga del db 

function modifica_utenti($db)
{
    $users = load_users();
    $header = $users[0];
    for($i=1;$i<=$users[0];$i++)
    {
        $header .= ",".htmlspecialchars($_POST["nome_".$i]); 
    }
    for($i=1;$i<=$users[0];$i++)
    {
        $header .= ",".htmlspecialchars($_POST["email_".$i]); 
    }
    if((isset($_POST['form-utenti-password'])) && ($_POST['form-utenti-password'] != '')) 
	$header .= ",".$_POST['form-utenti-password']."\n";
    else 
	$header .= ",".$users[(($users[0] * 2) + 1)]."\n";
    $db_new = $header;
    foreach($db as $linea)
    {
        $db_new .= $linea."\n";
    }
    return scrivi_file($db_new,'w');
}

// SCRIVI_FILE  ----------------------------------------------------------------
// aggiunge una riga o sostituisce db.csv

function scrivi_file($somecontent,$modo) 
{
    $filename = load_file_name();
    $output = "";
    if (is_writable($filename)) {
        if (!$handle = fopen($filename, $modo)) {
             $output .= "Impossibile aprire file";
             exit;
        }
        if (fwrite($handle, $somecontent) === FALSE) {
            $output .= "Impossibile scrivere file";
            exit;
        }
        fclose($handle);
        $output .= "Modifica completata :)";
        //$output .= '<meta http-equiv="refresh" content="2;URL=">';
    }
    else $output .= "File non scrivibile";
    return $output;
}

// SOMMA SPESA ----------------------------------------------------------------
// restituisce il totale speso da $nome o da tutti, anche solo i non saldati

function somma_spesa($db,$nome)
{
    $users = load_users();
	$somma = 0;
	foreach ($db as $linea) 
	{
        $linea = explode ('|', $linea);
        $chi_spende = $linea[1];
        $importo = $linea[($users[0])+3];
        $riparatore = $linea[$users[0]+5];
        //list ($a, $chi_spende, $a, $a, $a, $a, $a, $importo, $a, $riparatore) = explode ('|', $linea);
		if ((($nome == $chi_spende) || ($nome == "tutti")) && ($riparatore == 0)) 
		    $somma = $somma + $importo;
	}	
	return $somma;
}

// CANCELLA ---------------------------------------------------------------------------------
// cancella definitivamente una riga da db.csv

function cancella($db)
{
    $selezionata_almeno_una = 0;
    $db_new = load_header()."\n";
    foreach ($db as $linea) {
        $l = explode ('|', $linea);
        $numero_entry = $l[0];
        if (isset($_POST['cancella']) && (isset($_POST[$numero_entry]))){
			$selezionata_almeno_una = 1;
			; // Un semplice punto e virgola che cancella una riga! :)
		}
		else $db_new .= $linea."\n";
	}
	if($selezionata_almeno_una == 1) return scrivi_file($db_new,'w');
	else return "Non hai selezionato la spesa da cancellare!";
}

// ESTRAI NUMERO ENTRY -------------------------------------------------------
// Calcola il numero della riga da inserire

function estrai_numero_entry ($db)
{
    if ($db == NULL)  $numero_entry = 1; 
    else 
    {
        $linea = explode ('|', array_pop($db));
        $numero_entry = $linea[0] + 1;
    }
    return $numero_entry;
}

// AGGIUNGI RIPARATORE FORM
// Chiede quanto c'è da saldare! :)

function aggiungi_riparatore_form ($db)
{
    $p1 = $_POST["salda1"];
    $p2 = $_POST["salda2"];	
	$importo = round((calcola_rapporto($db,$p1,$p2) - calcola_rapporto($db,$p2,$p1)),2);
	$output = '	
	<div class="riquadro-sidebar">
		<p>Quanto viene saldato?</p>
		<form method="post" action="">
			<input type="text" name="importo_da_saldare" value="'.round(abs($importo)).'" size="4">
			<input type="submit" onClick="return confirmSubmit2()" name="aggiungi_riparatore" value="Conferma">
			<input type="hidden" name="salda1" value="'.$_POST["salda1"].'">
			<input type="hidden" name="salda2" value="'.$_POST["salda2"].'">
		</form>
	</div> ';
	return $output;	
}

// AGGIUNGI RIPARATORE ---------------------------------------------------------
// Inserisce una riga di saldo nel db
//$numero_entry|$chi_spende|$x1|$x2|$x3|$x4|$descrizione|$importo|$data|$riparatore

function aggiungi_riparatore($db) 
{
    $users = load_users();
    $p1 = $_POST["salda1"];
    $p2 = $_POST["salda2"];
    $numero_entry = estrai_numero_entry ($db); 
    $importo2 = round((calcola_rapporto($db,$p1,$p2) - calcola_rapporto($db,$p2,$p1)),2);
    $importo = $_POST["importo_da_saldare"];
    if ($importo2 < 0)
    {
        $chi_spende = $p1;
        $chi_riceve = $p2;
    }
    else
    {
        $chi_spende = $p2;
        $chi_riceve = $p1;    
    }
    for($i=1;$i<=$users[0];$i++)
    {
        if($i==$chi_riceve) $x[$i] = 1;
        else $x[$i] = 0;
    }
    $descrizione = "SALDA DEBITO CON ".strtoupper($users[$chi_riceve]);
    $importo = abs($importo);
    $data = date("d/m/y");
    $form = "$numero_entry|$chi_spende|";
    for($i=1;$i<=$users[0];$i++)
    {
        $form .= $x[$i]."|";
    }
    $form .= "$descrizione|$importo|$data|1\n";
    return scrivi_file($form,'a'); 
}

// INSERISCI ---------------------------------------------------------------------------------
// Inserisce una riga in db.csv
//$numero_entry|$chi_spende|$x1|$x2|$x3|$x4|$descrizione|$importo|$data|$riparatore

function inserisci($db) 
{
    $users = load_users();
    $somma = NULL;
	$numero_entry = estrai_numero_entry ($db); 
    $chi_spende = $_POST['chi_spende'];
    for($i=1;$i<=$users[0];$i++)
    {
        $x[$i] = $_POST['x'.$i];
    }
    $descrizione = htmlspecialchars($_POST['descrizione']);
    $importo = htmlspecialchars($_POST['importo']);
    $importo = str_replace(",",".",$importo);
    $data = htmlspecialchars($_POST['data']);
    //Metto la variabile globale a 1, poi se arrivo alla fine torna a zero.
    $_POST["inserimento_errato"] = 1;
    for($i=1;$i<=$users[0];$i++)
        $somma = $somma + $x[$i];
    if (($somma) == 0) 
        return 'Per chi è questa spesa?';
    if ($descrizione == NULL) 
        return 'Manca la descrizione';
    if ($importo == NULL) 
        return "Manca l' importo";
    if (!is_numeric($importo)) 
        return "L'importo non è numerico";
    if ($data == NULL) 
        return 'Manca la data';
    // qui ci finiamo se tutto va bene, quindi azzero la variabile globale
    $_POST["inserimento_errato"] = 0;
    $form = "$numero_entry|$chi_spende|";
    for($i=1;$i<=$users[0];$i++)
    {
        $form .= "$x[$i]|";
    }
    $form .= "$descrizione|$importo|$data|0\n";
    return scrivi_file($form,'a'); 
}

// EMAIL -------------------------------------------------------------------------------------------------------
// invia per email il resoconto

function invia_email($db) 
{  
    $users = load_users();
    $to = NULL;
    for($i=1;$i<=$users[0];$i++)
    {
        $to  .= $users[$users[0]+$i];
        if ($i<$users[0]) 
            $to .= ",";
    }
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'To:'.$to."\r\n";
    $headers .= 'From: CoserGate <cosergate@tapion.it>' . "\r\n";
    $subject = '[CoserGate] Resoconto del '.date("d/m/y").'';
    $message = '
    <html>
    <head>
        <title>Resoconto del CoserGate</title>
        <style type="text/css">
            table {
                border-collapse: collapse;
            }
            td {
                border: 1px solid black;
                padding: 5px;
            }
            th {
                border: 1px solid black;
                padding: 5px;
            }
        </style>
    </head>
    <body>
    '.tabella_resoconto($db,1).'<br><br>
        <table>
            <tr>
                <th>#</th>
                <th>Chi ha speso</td/>';
                for($i=1;$i<=$users[0];$i++)
                    $message .= '<th>'.$users[$i].'</td/>';
                $message .= '
                <th>Descrizione</td>
                <th>Importo</td>
                <th>Data</td/>
            </tr>
      '.lista_db($db,'txt').'
    </table>
    </body>
    </html>
    ';
    mail($to, $subject, $message, $headers);
    return "E-mail inviata con successo.";
} 

// CALCOLA RAPPORTO -------------------------------------------------------------------------------------------
// restituisce quanto $persona1 deve dare a $persona2
// SE E' NEGATIVO -> DARE A

function calcola_rapporto ($db,$persona1,$persona2)
{
    $rapporto = 0;
    $users = load_users();
    foreach ($db as $linea)
    {
        $n_utenti_segnati = 0;
        $linea_esplosa = explode("|",$linea);
        $chi_spende = $linea_esplosa[1];
        $importo = $linea_esplosa[($users[0]+3)];
        for($i=1;$i<=$users[0];$i++)
        {
            $n_utenti_segnati = $n_utenti_segnati + $linea_esplosa[$i+1];
        }
        if (($chi_spende == $persona1) && ($linea_esplosa[$persona2+1] == 1))
        {
            $rapporto = $rapporto + ($importo / $n_utenti_segnati);
        } 
    }
    return $rapporto;
}

// STRINGA TABELLA -------------------------------------------------------------
// restituisce la scringa da stampare nella tabella di resoconto

function stringa_tabella($db,$persona1,$persona2,$mail)
{
    $u = load_users();
    $soldi = round(( calcola_rapporto($db,$persona1,$persona2) - calcola_rapporto($db,$persona2,$persona1) ),2);
    $output = "";
    if ($soldi == 0) 
        $output .= "<img src='img/si.png' height='15' width='15'>";
    else 
    {
        if (!$mail) $output .= '
            <form name="aggiungi_riparatore_form" method="post" action="">
            	<input type="hidden" name="aggiungi_riparatore_form">
                <input type="submit" name="salda" value="$">
                <input type="hidden" name="salda1" value="'.$persona1.'">
                <input type="hidden" name="salda2" value="'.$persona2.'">
            ';
        if ($soldi < 0) 
            $output .= "Dare a ";
        if ($soldi > 0) 
            $output .= "Avere da ";
        $output .= $u[$persona2].' '.abs($soldi). ' &euro;';
        if (!$mail) $output .= "</form>";        
    }
    return $output;
}

// RIEPILOGO TABELLA ----------------------------------------------------------
// Stampa il contenuto della cella di totale nella tabella di resoconto
// cioè se uno sta sopra o sta sotto e di quanto.

function riepilogo_tabella ($db,$p)
{
    $users = load_users();
    $soldi = NULL;
    $output = "";
    for($i=1;$i<=$users[0];$i++)
    {
        $soldi = $soldi + round(( calcola_rapporto($db,$p,$i) - calcola_rapporto($db,$i,$p)),2);
    }
    if ($soldi > 0)
        $output .= 'Totale:   <span id=green>+'.round($soldi,2).' &euro;</span>';
    if ($soldi < 0)
        $output .= 'Totale:   <span id=red>'.round($soldi,2).' &euro;</span>';
    if ($soldi == 0)
        $output = "<img src='img/si.png' height='15' width='15'>";
    return $output;
}

// TABELLA RESOCONTO ----------------------------------------------------------
// Stampa la tabella di resoconto

function tabella_resoconto ($db,$mail) 
{
    $users = load_users();
	$ret = '
	<table class="tabella">
	    <tr>';
	        for($i=1;$i<=$users[0];$i++)
	        {
	            $ret .= '<th>'.$users[$i].'</th>';
	        }
	    $ret .= '
		</tr>';
		for($i=1;$i<=($users[0])-1;$i++)
		{
		    $ret .= '<tr class="tabella-res">';
			    for($j=1;$j<=$users[0];$j++)
			    {
			        if($i>=$j) $ret .= '<td>'.stringa_tabella($db,$j,($i+1),$mail).'</td>';
			        else 
			            $ret .= '<td>'.stringa_tabella($db,$j,$i,$mail).'</td>';
			    }
		    $ret .= '</tr>';
		}
	    $ret .= '<tr>';
	    for($i=1;$i<=$users[0];$i++)
	    {
	        $ret .= '<td class="riepilogo">'.riepilogo_tabella($db,$i).'</td>';
	    }
	    $ret .= '
	    </tr>
    </table>';
return $ret;
}

// FORM INSERIMENTO --------------------------------------------------------------------------------------------------
// Spara l'HTML del form. Vanno inseriti i controlli sull'input in javascript! (ma anche no?)

function form_inserimento($db) 
{
    $users = load_users();
    $spende = NULL;
    $x = NULL;
    $descrizione = "";
    $importo = "";
    $numero_utenti = $users [0];
    if((isset($_POST["inserimento_errato"])) && ($_POST["inserimento_errato"] == 0)) $_POST = NULL;
    if(isset($_POST["data"])) $data = $_POST["data"];
    else $data = date("d/m/y");
    if((isset($_POST["chi_spende"])))
        for($i=1;$i<=$numero_utenti;$i++)
	{
       	    if ($_POST["chi_spende"] == $i) $spende[$i] = "selected";
	    else $spende[$i] = null;
	}
    for($i=1;$i<=$numero_utenti;$i++)
    {
        if((isset($_POST["x".$i])) && ($_POST["x".$i] == 1)) $x[$i] = "checked";
	else $x[$i] = null;
    }
    if(isset($_POST["descrizione"])) $descrizione = $_POST["descrizione"];
    if(isset($_POST["importo"])) $importo = $_POST["importo"];
    $output = '
    <tr>
        <td></td>
        <td>'.estrai_numero_entry ($db).'</td>
        <td>
            <select name="chi_spende">';
            for($i=1;$i<=$numero_utenti;$i++)
                $output .= '<option value="'.$i.'"'.$spende[$i].'>'.$users[$i].'</option>';
        $output .= '</td>';
        for($i=1; $i<=$numero_utenti; $i++)
        {
            $output .= '
            <td>
                <input type="hidden" name="x'.$i.'" value="0">
                <input type="checkbox" name="x'.$i.'"'.$x[$i].' value="1">
            </td>';
        }
        $output .= '
        <td><input type="text" id="form-descrizione" name="descrizione" maxlength="70" value="'.$descrizione.'"></td>
        <td><input type="text" id="form-importo" name="importo" maxlength="7" size="2" value="'.$importo.'">&euro;</td>
        <td><input type="text" id="form-data" name="data" maxlength="8" size="5"value="'.$data.'"></td>

    <input type="hidden" name="inserimento_errato" value="0">
    </tr>';
    return $output;
}

// MARCATORE -------------------------------------------------------------------
// Restituisce una adeguata fighetteria in Lista DB

function marcatore($bool,$img)
{
    if ($img == 'img') 
        $marcatore = '<img src="img/si.png" height="15" width="15" alt="selezionato">';
        //$marcatore = "si";  
    if ($img == 'txt') 
        $marcatore = 'SI';
    if ($bool) 
        return $marcatore;
    else
        return NULL;
}

// LISTA DB --------------------------------------------------------------------
// Spara l'html con la lista delle spese non ancora saldate.

function lista_db($db,$tipo_marcatore) 
{
    $users = load_users();
    $ret = "";
    if ($db == NULL) return NULL;
    foreach ($db as $entry) 
    {
        $linea = explode ('|', $entry);
        $numero_entry = $linea[0];
        $chi_spende = $linea[1];
        for($i=1;$i<=$users[0];$i++)
        {
            $x[$i] = $linea[1+$i]; 
        }
        $descrizione = $linea[$i+1];
        $importo = $linea[$i+2];
        $data = $linea[$i+3];
        $riparatore = $linea[$i+4]; 
        //list ($numero_entry, $chi_spende, $x1, $x2, $x3, $x4, $descrizione, $importo, $data, $riparatore) = explode ('|', $entry);
	    $ret .= "<tr>";
	    if ($tipo_marcatore == 'img') $ret .= '<td><input type="checkbox" name="'.$numero_entry.'" value="'.$numero_entry.'"></td>';
	    $ret .= "
	        <td>".$numero_entry."</td>
	        <td>".$users[$chi_spende]."</td>";
	        for($i=1;$i<=$users[0];$i++)
	        {
	            $ret .= "<td>".marcatore($x[$i],$tipo_marcatore)."</td>";
	        }
	        $ret .= "
	        <td class='colonna-descrizione'>".$descrizione."</td>
	        <td>".$importo."&euro;</td>
	        <td>".$data."</td>
	    </tr>";
    }
    return $ret;
}

// DIFFERENZA TIMESTAMP
// Estrae la il tempo trascorso da un timestamp ad ora
/* 
My take on a function to find the differences between a timestamp and current time. 

Format: findTime($sometime['stamp'], '%d Days, %h Hours, %m Minutes'); 

Always use plural it will auto correct on singular results.  You don't have to include all %d,%m,%h you may include only one.  To get Total Hours remaining(including days) use %ho.  To get Total Minutes remaining(including hours and days) use %mo.  Take a look at the format I assumed to make any changes
*/

function findTime($timestamp, $format) {
    $difference = time() - $timestamp; 
    if($difference < 0) 
        return false; 
    else{ 
    
        $min_only = intval(floor($difference / 60)); 
        $hour_only = intval(floor($difference / 3600)); 
        
        $days = intval(floor($difference / 86400)); 
        $difference = $difference % 86400; 
        $hours = intval(floor($difference / 3600)); 
        $difference = $difference % 3600; 
        $minutes = intval(floor($difference / 60)); 
        if($minutes == 60){ 
            $hours = $hours+1; 
            $minutes = 0; 
        } 
        
        if($days == 0){ 
            $format = str_replace('Days', '?', $format); 
            $format = str_replace('Ds', '?', $format); 
            $format = str_replace('%d', '', $format); 
        } 
        if($hours == 0){ 
            $format = str_replace('Hours', '?', $format); 
            $format = str_replace('Hs', '?', $format); 
            $format = str_replace('%h', '', $format); 
        } 
        if($minutes == 0){ 
            $format = str_replace('Minutes', '?', $format); 
            $format = str_replace('Mins', '?', $format); 
            $format = str_replace('Ms', '?', $format);        
            $format = str_replace('%m', '', $format); 
        } 
        
        $format = str_replace('?,', '', $format); 
        $format = str_replace('?:', '', $format); 
        $format = str_replace('?', '', $format); 
        
        $timeLeft = str_replace('%d', number_format($days), $format);        
        $timeLeft = str_replace('%ho', number_format($hour_only), $timeLeft); 
        $timeLeft = str_replace('%mo', number_format($min_only), $timeLeft); 
        $timeLeft = str_replace('%h', number_format($hours), $timeLeft); 
        $timeLeft = str_replace('%m', number_format($minutes), $timeLeft); 
            
        if($days == 1){ 
            $timeLeft = str_replace('Days', 'Day', $timeLeft); 
            $timeLeft = str_replace('Ds', 'D', $timeLeft); 
        } 
        if($hours == 1 || $hour_only == 1){ 
            $timeLeft = str_replace('Hours', 'Hour', $timeLeft); 
            $timeLeft = str_replace('Hs', 'H', $timeLeft); 
        } 
        if($minutes == 1 || $min_only == 1){ 
            $timeLeft = str_replace('Minutes', 'Minute', $timeLeft); 
            $timeLeft = str_replace('Mins', 'Min', $timeLeft); 
            $timeLeft = str_replace('Ms', 'M', $timeLeft);            
        } 
            
      return $timeLeft; 
    } 
} 

// DATA PRIMA SPESA
// Restituisce la data della prima spesa inserita nel db
// formato salvato: GG/MM/YY -> date("d/m/y")

function data_prima_spesa ($db)
{
    $users = load_users();
    if ($db == NULL)  $date = time(); 
    else
    { 
        $linea = explode ('|', array_pop(array_reverse($db)));
        $data = $linea[$users[0]+4];
    }
    list ($giorno, $mese, $anno) = explode ('/', $data);
    $anno = "20".$anno;
    $timestamp = mktime(0, 0, 0, $mese, $giorno, $anno);
    return $timestamp;
}

// STATISTICHE TEMPO
// Stampa le medie di spesa al giorno, al mese e a settimana

function statistiche_tempo ($db)
{
    $giorni = (findTime(data_prima_spesa($db),"%d"));
    $totale = somma_spesa($db, "tutti");
	if($giorni == 0) return ;
    $spesa_giornaliera = round(($totale/$giorni),2);
    $spesa_mensile = $spesa_giornaliera * 30;
    $spesa_annuale = $spesa_mensile * 12;
    $output = "
    <tr>
		<td>Giorni trascorsi:</td>
		<td>$giorni</td>
	</tr>
	<tr>
		<td>Spesa giornaliera:</td>
		<td>$spesa_giornaliera&euro;</td>
	</tr>
	<tr>
		<td>Spesa mensile:</td>
		<td>$spesa_mensile&euro;</td>
	</tr>
	<tr>
    	<td>Spesa annuale:</td>
		<td>$spesa_annuale&euro;</td>
	</tr>
    ";
    return $output;
}

// STATISTICHE -------------------------------------------------------------------
// Stampa le statistiche: spesa totale, spesa non saldata, percentuali degli utenti

function statistiche($db) 
{
    $u = load_users();
    $output = "";
    $spesa_totale = somma_spesa($db, "tutti");
    $numero_utenti = $u[0];
    if ($spesa_totale == 0) return ;
    for($i=1;$i<=$numero_utenti;$i++)
    {
        $u_100[$i] = round(((somma_spesa($db,$i) * 100) / $spesa_totale));
    }
    $output .= "
    <legend>Statistiche:</legend>
    <table>
		<tr>
			<td>Totale speso:</td>
			<td>$spesa_totale &euro;</td>
		</tr>
		<tr>";
    for($i=1;$i<=$numero_utenti;$i++)
    {
        $output .= "
			<tr>
				<td>$u[$i]</td>
				<td>$u_100[$i]%</td>
			</tr>";
    }    
    $output .= statistiche_tempo($db);
	$output .= "</table>";    
	return $output;
}

// INFO UTENTI ----------------------------------------------------------------

function info_utenti()
{
    $users = load_users();
    $utenti = NULL;
    for($i=1;$i<=$users[0];$i++)
    {
        $utenti .= "<li>".$users[$i]."</li>"; 
    }
    $output = "
    <legend>".strtoupper($_SERVER['PHP_AUTH_USER'])."</legend>
    <ul>
    $utenti
    </ul>
    <form id='mod-utenti' method='post' action=''>
        <input type='submit' name='modifica_utenti' value='Impostazioni'>
    </form> ";
    return $output;
}

// CONTROLLI E MESSAGGI -------------------------------------------------------

function controlli_messaggi($db)
{
    if (isset($_POST['damodificare']))          return modifica_spesa($db);
    if (isset($_POST['damodificare_utenti']))   return modifica_utenti($db);
    if (isset($_POST['inserisci']))             return inserisci($db);
    if (isset($_POST['cancella']))              return cancella($db);
    if (isset($_POST['parte']))                 return invia_email($db);
    if (isset($_POST['aggiungi_riparatore']))   return aggiungi_riparatore($db);
    //if (($_POST == NULL) || (isset($_POST["modifica"])) || (isset($_POST["modifica_utenti"])) || (isset($_POST['aggiungi_riparatore_form']))) 
    else  
	return '<div id="lost">>:| 4 8 15 16 23 42</div>';  
}

?>
