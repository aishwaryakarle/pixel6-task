<?php
session_start();
include 'connect.php';

// Get filter values from the form
$category = isset($_POST['category']) ? $_POST['category'] : '';
$life = isset($_POST['life_expectancy']) ? $_POST['life_expectancy'] : '';
$sort = isset($_POST['sort']) ? $_POST['sort'] : 'newest';



// query
$sql = "SELECT * FROM animals";

// add filters directly in  query
if ($category != '' && $life != '') {
    $sql = "SELECT * FROM animals WHERE category = '$category' AND life_expectancy = '$life'";
} elseif ($category != '') {
    $sql = "SELECT * FROM animals WHERE category = '$category'";
} elseif ($life != '') {
    $sql = "SELECT * FROM animals WHERE life_expectancy = '$life'";
}

// Apply sort
if ($sort == 'newest') {
    $sql .= " ORDER BY created_at DESC";
} elseif ($sort == 'oldest') {
    $sql .= " ORDER BY created_at ASC";
} elseif ($sort == 'name_asc') {
    $sql .= " ORDER BY name ASC";
} elseif ($sort == 'name_desc') {
    $sql .= " ORDER BY name DESC";
}

// Run query
$result = $connection->query($sql);



//Visitor count 
// it check the count if dont then store 0
$check = $connection->query("SELECT * FROM visitors WHERE id = 1");
if ($check->num_rows == 0) {
    $connection->query("INSERT INTO visitors (id, count) VALUES (1,0)");
}

// update visitor count
$connection->query("UPDATE visitors SET count = count + 1 WHERE id = 1");

// fetch visitor count
$result1 = $connection->query("SELECT count FROM visitors WHERE id = 1");
$row = $result1->fetch_assoc();
$visitors = $row['count'];
?>


<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        body {
            background-color: #f5f7fb;
            font-family: poppins;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(15, 15, 15, 0.06);
            padding: 20px;
            margin: 3% auto;
            width: fit-content;
        }

        .head {
            display: flex;
            align-items: center;
            gap: 70%;
        }

        .head p {
            font-size: 15px;
        }

        .demo {
            display: flex;
            gap: 30px;
        }

        .demo label {
            font-size: 16px;
        }

        .demo select {
            border-radius: 5px;
            padding: 4px;
            font-size: 12px;

        }

        .btn {
            margin-top: 2%;
            padding: 5px;
            border-radius: 5px;
            border: none;
            background-color: #2c7be5;
            color: white;
        }

        .btn-add {
            text-decoration: none;/*remove underline */
            font-size: 14px;
        }

        .card {
            margin-top: 3%;
            display: flex;
            align-items: center;
            gap: 20px; /* Space between image and text */
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
            margin-bottom: 15px;
            max-width: 750px;
        }

        .card-left img {
            border-radius: 5px;
            width: 150px;
            height: 150px;
        }

        .card-right {
            text-align: justify;
        }

        .card-right span {
            color: #175bb5;
            border: 1px solid #175bb5;
            border-radius: 10px;
            padding: 3px;
            background-color: #f1f6ff;

        }

        .card-right span,
        .card-right small {
            margin-right: 8px;
        }

        .card-right span,
        .card-right p {
            font-size: 15px;
        }

        .card-right p {
            margin-top: 10px !important;
        }

        .option .btn-edit,
        .option .btn-delete {
            color: #2c7be5;
            font-size: 15px;

        }

        .btn-edit,
        .btn-delete {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="head">
            <h2>ANIMAL</h2>
            <p>Visitor <?php echo $visitors; ?></p>
        </header>
        <section>
            <nav>
                <form method="POST">
                    <div class="demo">
                        <div>
                            <label>Category</label>
                            <select name="category">
                                <option value="">All</option>
                                <option value="herbivores" <?php if ($category == 'herbivores')
                                    echo 'selected'; ?>>
                                    Herbivores</option>
                                <option value="omnivores" <?php if ($category == 'omnivores')
                                    echo 'selected'; ?>>Omnivores
                                </option>
                                <option value="carnivores" <?php if ($category == 'carnivores')
                                    echo 'selected'; ?>>
                                    Carnivores</option>
                            </select>
                        </div>

                        <div>
                            <label>Life expectancy</label>
                            <select name="life_expectancy">
                                <option value="">All</option>
                                <option value="0-1 year" <?php if ($life == '0-1 year')echo'selected'; ?>>0-1 Year</option>
                                <option value="1-5 years" <?php if ($life == '1-5 years')echo 'selected'; ?>>1-5 Years</option>
                                <option value="5-10 years" <?php if ($life == '5-10 years')echo 'selected'; ?>>5-10 Years</option>
                                <option value="10+ years" <?php if ($life == '10+ years')echo 'selected'; ?>>10+ Years</option>
                            </select>
                        </div>

                        <div>
                            <label>Sort</label>
                            <select name="sort">
                                <option value="newest" <?php if ($sort == 'newest')echo 'selected'; ?>>First Newest</option>
                                <option value="oldest" <?php if ($sort == 'oldest')echo 'selected'; ?>>First Oldest</option>
                                <option value="name_asc" <?php if ($sort == 'name_asc')echo 'selected'; ?>>Name A->Z</option>
                                <option value="name_desc" <?php if ($sort == 'name_desc')echo 'selected'; ?>>Name Z->A</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-apply">Apply</button>
                    <a class="btn btn-add" href="submission.php">Add New Animal</a>
                </form>
            </nav>
        </section>

        <section>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="card">
                        <div class="card-left">
                            <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" />
                        </div>

                        <div class="card-right">
                            <h3><?php echo $row['name']; ?></h3>
                            <span><?php echo $row['category']; ?></span>
                            <span><?php echo $row['life_expectancy']; ?></span>
                            <small><?php echo $row['created_at']; ?></small>
                            <p><?php echo $row['description']; ?></p>
                            <div class="option">
                                <a class="btn-edit" href="edit.php?id=<?php echo $row['id']; ?>">Edit |</a>
                                <a class="btn-delete" href="delete.php?id=<?php echo $row['id']; ?>"
                                    onclick="return confirm('are u sure you want to delete this animal?');">Delete</a>
                            </div>
                        </div>
                    </div>

                    <?php
                }
            } else {
                echo "<p>No animals found.</p>";
            }
            ?>

        </section>
    </div>
</body>

</html>