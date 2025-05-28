<?php 


include("/var/www/html/Projeto/conecxao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password_raw = $_POST["passwordone"] ?? '';

    if (!empty($username) && !empty($email) && !empty($password_raw)) {
        $passwd = password_hash($password_raw, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (username, email, passwd) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $passwd);

        if ($stmt->execute()) {
            header("location: http://localhost/Projeto/paginas/loginpage.php");
            exit();
        } else {
            echo "Erro: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Preencha todos os campos.";
    }

    $conn->close();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body{
            background-image: url(http://localhost/Projeto/assets/imgs/foggy-mountain-and-forest-landscape-illustration-in-the-morning-and-evening-free-vector.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            animation: gradientMove 48s ease infinite;
            min-height: 100vh;
            min-width: 100vw;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @keyframes gradientMove {
         0% { background-position: 50% 34%; }
         50% { background-position: 100% 20%; }
         100% { background-position: 20% 60%; }
        }

        .login-container{
            margin: 20px auto;
            max-width: 85%;
            max-height: 30%;
            border-radius: 10px;
            overflow: hidden;

        }

        .login-container{
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.6s ease, border 0.6s ease;
            border: 2px solid transparent;
        }

        .login-container:hover{
            transform: scale(1.01);
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);

        }
        h2{
            font-size: 64px;
            padding-top: 10vh;
        }

        .login-left{
            
            background-color: #BF5EAF;
            color: #ffffff;
            padding: 6rem;
            display: flex;
            flex-direction:column;
            justify-content: left;
        }


        .login-right{
            background-color:#ffffff;
            padding: 4rem;
            padding-top: 15vh;
            color: #7E7E7E;
            display: flex;
            flex-direction: column;
            justify-content: left;   
        }

        .btn-gradient{
            background-color:rgb(241, 105, 105);
        }
        
        .btn-gradient:hover{
            background: linear-gradient(to right,rgb(230, 46, 33),rgb(151, 10, 10));
            color: white;
        }
        
        .form-control{
            border-radius: 50px;
        }
        
    </style>

    </head>
<body>

  <div class="d-flex justify-content-center align-items-center h-100">
    <div class="row login-container w-100">
        <!-- Left side -->
       <div class="col-md-6 login-left bs-danger-bg-subtle">
        <h2 class="fw-bold mb-3'">Welcome Back!</h2>
        <p class="fs-3">Here you can Create a new account!</p>
        <a href="http://localhost/Projeto/paginas/loginpage.php" class="btn btn-gradient ">Sing in</a>
        </div>
        

        <!-- Right side -->
        <div class="col-md-6 login-right">
        <h3 class="fw-bold mb-3 display-4 pt-0">Create a new account</h3>
        <form action="registerpage.php" method="post">
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Username" name="username" required />
          </div>

          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Email" name="email" required/>
          </div>

          <div class="mb-3">
            <input type="password" class="form-control" placeholder="Password" name="passwordone" required />
          </div>




          <div class="mb-3">
            <input type="password" class="form-control" placeholder="Repeat Password" name="passwordtwo" required/>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
            </div>
          </div>
          <div class="d-flex justify-content-between">

            <button type="submit" class="btn btn-gradient w-100  ">Register</button>

          </div>
          
        </form>
      </div>
    </div>
  </div>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>