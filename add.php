<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Photo - MY LOVE</title>
    <style>
        body {
            background: linear-gradient(135deg, rgba(184, 51, 106, 0.8) 0%, rgba(139, 30, 71, 0.7) 50%, rgba(200, 66, 124, 0.8) 100%),
                        url("yona.jpg") no-repeat center center / cover;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            color: white;
            overflow-x: hidden;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.3);
            padding: 12px 10px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-size: clamp(0.9rem, 2.5vw, 1.2rem);
            font-weight: bold;
            padding: clamp(8px, 2vw, 12px) clamp(12px, 3vw, 20px);
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: inline-block;
        }

        .navbar a:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .container {
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            width: min(100%, 600px);
            margin: 0 auto;
        }

        h1 {
            color: white;
            font-size: clamp(2rem, 5vw, 3rem);
            margin: 0 0 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .upload-form {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 500px;
        }

        .upload-form input[type="file"] {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            width: 100%;
            box-sizing: border-box;
        }

        .upload-form button {
            background: rgba(255, 105, 180, 0.8);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .upload-form button:hover {
            background: rgba(255, 105, 180, 1);
        }

        .message {
            margin-top: 20px;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.html#memories">Memories</a>
        <a href="gallery.html">Gallery</a>
        <a href="index.html#message">Message</a>
        <a href="index.html#games">Games</a>
        <a href="add.php">Add Photo</a>
    </div>

    <div class="container">
        <h1>Add a New Photo</h1>
        <form class="upload-form" action="add.php" method="post" enctype="multipart/form-data">
            <input type="file" name="photo" accept="image/*" required>
            <button type="submit">Upload Photo</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            $target_file = $target_dir . basename($_FILES["photo"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                echo "<p class='message'>File is not an image.</p>";
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "<p class='message'>Sorry, file already exists.</p>";
                $uploadOk = 0;
            }

            // Check file size (5MB max)
            if ($_FILES["photo"]["size"] > 5000000) {
                echo "<p class='message'>Sorry, your file is too large.</p>";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                echo "<p class='message'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "<p class='message'>Sorry, your file was not uploaded.</p>";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                    echo "<p class='message'>The file ". htmlspecialchars( basename( $_FILES["photo"]["name"])). " has been uploaded successfully!</p>";
                    echo "<p class='message'><a href='gallery.html' style='color: white;'>Go back to Gallery</a></p>";
                } else {
                    echo "<p class='message'>Sorry, there was an error uploading your file.</p>";
                }
            }
        }
        ?>
    </div>
</body>
</html>