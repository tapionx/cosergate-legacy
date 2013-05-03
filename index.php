<!DOCTYPE html>
<html lang="it">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic' rel='stylesheet' type='text/css'>
        <style type="text/css">
        
        body {
            /*
            font-family: 'Open Sans', sans-serif;
            */
            color: white;
            background: url(img/back.jpg) repeat;
        }
        tr:hover {
            background-color: yellow;
        }
        .title h1 {
            margin-bottom: 0px;
        }
        .title h3 {
            text-align: right;
            margin-top: 0px;
        }
        .main {
            position: relative;
            margin-top: 100px;
            margin-left: auto;
            margin-right: auto;
            width: 800px;
            height: 345px;
            padding: 20px;
            box-shadow: 0 0 25px black;
            background-color: rgba(20,20,20,0.77);
            border-radius: 10px; 
        }
        .left {
            position: absolute;
            top: 5%;
            width: 410px;
            text-align: center;
            font-size: 14px;
        }
        .right {
            position: absolute;
            right: 7%;
            top: 10%;
        }
        .right p {
            text-align: center;
            font-size: 23px;
            width: 200px;
            margin: 60px 0;
        }
        #login-form {
            color: black; 
            border-radius: 10px;
            border: 3px solid #299818;
            box-shadow: 0 0 25px #299818;
            padding: 12px;
            background-color: #26BA28;
	    margin-top: 30px;
        }
        #login-form:hover {
            border-color: #165C0B;
            box-shadow: 0 0 25px #165C0B;
            background-color: #1CAE1E;
        }
        #registrati {
            color: black;  
            border-radius: 10px;
            border: 3px solid #C11818;
            box-shadow: 0 0 25px #C11818;
            padding: 8px 0;
            background-color: #FE3939; 
	    margin-left: 45px;
        }
        #registrati:hover {
            border-color: #6B0A0A;
            box-shadow: 0 0 25px #6B0A0A;
            background-color: #E62626;
        }
        .footer {
            font-size: 14px;
            position: absolute;
            bottom: 2%;
            right: 4%;
        }
        .right a {
            text-decoration: none;
        }
        a {
            color: #67B9FD;
        }
        h1 {
            font-size: 38px;
        }
        </style>
        <title>CoserGate</title>
    </head>
    <body>
        <div class="main">
            <div class="left">
                <div class="title">
                    <h1>CoserGate</h1>
                    <h3>..e i conti tornano!</h3>
		        </div>
                <p>Quanto tempo perdi per regolare i conti con i tuoi coinquilini?</p>
		        <p>CoserGate ti aiuta a gestire la contabilità senza fatica.</p>
                <h4>Come funziona?</h4>
                <p>Semplice! Basta inserire i dati degli scontrini nel sistema, specificando chi intende utilizzare ogni prodotto acquistato e chi ha pagato.</p>
                <p>Viene visualizzata una tabella riassuntiva in cui puoi leggere quanto devi restituire agli altri.</p>    
            </div>
            <div class="right">
		<div id="login-form">
			<form method="post" action="cosergate.php">
				<label for="username">Nome utente</label>
				<input type="text" name="username" size="10">
				<br>
				<label for="password" style="margin-right: 25px;">Password</label>
				<input type="password" name="password" size="10">
				<br>
				<input type="submit" value="Accedi">
			</form>
		</div>
                <a href="./form-iscrizione.php"><p id="registrati">REGISTRATI</p></a>
            </div>
            <div class="footer">
                <p>Ideato e realizzato da Riccardo Serafini. $~ <a href="http://tapion.it/">http://tapion.it/</a></p>
            </div>
        </div>
    </body>
</html>
