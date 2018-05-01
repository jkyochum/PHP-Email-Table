<?php
try{
    $pdo = new PDO('mysql:host=localhost;dbname=peqpypmy_testdb', 'peqpypmy_yochumj', '$J10k11y25$');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('SET NAMES "utf8"');
}
catch(PDOException $e){
    $error = 'Unable to connect to the database server.';
    include 'error.html.php';
    exit();
}

if(isset($_GET['adduser'])){
    $fname_err = "";
    $lname_err = "";
    $email_err = "";
    
	include 'insert.html.php';
	exit();
}

if(isset($_POST['newfirstname'])){  
        $fnameValid = false;
        $lnameValid = false;
        $emailValid = false;

    if($_POST['newfirstname'] == ""){
        $fname_err = 'First name cannot be empty';
    }
    else{
        $fname_err = '';
        $fnameValid = true;
    }
    
    if($_POST['newlastname'] == ""){
        $lname_err = 'Last name cannot be empty';
    }
    else{
        $lname_err = '';
        $lnameValid = true;
    }
    
    if($_POST['newemail'] == ""){
        $email_err = 'Email cannot be empty';
    }
    elseif(!preg_match('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.([a-zA-Z0-9-.]){2,}$/', $_POST['newemail'])){
        $email_err = 'Email format is not correct (ex. jeremy@yahoo.com)';   
    }
    else{
        $email_err = '';
        $emailValid = true;
    }

    if($fnameValid == true && $lnameValid == true && $emailValid == true){
        try{
            $sql = 'INSERT INTO users SET firstName=:firstname, lastName=:lastname, email=:email';
            $s = $pdo->prepare($sql);
            $s->bindValue(':firstname', $_POST['newfirstname']);
            $s->bindValue(':lastname', $_POST['newlastname']);
            $s->bindValue(':email', $_POST['newemail']);
            $s->execute();
        }
        catch(PDOException $e){
            $error = 'Error inserting user: '.$e->getMessage();
            include 'error.html.php';
            exit();
        }
            header('Location:.');
            exit();
    }
    else{
        include 'insert.html.php';
        exit();
    }
	
}

if(isset($_GET['deleteuser'])){
    try{
        $sql = 'SELECT firstName FROM users WHERE id = :id';
        $result = $pdo->prepare($sql);
        $result->bindValue(':id', $_POST['id']);
        $result->execute();
        $name = $result->fetch();
        
    } catch (Exception $e) {
        $error = "Error deleting user: ".$e->getMessage();
	include 'error.html.php';
	exit();
    }
	include 'delete_confirmation.html.php';
	exit();
}

if(isset($_GET['deletinguser']) && $_POST['delete'] == "Yes"){
	try{
        $sql = 'DELETE FROM users WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
	}
	catch(PDOException $e){
		$error = "Error deleting user: ".$e->getMessage();
		include 'error.html.php';
		exit();
	}
	header('Location:.');
	exit();
}

if(isset($_GET['id'])){
        $fname_err = "";
        $lname_err = "";
        $email_err = "";

	try{
		$sql = 'SELECT id,firstName,lastName,email FROM users WHERE id = :id';
		$s=$pdo->prepare($sql);
        $s->bindValue(':id', $_GET['id']);
        $s->execute();
	}
	catch(PDOException $e){
		$error = 'Error updating user: '.$e->getMessage();
		include 'error.html.php';
		exit();
	}

	//storing the selected info into an array to use on the edit page
	$row = $s->fetch(PDO::FETCH_ASSOC);   
	$users = array('id'=>$row['id'], 'firstname'=>$row['firstName'], 'lastname'=>$row['lastName'], 'email'=>$row['email']);

	include 'update.html.php';
	exit();
}

if(isset($_POST['updatedfirstname'])){
    $fnameValid = false;
    $lnameValid = false;
    $emailValid = false;

    if($_POST['updatedfirstname'] == ""){
        $fname_err = 'First name cannot be empty';
    }
    else{
        $fname_err = '';
        $fnameValid = true;
    }
    
    if($_POST['updatedlastname'] == ""){
        $lname_err = 'Last name cannot be empty';
    }
    else{
        $lname_err = '';
        $lnameValid = true;
    }
    
    if($_POST['updatedemail'] == ""){
        $email_err = 'Email cannot be empty';
    }
    else{
        $email_err = '';
        $emailValid = true;
    }
    
    if($fnameValid == true && $lnameValid == true && $emailValid == true){
        try{ 
            $sql = 'UPDATE users SET firstName=:firstname, lastName=:lastname, email=:email WHERE id=:id';
            $s=$pdo->prepare($sql);
            $s->bindValue(':id', $_POST['id']);
            $s->bindValue(':firstname', $_POST['updatedfirstname']);
            $s->bindValue(':lastname', $_POST['updatedlastname']);
            $s->bindValue(':email', $_POST['updatedemail']);
            $s->execute();   
        }
        catch(PDOException $e){
            $error = 'Error updating user: '.$e->getMessage();
            include 'error.html.php';
            exit();
        }
        header('Location:.');
        exit();
    } 

    include 'update.html.php';
    exit();
}

try{
    $sql = 'SELECT id,firstName,lastName,email FROM users';
    $res = $pdo->query($sql);
}
catch(PDOException $e){
    $error = 'Error updating user: '.$e->getMessage();
    include 'error.html.php';
    exit();
}
while($row = $res->fetch()){
    $users[] = array('id'=>$row['id'], 'firstname'=>$row['firstName'], 'lastname'=>$row['lastName'], 'email'=>$row['email']);
}

include 'demo.html.php';
?>