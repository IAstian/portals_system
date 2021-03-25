<?php    
    require './config.php';
    $errors = array();
    function insert($title,$document){
        global $errors,$conn;        
        if(empty($title)){
            array_push($errors,"Title Required");
        }
        if(empty($document)){
            array_push($errors,"Document Required");
        }
        if(count($errors) == 0){
            if(!empty($document)){
                $folder = './assignments/';
                $filename = basename($document);
                $file_path = $folder.$filename;
                $extension  = pathinfo($file_path, PATHINFO_EXTENSION);
                $formats = array('pdf','docx','zip','txt','rar');
                $doc = addslashes(file_get_contents($_FILES['pdf']['tmp_name']));
                $tmp_name = $_FILES['pdf']['tmp_name'];
                if(in_array($extension,$formats)){
                    if(move_uploaded_file($tmp_name,$file_path)){
                        include './config.php';
                        $query = "INSERT INTO assignments (title, doc_file) VALUES('$title','$filename')";
                        $action = mysqli_query($conn,$query);  
                        if($action){
                            echo 'Uploaded succesfully';
                        }else{
                            echo 'Failed to add assignment';
                            return false;
                        }
                    }else{
                        echo 'Failed operation';
                    }
                }else{
                    array_push($errors,"Could not complete request");
                    echo 'Invalid file format';
                }
            }else{
                echo 'Document Required';
            }
        }else{
            print_r($errors);
            return false;
        }
    }
    if(isset($_POST['upload'])){
        //get form data
        $title = $_POST['title'];
        //get document
        $document = $_FILES['pdf']['name'];
        //call function to insert
        insert($title,$document);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/panel.css">
    <title>Add assignment</title>
</head>
<body>
<center>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <label>Assignment Title</label> <br><br>
            <input type="text" name="title"> <br> <br>

            <label for="price">File</label> <br><br>
            <input type="file" name="pdf"> <br><br>
            <br>
        
            <input type="submit" value="Upload" name="upload" class="btn btn-primary">
        </form>
        </center>
</body>
</html>