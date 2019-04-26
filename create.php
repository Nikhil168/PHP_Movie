<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $language = $budget = $type = "";
$name_err = $language_err = $budget_err = $type_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate language type
    // $input_language = trim($_POST["language"]);
    // if(empty($input_language)){
    //     $language_err = "Please select an language_type.";     
    // } else{
    //     $language = $input_language;
    // }
    if(isset($POST['language']))
    {
        $data = $_POST["language"];
        echo "Selected langauage: " . $data;     
    }

    
    // Validate budget
    $input_budget = trim($_POST["budget"]);
    if(empty($input_budget)){
        $budget_err = "Please enter the budget amount.";     
    } elseif(!ctype_digit($input_budget)){
        $budget_err = "Please enter a positive integer value.";
    } else{
        $budget = $input_budget;
    }

     // Validate movie_type
     $input_type = trim($_POST["type"]);
     if(empty($input_type)){
         $type_err = "Please select type of movie(2D or 3D)";     
     } else{
         $type = $input_type;
     }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($language_err) && empty($budget_err) && empty($type_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO movie (name,language,budget,type) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_language, $param_budget,$param_type);
            
            // Set parameters
            $param_name = $name;
            $param_language = $language;
            $param_budget = $budget;
            $param_type = $type;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: homepage.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($language_err)) ? 'has-error' : ''; ?>">
                            <label>Language</label>
                            <select>
                            <option value="Select">Select</option>
                            <option value="Marathi">Marathi</option>
                            <option value="Hindi">Hindi</option>
                            <option value="English">English</option>
                            </select>
                            <span class="help-block"><?php echo $language_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($budget_err)) ? 'has-error' : ''; ?>">
                            <label>Budget</label>
                            <input type="text" name="budget" class="form-control" value="<?php echo $budget; ?>">
                            <span class="help-block"><?php echo $budget_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($type_err)) ? 'has-error' : ''; ?>">
                            <label>Type</label>
                            <input type="radio" name="type" value="$2D"> 2D
                           <input type="radio" name="type" value="$3D"> 3D<br>
                            <span class="help-block"><?php echo $type_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="homepage.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
