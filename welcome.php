<?php

session_start();
if(!isset($_SESSION['login']) || $_SESSION['login']!=true){
    header('location:login.php');
    exit;
}

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo "Welcome ". $_SESSION['username']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php require 'partials/_nav.php';  ?>
    <?php require 'partials/_dbconnect.php';  ?>

    <?php

$alert = false;
$editAlert = false;
$deleteAlert = false;

$servername = "localhost";
$username = "root";
$password = "";
$database = "inotes";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    echo "Failed to connect for this =>" . mysqli_connect_error();
}


// INSERTION
if(isset($_GET['delete'])){

    $sno = $_GET['delete'];
    $sql = "DELETE FROM `notes` WHERE `notes`.`sno` = $sno";
    $result = mysqli_query($conn, $sql);

    if($result){
        $deleteAlert = true;
    }

}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if (isset($_POST['snoEdit'])) {


        //    UPDATION


        $sno = $_POST['snoEdit'];
        $title = $_POST['titleEdit'];
        $description = $_POST['descriptionEdit'];

        $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `notes`.`sno` = $sno";
        $result = mysqli_query($conn, $sql);

        if($result){
            $editAlert = true;
        }





    } else {

        // INSERTION

        $title = $_POST['title'];
        $description = $_POST['description'];

        $sql = "INSERT INTO `notes` (`sno`, `title`, `description`, `time`) VALUES (NULL, '$title', '$description', current_timestamp())";
        $result = mysqli_query($conn, $sql);

        if($result){
            $alert = true;
        }
    }
}













?>


    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>iNotes-Notes taking made easy</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

        <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    </head>

    <body>

        <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">iNotes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>


                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav> -->



        <?php

    if ($alert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Record has been inserted successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
    }

    if($editAlert){

        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Record has been updated successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';

    }
    if($deleteAlert){

        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Record has been deleted successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';

    }

    ?>



        <div class="container my-4">
            <h2>Add a Note</h2>
            <form action="/loginsystem/welcome.php" method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">Note Title</label>
                    <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Note Description</label>
                        <div class="form-floating">
                            <textarea class="form-control" name="description" placeholder="Leave a comment here"
                                id="description" style="height: 100px"></textarea>

                        </div>

                        <button type="submit" class="btn btn-primary my-2">Add Note</button>
            </form>
        </div>
        <div class="container">



            <table class="table" id="myTable">
                <thead>
                    <tr>

                        <th scope="col">Sno</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn, $sql);

                $sno=0;


                while ($row = mysqli_fetch_assoc($result)) {
                    $sno=$sno+1;
                    echo "   <tr>
                    <th scope='row'>" .$sno . "</th>
                    <td>" . $row['title'] . "</td>
                    <td>" . $row['description'] . "</td>
                    <td><button type='button' class=' edit btn btn-primary' id=" . $row['sno'] . " >Edit</button> <button type='button'  class=' delete btn btn-primary' id=d".$row['sno'].">Delete</button></td>
                </tr>";
                }
                ?>

                </tbody>
            </table>



        </div>


        <!-- Button trigger modal -->
        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
  Launch static backdrop modal
</button> -->

        <!-- Modal -->
        <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit Records</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/loginsystem/welcome.php?update=true" method="post">
                            <input type="hidden" name="snoEdit" id="snoEdit">
                            <div class="mb-3">
                                <label for="title" class="form-label">Note Title</label>
                                <input type="text" class="form-control" id="titleEdit" name="titleEdit"
                                    aria-describedby="emailHelp">

                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Note Description</label>
                                    <div class="form-floating">
                                        <textarea class="form-control" name="descriptionEdit"
                                            placeholder="Leave a comment here" id="descriptionEdit"
                                            style="height: 100px"></textarea>

                                    </div>

                                    <button type="submit" class="btn btn-primary my-2">Update Note</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
        </script>


        <script src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>


        <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
        </script>



        <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit");
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName('td')[0].innerText;
                description = tr.getElementsByTagName('td')[1].innerText;
                console.log(title, description);
                titleEdit = document.getElementById('titleEdit');
                decriptionEdit = document.getElementById('descriptionEdit');
                titleEdit.value = title;
                decriptionEdit.value = description;
                snoEdit.value = e.target.id;
                console.log(e.target.id);


                $('#editModal').modal('toggle');

            })
        })


        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                sno = e.target.id.substr(1, );

                if (confirm("Do you want to delete this note ?")) {

                    console.log("Yes");


                    window.location = `/loginsystem/welcome.php?delete=${sno}`;

                } else {
                    console.log("No")
                }
            })
        })
        </script>



    </body>

    </html>







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>
</body>

</html>