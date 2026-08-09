<?php
include('session.php');
include('DB.php');

$upload_msg = "";
$prisoner_info = null;
$prisoner_id = "";
$existing_image = "";

// Fetch prisoner info if ID is provided
if(isset($_POST['fetch_prisoner']) && !empty($_POST['prisoner_id'])) {
    $prisoner_id = mysqli_real_escape_string($conn, $_POST['prisoner_id']);
    
    $stmt = $conn->prepare("SELECT * FROM prisoner WHERE prison_ID = ?");
    $stmt->bind_param("i", $prisoner_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $prisoner_info = $result->fetch_assoc();
        
        // Get existing image if any
        $img_stmt = $conn->prepare("SELECT path FROM prisoner_images WHERE prisoner_id = ? ORDER BY id DESC LIMIT 1");
        $img_stmt->bind_param("i", $prisoner_id);
        $img_stmt->execute();
        $img_stmt->bind_result($existing_image);
        $img_stmt->fetch();
        $img_stmt->close();
    } else {
        $upload_msg = "<div class='alert alert-danger'>Prisoner ID not found. Please check the ID.</div>";
    }
    $stmt->close();
}

// Image Upload
if(isset($_FILES['uploadedimage']) && $_FILES['uploadedimage']['name'] != "" && isset($_POST['prisoner_id']) && !empty($_POST['prisoner_id']))
{
    $prisoner_id = mysqli_real_escape_string($conn, $_POST['prisoner_id']);
    
    // Verify prisoner exists
    $check_stmt = $conn->prepare("SELECT prison_ID, prison_fname, prison_lname FROM prisoner WHERE prison_ID = ?");
    $check_stmt->bind_param("i", $prisoner_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if($check_result->num_rows > 0) {
        $prisoner_data = $check_result->fetch_assoc();
        
        function GetImageExtension($imagetype)
        {
            if(empty($imagetype)) return false;
            
            switch($imagetype)
            {
                case 'image/bmp': return '.bmp';
                case 'image/gif': return '.gif';
                case 'image/jpeg': return '.jpg';
                case 'image/png': return '.png';
                default: return false;
            }
        }
        
        $file_name  = $_FILES["uploadedimage"]["name"];
        $temp_name  = $_FILES["uploadedimage"]["tmp_name"];
        $imgtype    = $_FILES["uploadedimage"]["type"];
        
        $ext = GetImageExtension($imgtype);
        
        if($ext === false)
        {
            $upload_msg = "<div class='alert alert-danger'>Invalid image type. Please upload JPG, PNG, GIF, or BMP files only.</div>";
        }
        else
        {
            // Create directory if not exists
            $upload_dir = "prisoner_images/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Create filename with prisoner ID and timestamp
            $imagename = "prisoner_" . $prisoner_id . "_" . date("Y_m_d") . "_" . time() . $ext;
            $target_path = $upload_dir . $imagename;
            
            if(move_uploaded_file($temp_name, $target_path))
            {
                // Delete old image if exists
                if(!empty($existing_image) && file_exists($existing_image)) {
                    unlink($existing_image);
                }
                
                // Insert into database
                $stmt = $conn->prepare("INSERT INTO prisoner_images (prisoner_id, path, uploaded_by, upload_date) VALUES (?, ?, ?, NOW())");
                $username = $_SESSION["userName"];
                $stmt->bind_param("iss", $prisoner_id, $target_path, $username);
                
                if($stmt->execute())
                {
                    $existing_image = $target_path;
                    $upload_msg = "<div class='alert alert-success'>Image uploaded successfully for Prisoner ID: " . $prisoner_id . " (" . htmlspecialchars($prisoner_data['prison_fname'] . " " . $prisoner_data['prison_lname']) . ")</div>";
                }
                else
                {
                    $upload_msg = "<div class='alert alert-danger'>Database Error: " . $stmt->error . "</div>";
                }
                $stmt->close();
            }
            else
            {
                $upload_msg = "<div class='alert alert-danger'>Error uploading image. Please check folder permissions.</div>";
            }
        }
    } else {
        $upload_msg = "<div class='alert alert-danger'>Invalid Prisoner ID. Please fetch a valid prisoner first.</div>";
    }
    $check_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Prisoner Image Management - Woliso Prison</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>
    body {
        background: #f5f5f5;
    }
    
    .prisoner-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .prisoner-header {
        background: linear-gradient(135deg, #1e2a3e 0%, #0f1722 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 10px 10px 0 0;
    }
    
    .prisoner-info {
        padding: 20px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .prisoner-info p {
        margin: 8px 0;
        font-size: 14px;
    }
    
    .prisoner-info strong {
        color: #1e2a3e;
        width: 120px;
        display: inline-block;
    }
    
    .image-preview {
        text-align: center;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 5px;
        margin-top: 20px;
    }
    
    .image-preview img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        margin-bottom: 10px;
    }
    
    .current-image {
        background: #e9ecef;
        padding: 15px;
        border-radius: 5px;
        text-align: center;
    }
    
    .btn-fetch {
        background: #5cb85c;
        border: none;
        transition: all 0.3s;
    }
    
    .btn-fetch:hover {
        background: #4cae4c;
        transform: translateY(-2px);
    }
    
    .btn-upload {
        background: #337ab7;
        border: none;
        transition: all 0.3s;
    }
    
    .btn-upload:hover {
        background: #286090;
        transform: translateY(-2px);
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #1e2a3e;
        border-left: 4px solid #337ab7;
        padding-left: 10px;
    }
    
    .badge-prisoner {
        background: #d9534f;
        font-size: 14px;
        padding: 5px 10px;
        margin-left: 10px;
    }
</style>
</head>
<body>

<div class="container" style="margin-top:30px; margin-bottom: 50px;">

    <!-- Return Home Button -->
    <div class="row">
        <div class="col-md-12">
            <a href="index3.php" class="btn btn-primary" style="margin-bottom:20px;">
                <i class="fa fa-home"></i> Return to Home
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="prisoner-card">
                <div class="prisoner-header">
                    <h3><i class="fa fa-camera"></i> Prisoner Image Management</h3>
                    <p>Upload and manage prisoner photographs for identification</p>
                </div>
                
                <div class="prisoner-info">
                    <!-- Step 1: Enter Prisoner ID -->
                    <div class="section-title">
                        <i class="fa fa-search"></i> Step 1: Find Prisoner
                    </div>
                    
                    <form method="POST" action="" style="margin-bottom: 20px;">
                        <div class="form-group">
                            <label><i class="fa fa-id-card"></i> Enter Prisoner ID:</label>
                            <div class="input-group">
                                <input type="number" name="prisoner_id" class="form-control" placeholder="e.g., 1001" value="<?php echo htmlspecialchars($prisoner_id); ?>" required>
                                <span class="input-group-btn">
                                    <button type="submit" name="fetch_prisoner" class="btn btn-fetch">
                                        <i class="fa fa-search"></i> Fetch Prisoner
                                    </button>
                                </span>
                            </div>
                            <small class="help-block"><i class="fa fa-info-circle"></i> Enter the unique Prisoner ID to load prisoner information</small>
                        </div>
                    </form>
                    
                    <?php if($upload_msg != ""): ?>
                        <?php echo $upload_msg; ?>
                    <?php endif; ?>
                    
                    <!-- Step 2: Display Prisoner Info -->
                    <?php if($prisoner_info): ?>
                    <div class="section-title" style="margin-top: 20px;">
                        <i class="fa fa-user"></i> Step 2: Prisoner Information
                    </div>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="fa fa-hashtag"></i> Prisoner ID:</strong> <?php echo htmlspecialchars($prisoner_info['prison_ID']); ?></p>
                                <p><strong><i class="fa fa-user"></i> Full Name:</strong> <?php echo htmlspecialchars($prisoner_info['prison_fname'] . " " . $prisoner_info['prison_mname'] . " " . $prisoner_info['prison_lname']); ?></p>
                                <p><strong><i class="fa fa-calendar"></i> Age:</strong> <?php echo htmlspecialchars($prisoner_info['prison_age']); ?> years</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fa fa-venus-mars"></i> Gender:</strong> <?php echo htmlspecialchars($prisoner_info['prison_gen']); ?></p>
                                <p><strong><i class="fa fa-map-marker"></i> Address:</strong> <?php echo htmlspecialchars($prisoner_info['prison_add']); ?></p>
                                <p><strong><i class="fa fa-gavel"></i> Severity:</strong> 
                                    <?php 
                                    $severity = isset($prisoner_info['criminal_severity']) ? $prisoner_info['criminal_severity'] : 'Not Set';
                                    $badge_class = '';
                                    if($severity == 'Low') $badge_class = 'label-success';
                                    elseif($severity == 'Medium') $badge_class = 'label-warning';
                                    elseif($severity == 'High') $badge_class = 'label-danger';
                                    else $badge_class = 'label-default';
                                    ?>
                                    <span class="label <?php echo $badge_class; ?>"><?php echo $severity; ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Image Display -->
                    <div class="section-title">
                        <i class="fa fa-image"></i> Current Photograph
                    </div>
                    
                    <div class="current-image">
                        <?php if($existing_image && file_exists($existing_image)): ?>
                            <img src="<?php echo $existing_image; ?>" alt="Prisoner Photo" style="max-width: 200px; max-height: 200px; border-radius: 10px;">
                            <p style="margin-top: 10px;">
                                <i class="fa fa-check-circle" style="color: #5cb85c;"></i> 
                                Photo uploaded on: <?php echo date("F j, Y", filemtime($existing_image)); ?>
                            </p>
                            <small class="text-muted">Current photo will be replaced when you upload a new one.</small>
                        <?php else: ?>
                            <div style="padding: 40px; background: #e0e0e0; border-radius: 10px;">
                                <i class="fa fa-user-circle" style="font-size: 80px; color: #999;"></i>
                                <p style="margin-top: 10px;">No photo uploaded yet for this prisoner.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Step 3: Upload Image -->
                    <div class="section-title" style="margin-top: 20px;">
                        <i class="fa fa-upload"></i> Step 3: Upload New Photograph
                    </div>
                    
                    <form enctype="multipart/form-data" method="post" action="">
                        <input type="hidden" name="prisoner_id" value="<?php echo $prisoner_id; ?>">
                        
                        <div class="form-group">
                            <label><i class="fa fa-file-image-o"></i> Select Image File:</label>
                            <input type="file" name="uploadedimage" class="form-control" accept="image/jpeg,image/png,image/gif,image/bmp" required>
                            <small class="help-block">
                                <i class="fa fa-info-circle"></i> 
                                Allowed formats: JPG, PNG, GIF, BMP. Max size: 5MB. Recommended size: 300x300 pixels.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="upload_image" class="btn btn-upload btn-block">
                                <i class="fa fa-cloud-upload"></i> Upload Image for Prisoner ID: <?php echo $prisoner_id; ?>
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
// Image preview functionality
document.querySelector('input[name="uploadedimage"]')?.addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Check if preview container exists, if not create it
            let previewContainer = document.querySelector('.image-preview');
            if (!previewContainer) {
                previewContainer = document.createElement('div');
                previewContainer.className = 'image-preview';
                previewContainer.innerHTML = '<h5><i class="fa fa-eye"></i> Image Preview:</h5><img id="preview-img" style="max-width: 200px; max-height: 200px; border-radius: 10px;"><p class="text-muted" style="margin-top: 5px;"><small>This is how the image will look after upload</small></p>';
                document.querySelector('.form-group:last-of-type').after(previewContainer);
            }
            const previewImg = document.getElementById('preview-img');
            if (previewImg) {
                previewImg.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    }
});

// Form validation
document.querySelector('form[enctype="multipart/form-data"]')?.addEventListener('submit', function(e) {
    const fileInput = document.querySelector('input[name="uploadedimage"]');
    if (fileInput && fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const fileSize = file.size / 1024 / 1024; // in MB
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'];
        
        if (fileSize > 5) {
            alert('File size too large. Please upload an image less than 5MB.');
            e.preventDefault();
            return false;
        }
        
        if (!validTypes.includes(file.type)) {
            alert('Invalid file type. Please upload JPG, PNG, GIF, or BMP images only.');
            e.preventDefault();
            return false;
        }
    }
});
</script>

</body>
</html>