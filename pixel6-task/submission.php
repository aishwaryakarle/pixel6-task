<?php
    // Database connection
    session_start();
    include 'connect.php';  
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
            justify-content: center;  /* horizontal center */
            align-items: center;  /* vertical center */
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
            display: inline-block;
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

        .edit-btn {
            text-decoration: none; /*remove underline */
            background-color: #6c757d;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 2%;
        }
    </style>
</head>

<body onload="gen_cap()">
    <div class="container">
        <h1>Animal Information</h1>
        <form method="POST" action="" onsubmit="return check_cap()" enctype="multipart/form-data">


            <label for="name">Name Of The Animal:</label>
            <input type="text" id="name" name="name"></input>

            <label>Category:</label>
            <input type="radio" id="h" name="category" value="herbivores"> Herbivores</input>
            <input type="radio" id="o" name="category" value="omnivores"> Omnivores</input>
            <input type="radio" id="c" name="category" value="carnivores"> Carnivores</input><br><br>

            <label>Photo Upload:</label>
            <input type="file" id="photo" name="photo"></input>

            <label>Description:</label>
            <textarea name="des" id="des"></textarea>

            <label>Life expectancy:</label>
            <select id="life" name="life">
                <option value="0-1 year">0-1 year</option>
                <option value="1-5 years">1-5 years</option>
                <option value="5-10 years">5-10 years</option>
                <option value="10+ years">10+ years</option>
            </select>

            <label id="Captcha">Captcha:</label>
            <input type="text" id="capid" name="capname"></input>


            <button type="submit" name="submit">Submit</button>
            <a href="index.php" class="edit-btn">Back</a>
        </form>
    </div>

    <script>
        function gen_cap() {  //generate captcha
            console.log(Math.random())
            data1 = Math.round(10 * Math.random());
            console.log(data1);
            data2 = Math.round(10 * Math.random());
            console.log(data2);

            str = `Enter ${data1} + ${data2}`
            document.querySelector("#Captcha").innerHTML = str;
            sum = data1 + data2;
            console.log(sum);
        }

        function check_cap() {   //check captcha
            record = document.querySelector("#capid").value;
            console.log(record);
            if (record == sum) {
                alert("animal information is saved..");
                return true;
            }
            else {
                alert("Invalid captcha..You can Try again.");
                gen_cap(); // regenerate captcha
                return false;
            }
        }
    </script>
</body>
</html>

<?php

if (isset($_POST['submit'])) {
   

    // get form values
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['des'];
    $life = $_POST['life'];

    // file upload
    $photo = "";
    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo']['name'];
        $path = "uploads/" . $photo;
        move_uploaded_file($_FILES['photo']['tmp_name'], $path);
    }
    

    $sql_query = "INSERT INTO animals (name, category, image, description, life_expectancy) 
            VALUES ('$name', '$category', '$photo', '$description', '$life')";

     mysqli_query($connection, $sql_query);


    mysqli_close($connection);
}
?>

