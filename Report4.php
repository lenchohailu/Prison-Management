<?php
include('session.php');
include('DB.php'); // must contain mysqli $conn
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Woliso Prison Management System - Report</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .container-custom {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Return Home Button */
        .return-home-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .return-home-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(40,167,69,0.3);
            color: white;
            text-decoration: none;
        }
        
        .return-home-btn i {
            font-size: 18px;
        }
        
        /* Report Card */
        .report-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .report-header {
            background: linear-gradient(135deg, #2c3e50 0%, #1a1a2e 100%);
            color: white;
            padding: 25px 30px;
            border-bottom: 3px solid #667eea;
        }
        
        .report-header h2 {
            margin: 0;
            font-size: 24px;
        }
        
        .report-header h2 i {
            margin-right: 10px;
        }
        
        .report-body {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
            outline: none;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 150px;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        
        .alert-danger {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        
        @media (max-width: 768px) {
            .report-body {
                padding: 20px;
            }
            .return-home-btn {
                width: 100%;
                justify-content: center;
            }
            .btn-submit, .btn-cancel {
                width: 100%;
                margin-bottom: 10px;
                text-align: center;
            }
        }
    </style>
</head>

<body>

<div class="container-custom">
    
    <!-- Return to Home Page Button -->
    <a href="index5.php" class="return-home-btn">
        <i class="fa fa-home"></i> Return to Home Page
    </a>
    
    <!-- Report Card -->
    <div class="report-card">
        <div class="report-header">
            <h2>
                <i class="fa fa-file-text"></i> Submit a Report
            </h2>
        </div>
        
        <div class="report-body">
            
            <?php
            if (isset($_POST["postB"])) {

                $title = trim($_POST["title"]);
                $post = trim($_POST["post"]);

                if ($title === "" || $post === "") {
                    echo "<div class='alert alert-danger'>
                            <i class='fa fa-exclamation-triangle'></i> Please fill all fields.
                          </div>";
                } else {

                    $stmt = $conn->prepare("
                        INSERT INTO post (title, post, postby, date)
                        VALUES (?, ?, ?, NOW())
                    ");

                    $stmt->bind_param("sss", $title, $post, $_SESSION["userName"]);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>
                                <i class='fa fa-check-circle'></i> Report submitted successfully!
                              </div>";
                    } else {
                        echo "<div class='alert alert-danger'>
                                <i class='fa fa-exclamation-triangle'></i> Error: " . $stmt->error . "
                              </div>";
                    }

                    $stmt->close();
                }
            }
            ?>

            <form method="post" action="">
                <div class="form-group">
                    <label><i class="fa fa-tag"></i> Report Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter report title" required>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-pencil"></i> Report Content</label>
                    <textarea name="post" rows="6" class="form-control" placeholder="Write your report here..." required></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" name="postB" class="btn-submit">
                        <i class="fa fa-send"></i> Submit Report
                    </button>
                    <a href="index5.php" class="btn-cancel" style="margin-left: 10px;">
                        <i class="fa fa-times"></i> Cancel
                    </a>
                </div>
            </form>
            
        </div>
    </div>
    
</div>

<?php include('footer.php'); ?>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>