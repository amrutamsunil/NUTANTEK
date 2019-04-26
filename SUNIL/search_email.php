<?php
if(@$_GET['page']!=""){
if($_GET['page']==1){
header('location:index.php');
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="n.png" rel="icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

</head>

<body style="overflow-y: scroll">

<nav class="navbar navbar-expand-sm bg-dark">
    <!-- Links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" style="color: white" href="search_email.php?page=1">UPLOAD</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" style="color: white" href="#">DISPLAY</a>
        </li>
    </ul>

</nav>
<div class="jumbotron">
<form method="post">
    <div class="form-group">
        <label for="Email">Email address</label>
        <input type="text" name="email_id" class="form-control" id="Email" aria-describedby="emailHelp" placeholder="Enter email" required>
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <input type="submit" name="display" value="GET DATA" class="btn btn-primary">
</form>
</div>
<div class="container-fluid">
<?php
include ('dbconfig.php');
if(isset($_POST['display'])){
    $email_id=$_POST['email_id'];
    $query=mysqli_query($conn,"select * from resume where email_id like '%$email_id%' ");
    if(mysqli_num_rows($query)>0){
        echo "<br>EMAIL ID's FOUND SIMILAR  FROM DB ARE : <br>";
        $i=0;
        while ($lists=mysqli_fetch_array($query)){
            ++$i;
            echo " $i. ".$lists['email_id']."<br/>";

        }
    }
    else{
        echo "<p style='color: palevioletred'>NO RECORD FOUND !!</p>";
    }
}
?>
</div>
</body>

</html>