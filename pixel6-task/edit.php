<?php
session_start();
include 'connect.php';

// Check if id is provided
if (!isset($_GET['id'])) {
  header("Location: submission.php");
  exit;
}

$id = $_GET['id'];

// Fetch current animal data
$sql = "SELECT * FROM animals WHERE id='$id'";
$result = $connection->query($sql);

if ($result->num_rows == 0) {
  echo "Animal not found!";
  exit;
}

$animal = $result->fetch_assoc();

// If form submitted
if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $category = $_POST['category'];
  $description = $_POST['des'];
  $life = $_POST['life'];

  // Handle file upload
  $photo = $animal['image']; // keep old photo if not updated
  if (!empty($_FILES['photo']['name'])) {
    $photo = $_FILES['photo']['name'];
    $path = "uploads/" . $photo;
    move_uploaded_file($_FILES['photo']['tmp_name'], $path);
  }

  // Update query
  $update = "UPDATE animals SET 
                name='$name',
                category='$category',
                image='$photo',
                description='$description',
                life_expectancy='$life'
               WHERE id='$id'";

  if ($connection->query($update)) {
    $_SESSION['message'] = "Animal updated successfully!";
    header("Location: index.php");
    exit;
  } else {
    echo "Error updating animal: " . $connection->error;
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      background-color: #f5f7fb;
      font-family: 'Poppins';
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      width: 100%;
      max-width: 700px;
      background-color: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.2);
      margin-top: 20px;
    }

    h1 {
      color: black;
    }

    label {
      color: #343333d3;
    }

    input[type=text],
    select,
    textarea,
    input[type=file] {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    button {
      background-color: #175bb5;
      color: #ffffff;
      border: none;
      border-radius: 5px;
      padding: 2%;
      
    }

    button:hover {
      background-color: #366fba;
    }

    .edit-btn{
      text-decoration: none; /*remove underline */
      background-color: #6c757d;
      color: #ffffff;
      border: none;
      border-radius: 5px;
      padding: 2%;
    }

   
  </style>
</head>

<body>
  <div class="container">
    <h1>Edit Animal Information</h1>
    <form method="POST" enctype="multipart/form-data">
      <label for="name">Name Of The Animal:</label>
      <input type="text" id="name" name="name" value="<?php echo $animal['name']; ?>" required>

      <label>Category:</label>
      <input type="radio" id="h" name="category" value="herbivores" <?php if ($animal['category'] == 'herbivores')
        echo 'checked'; ?>> Herbivores
      <input type="radio" id="o" name="category" value="omnivores" <?php if ($animal['category'] == 'omnivores')
        echo 'checked'; ?>> Omnivores
      <input type="radio" id="c" name="category" value="carnivores" <?php if ($animal['category'] == 'carnivores')
        echo 'checked'; ?>> Carnivores
      <br><br>

      <label>Photo Upload:</label>
      <input type="file" id="photo" name="photo">
      <?php if ($animal['image'] != ''): ?>
        <p>Current Photo: <img src="uploads/<?php echo $animal['image']; ?>" width="100"></p>
      <?php endif; ?>

      <label>Description:</label>
      <textarea name="des" id="des" required><?php echo $animal['description']; ?></textarea>

      <label>Life expectancy:</label>
      <select id="life" name="life" required>
        <option value="0-1 year" <?php if ($animal['life_expectancy'] == '0-1 year')
          echo 'selected'; ?>>0-1 year</option>
        <option value="1-5 years" <?php if ($animal['life_expectancy'] == '1-5 years')
          echo 'selected'; ?>>1-5 years
        </option>
        <option value="5-10 years" <?php if ($animal['life_expectancy'] == '5-10 years')
          echo 'selected'; ?>>5-10 years
        </option>
        <option value="10+ years" <?php if ($animal['life_expectancy'] == '10+ years')
          echo 'selected'; ?>>10+ years
        </option>
      </select>

      <button type="submit" name="submit">Update</button>
        <a href="index.php" class="edit-btn">Back</a>
    </form>
  </div>
</body>

</html>