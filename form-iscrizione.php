<!DOCTYPE html>
<html lang="it">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic' rel='stylesheet' type='text/css'>
        <script type="text/javascript">
        <!--
        var counter = 2;
        var limit = 9;
        var altezza = 450;
        function addInput(divName){
             if (counter > limit)  {
                  alert("Ma dove abiti, in una caserma? :)");
             }
             else {
                  var newdiv = document.createElement('div');
        newdiv.innerHTML = "<div class='user'><p><label>Nome utente "+(counter + 1) +"</label><input size='15' type='text' name='nome_utente["+(counter+1)+"]' required></p><p><label>Email utente "+(counter + 1) +"</label><input size='15' type='text' name='email_utente["+(counter + 1) +"]' required></p></div>";
                  document.getElementById(divName).appendChild(newdiv);
                  counter++;
                  document.getElementById('main').style.height = ( altezza + 129 ) + "px";
                  altezza = altezza + 129;
             }
        }
        function conta(){
            return counter;   
        }

        //-->
        </script>
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
        #main {
            margin-top: 100px;
            margin-left: auto;
            margin-right: auto;
            width: 800px;
            height: 450px;
            padding: 20px;
            box-shadow: 0 0 25px black;
            background-color: rgba(20,20,20,0.77);
            border-radius: 10px; 
            position: relative;
        }
        .left {
            position: absolute;
            top: 20px;
            width: 410px;
            text-align: center;
            font-size: 14px;
        }
        .right {
            float: right;
            margin-right: 10%;
        }
        .right p {
            text-align: center;
            font-size: 23px;
            width: 200px;
            margin: 60px 0;
        }
        #accedi {
            color: black; 
            border-radius: 10px;
            border: 3px solid #299818;
            box-shadow: 0 0 25px #299818;
            padding: 8px 0;
            background-color: #26BA28;
        }
        #accedi:hover {
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
        }
        #registrati:hover {
            border-color: #6B0A0A;
            box-shadow: 0 0 25px #6B0A0A;
            background-color: #E62626;
        }
        .footer {
            position: absolute;
            bottom: 5px;
            right: 15px;
            font-size: 14px;
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
        .form-right {
            position: absolute;
            top: 40px;
            right: 20px;
            text-align: right;
        }
        .user {
            color: black;
            border-radius: 10px;
            border: 3px solid #299818;
            background-color: #26BA28;
            border-radius: 10px;
            padding: 3px;
            margin: 10px 0;
        }
        .form-left {
            color: black;
            border-radius: 10px;
            border: 3px solid #299818;
            background-color: #26BA28;
            position: absolute;
            top: 220px;;
            left: 70px;
            text-align: right;
            padding: 6px;
        }
        form label {
            margin-right: 10px;
        }
        #inserisci {
            font-size: 22px;
            margin-top: 20px;
            margin-right: 100px;
            color: black;
            border-radius: 10px;
            border: 3px solid #C11818;
            box-shadow: 0 0 25px #C11818;
            padding: 10px;
            background-color: #FE3939;
        }
        #inserisci:hover {
            border-color: #6B0A0A;
            box-shadow: 0 0 25px #6B0A0A;
            background-color: #E62626;
        }
        input:focus {
            background-color: white;
        }
        input {
            background-color: green;
            border: 0px;
            padding: 5px;
        }
        </style>
        <title>CoserGate</title>
    </head>
    <body>
        <div id="main">
            <div class="left">
                <div class="title">
                    <h1>CoserGate</h1>
                    <h3>..e i conti tornano!</h3>
                </div>
		        <h2>Iscrizione</h2>
		        <p>Per aprire un ambiente tutto per te, compila questi campi:</p>
            </div>
            <form action="iscrivi.php" method="POST">
                <div class="form-left">
                    <p>
                        <label>Nome utente </label>
                        <input size="15" type="text" name="nome_ambiente" />
                    </p>
                    <p>
                        <label>Password</label>
                        <input size="15" type="password" name="password" required />
                    </p>
                    <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /><br />
                    <label>Ricopia questi caratteri</label>
                    <input type="text" name="captcha_code" size="12" maxlength="6" /><br />
                </div>
                <div class="form-right">
                    <div id="utenti">
                        <div class="user">
                            <p>
                                <label>Nome utente 1</label>
                                <input size="15" type="text" name="nome_utente[1]" required />
                            </p>
                            <p>
                                <label>Email utente 1</label>
                                <input size="15" type="text" name="email_utente[1]" required />
                            </p>
                        </div>
                        <div class="user">
                            <p>
                                <label>Nome utente 2</label>
                                <input size="15" type="text" name="nome_utente[2]" required />
                            </p>
                            <p>
                                <label>Email utente 2</label>
                                <input size="15" type="text" name="email_utente[2]" required />
                            </p>
                        </div>
                    </div>
                    <input type="button" value="Aggiungi utente" onClick="addInput('utenti');"><br />
                    <input id="inserisci" type="submit" value="Inserisci">
                </div>
            </form>	
            <div class="footer">
                <p>Ideato e realizzato da Riccardo Serafini. $~ <a href="http://tapion.it/">http://tapion.it/</a></p>
            </div>
        </div>
    </body>
</html>
