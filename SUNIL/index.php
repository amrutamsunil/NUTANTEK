<?php
include ('dbconfig.php');



if(@$_GET['page']!=""){
    if($_GET['page']==2){
        header('location:search_email.php');
    }
}


function read_docx($filename){

    $striped_content = '';
    $content = '';

    if(!$filename || !file_exists($filename)) return false;

    $zip = zip_open($filename);
    if (!$zip || is_numeric($zip)) return false;

    while ($zip_entry = zip_read($zip)) {

        if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

        if (zip_entry_name($zip_entry) != "word/document.xml") continue;

        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

        zip_entry_close($zip_entry);
    }
    zip_close($zip);
    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
    $content = str_replace('</w:r></w:p>', "\r\n", $content);
    $striped_content = strip_tags($content);

    return $striped_content;
}
function array_flatten($array) {
    if (!is_array($array)) {
        return FALSE;
    }
    $result = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, array_flatten($value));
        }
        else {
            $result[$key] = $value;
        }
    }
    return $result;
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
    <a class="navbar-brand" href="#"><img style="width: 30px;height: 24px;" src="n.png"></a>
    <!-- Links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" style="color: white" href="#">UPLOAD</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" style="color: white" href="index.php?page=2">DISPLAY</a>
        </li>
    </ul>

</nav>
<form method="post"  enctype="multipart/form-data">
<div class="jumbotron">
    <div class="form-group row">
        <label for="fup" class="col-sm-6 col-form-label ">Upload here</label>
        <div class="col-sm-6">
            <input type="file" id='fup' name="resume" />
        </div>

    </div>
<br/><br/>
    <div class="row">
        <div class="col-sm-12">
            <center><input type="submit" name="upload" class="btn btn-primary" value="Upload"></center></div>
    </div>
</div>
</form>
<?php
if(isset($_POST['upload'])){
    if($_FILES['resume']['name']) {
        $fname = explode(".", $_FILES['resume']['name']);
        if (end($fname) == "docx") {
            $f = fopen($_FILES['resume']['tmp_name'], "r");
            $data = $_FILES['resume']['tmp_name'];
            $data_ = read_docx($data);
            preg_match_all("/[\.a-zA-Z0-9-]+@[\.a-zA-Z0-9-]+/i", $data_, $email_id);
            preg_match('/([0-9]{3})?[\.)(]*([0-9]{10})/', $data_, $ph_no);
            $email_id = array_flatten($email_id);
            $ph_no = array_flatten($ph_no);
            $phone_number = end($ph_no);
            $email_id_ = end($email_id);
            if (!(($email_id_ == "") && ($phone_number == ""))) {

                $query = "insert into resume(email_id, mobile_no, file) values ('$email_id_','$phone_number','$data_')";
                $q = mysqli_query($conn, $query);
                if ($q) {
                    echo "<center><p style='font-size:20px; color: dodgerblue'>Successfully added record $email_id_ and $phone_number </p></center>";
                } else {
                    echo "<center><p style='font-size: 20px;color: palevioletred'>Error In Adding the Record !!</p></center>";
                }
            } else {
                echo "<center><p style='font-size: 20px;color: palevioletred'>NO RELEVANT RECORD FOUND IN THE FILE !!</p></center>";

            }
        }else{
            echo "<center><p style='font-size: 20px;color: palevioletred'>UNSUPPORTED FILE FORMAT!!</p></center>";

        }
    }
}
?>

    <!-- /.col-lg-12 -->

<!-- /.row -->
<!-- /#wrapper -->

<!-- jQuery -->

<!-- Bootstrap Core JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<p>**Only Docx file format is supported</p>

</body>

</html>
