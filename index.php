<?php
    session_start();
    /* Reset session and start from first step */
    if(isset($_POST['submit']) && $_POST['submit'] == "Abbrechen"){
        session_destroy();
        header("Location:index.php");
    }
    if(!isset($_SESSION['steps'])) $_SESSION['steps']   =   [];

    /* Declare variables for all kind of erros  */
    $targets_error          =   '';
    $choice1_error          =   '';
    $choice2_error          =   '';
    $choice3_error          =   '';
    $contact_error          =   '';
    $name_error             =   '';
    $zip_error              =   '';
    $phone_error            =   '';
    
    /* Check if form is submitted or not */
    if(isset($_POST) && !empty($_POST['submit'] )){

        /* If first step is submitted then proceed */
        if($_POST['submit'] == 'Schritt 1'){
            if (empty($_POST["choice1"]) && empty($_POST["choice2"]) && empty($_POST["choice3"])) {
                $targets_error =   "Bitte mindestens eine Wahl angeben.";
            }else {
                /* Check if choice 1 is filled or not, if filled then check for special characters */
                if(!empty($_POST["choice1"])){
                    if (!preg_match("/^[a-zA-Z ]*$/",$_POST["choice1"]))
                        $choice1_error = "Keine Sonderzeichen gestattet"; 
                    else
                        $_SESSION['form']['choice1']    =  $_POST["choice1"]; 
                }else
                    $_SESSION['form']['choice1']    =  ''; 

                /* Check if choice 2 is filled or not, if true then check for special characters */
                if(!empty($_POST["choice2"])){
                    if (!preg_match("/^[a-zA-Z ]*$/",$_POST["choice2"]))
                        $choice2_error = "Keine Sonderzeichen gestattet."; 
                    else
                        $_SESSION['form']['choice2']    =  $_POST["choice2"]; 
                }else
                    $_SESSION['form']['choice2']    =  ''; 

                /* Check if choice 3 is filled or not, if filled then check for special characters */
                if(!empty($_POST["choice3"])){
                    if (!preg_match("/^[a-zA-Z ]*$/",$_POST["choice3"])) 
                        $choice3_error = "Keine Sonderzeichen gestattet."; 
                    else
                        $_SESSION['form']['choice3']    =  $_POST["choice3"]; 
                }else
                    $_SESSION['form']['choice3']    =  ''; 

                /* Perform check for next step if targets step is successfully filled without errors after validation */
                if(empty($targets_error) && empty($choice1_error) && empty($choice2_error) && empty($choice3_error)){
                    $_SESSION['steps'][] = $_POST['submit'];
                }
            }  
        }

         /* If second step is submitted proceed  */
        if($_POST['submit'] == 'Schritt 2'){
            if (empty($_POST["first_last_name"]) && empty($_POST["zip"]) && empty($_POST["phone"])) {
                $contact_error  =   "Bitte die Felder entsprechend ausfÃ¼llen.";
            }else {
                /* Check if first & last name is filled or not, if filled then check for special characters */
                if(!empty($_POST["first_last_name"])){
                    if (!preg_match("/^[a-zA-Z ]*$/",$_POST["first_last_name"]))
                        $name_error = "Keine Sonderzeichen gestattet."; 
                    else
                        $_SESSION['form']['first_last_name']    =   $_POST["first_last_name"]; 
                }else
                    $_SESSION['form']['first_last_name']    =   ''; 
            
                /* Check if zip & place is filled or not, if filled then check for special characters */
                if(!empty($_POST['zip'])){
                    if (!preg_match("/^\d{5}\s\w+/",$_POST["zip"]))
                        $zip_error = "Bitte eine 5-stellige PLZ und einen Ort eingeben."; 
                    else
                        $_SESSION['form']['zip']    =   $_POST["zip"]; 
                }else
                    $_SESSION['form']['zip']        =   ''; 

                /* Check if phone is filled or not, if filled then check for numbers */
                if(!empty($_POST['phone'])){
                    if (!preg_match("/^[0-9]+$/",$_POST["phone"]))
                        $phone_error = "Bitte nur Zahlen eingeben.";
                    else
                        $_SESSION['form']['phone']  =   $_POST["phone"]; 
                }else
                    $_SESSION['form']['phone']      =   ''; 

                 /* Make check for next step if contacts detail step is successfully filled without errors after validation */
                if(empty($contact_error) && empty($name_error) && empty($zip_error) && empty($phone_error)){
                    $_SESSION['steps'][] = $_POST['submit'];
                }
            }  
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Wunschliste</title>
    <style type="text/css">
        .error{ color: red;}
    </style>
</head>
<body>
<!--  If user first time come or reset then it will start from here  -->
<?php if(empty($_SESSION['steps']) && !in_array('Schritt 1',$_SESSION['steps'])): ?>
<form method="post" action="">
    <h1>Meine Urlaubswunschliste</h1>
    <span class="error"><?php echo $targets_error;?></span>
    <table>
        <tr>
            <td>
                <label class="<?php if(!empty($choice1_error)) echo 'error'; ?>">1. Wahl</label>
            </td>
            <td>
                <input type="text" name="choice1" placeholder="Wahl 1" value="<?php if(isset($_SESSION['form']['choice1'])) echo $_SESSION['form']['choice1']; ?>">
                <span class="error"><?php echo $choice1_error;?></span>
            </td>
        </tr>
        <tr>
            <td>
                <label class="<?php if(!empty($choice2_error)) echo 'error'; ?>">2. Wahl</label>
            </td>
            <td>
                <input type="text" name="choice2" placeholder="Wahl 2" value="<?php if(isset($_SESSION['form']['choice2'])) echo $_SESSION['form']['choice2']; ?>">
                <span class="error"><?php echo $choice2_error;?></span>
            </td>
        </tr>
        <tr>
            <td>
                <label class="<?php if(!empty($choice3_error)) echo 'error'; ?>">3. Wahl</label>
            </td>
            <td>
                <input type="text" name="choice3" placeholder="Wahl 3" value="<?php if(isset($_SESSION['form']['choice3'])) echo $_SESSION['form']['choice3']; ?>">
                <span class="error"><?php echo $choice3_error;?></span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" name="submit" value="Abbrechen">
                <input type="submit" name="submit" value="Schritt 1">
            </td>
        </tr>
    </table>
</form>
<?php 
endif;
/* If user has successfully filled last step(vactions targets) */
if(!empty($_SESSION['steps']) && in_array('Schritt 1',$_SESSION['steps']) && !in_array('Schritt 2',$_SESSION['steps'])): ?>
<form method="post" action="">
    <h1>Kontaktdaten</h1>
    <div>
        <?php
            if(isset($_SESSION['form']['choice1']) && !empty($_SESSION['form']['choice1'])) echo '<h4>1 Wahl: '.$_SESSION['form']['choice1']."<h4>";
            if(isset($_SESSION['form']['choice2']) && !empty($_SESSION['form']['choice2'])) echo '<h4>2 Wahl: '.$_SESSION['form']['choice2']."<h4>";
            if(isset($_SESSION['form']['choice3']) && !empty($_SESSION['form']['choice3'])) echo '<h4>3 Wahl: '.$_SESSION['form']['choice3']."<h4>";
        ?>
    </div>
    <span class="error"><?php echo $contact_error;?></span>
    <table>
        <tr>
            <td>
                <label class="<?php if(!empty($name_error)) echo 'error'; ?>">Vor- und Nachname</label>
            </td>
            <td>
                <input type="text" name="first_last_name" placeholder="Vor- und Nachname" value="<?php if(isset($_SESSION['form']['first_last_name'])) echo $_SESSION['form']['first_last_name']; ?>">
                <span class="error"><?php echo $name_error;?></span>
            </td>
        </tr>
        <tr>
            <td>
                <label class="<?php if(!empty($zip_error)) echo 'error'; ?>">PLZ und Ort</label>
            </td>
            <td>
                <input type="text" name="zip" placeholder="PLZ und Ort" value="<?php if(isset($_SESSION['form']['zip'])) echo $_SESSION['form']['zip']; ?>">
                <span class="error"><?php echo $zip_error;?></span>
            </td>
        </tr>
        <tr>
            <td>
                <label class="<?php if(!empty($phone_error)) echo 'error'; ?>">Telefonnummer</label>
            </td>
            <td>
                <input type="text" name="phone" placeholder="Telefonnummer" value="<?php if(isset($_SESSION['form']['phone'])) echo $_SESSION['form']['phone']; ?>">
                <span class="error"><?php echo $phone_error ;?></span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" name="submit" value="Abbrechen">
                <input type="submit" name="submit" value="Schritt 2">
            </td>
        </tr>
    </table>
</form>
<?php
    endif;
    /* If user has successfully filled last step(contact details) */
    if(!empty($_SESSION['steps']) && in_array('Schritt 2',$_SESSION['steps'])):
        echo "<h1>Übersicht</h1>";
        if(isset($_SESSION['form']['choice1']) && !empty($_SESSION['form']['choice1'])) echo '<h4>1 Wahl: '.$_SESSION['form']['choice1']."<h4>";
        if(isset($_SESSION['form']['choice2']) && !empty($_SESSION['form']['choice2'])) echo '<h4>2 Wahl: '.$_SESSION['form']['choice2']."<h4>";
        if(isset($_SESSION['form']['choice3']) && !empty($_SESSION['form']['choice3'])) echo '<h4>3 Wahl: '.$_SESSION['form']['choice3']."<h4>";
        if(isset($_SESSION['form']['first_last_name']) && !empty($_SESSION['form']['first_last_name'])) echo '<h4>Vor- und Nachname: '.$_SESSION['form']['first_last_name']."<h4>";
        if(isset($_SESSION['form']['zip'])   && !empty($_SESSION['form']['zip']))     echo '<h4>PLZ und Ort: '.$_SESSION['form']['zip']."<h4>";
        if(isset($_SESSION['form']['phone']) && !empty($_SESSION['form']['phone']))   echo '<h4>Telefonnummer: '.$_SESSION['form']['phone']."<h4>";
?>
    <form method="post" action="">
        <input type="submit" name="submit" value="Abbrechen">
        <button type="button" name="ok" value="ok">Ok</button>
    </form>
<?php
    endif;
?>
</body>
</html>
