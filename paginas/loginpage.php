<?php
session_start();  

include("/var/www/html/Projeto/conecxao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailuser = trim($_POST["emailusername"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, username, email, passwd FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $emailuser);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuarios = $resultado->fetch_assoc();

        if (password_verify($password, $usuarios["passwd"])) {
            $_SESSION["user_id"] = $usuarios["id"];
            $_SESSION["username"] = $usuarios["username"];

            header("Location: http://localhost/Projeto/paginas/mainpage.php");
            exit();
        } else {
            echo "<script>alert('Invalid password');</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body{
            background: linear-gradient(-45deg,rgb(231, 214, 235),rgb(168, 120, 180),rgb(179, 133, 206), #a56eec);
             background-size: 400% 400%;
             animation: gradientMove 12s ease infinite;;
             min-height: 100vh;
             min-width: 100vw;
             display: flex;
             justify-content: center;
             align-items: center;
        }

        @keyframes gradientMove {
         0% { background-position: 0% 30%; }
         50% { background-position: 100% 20%; }
         100% { background-position: 0% 60%; }
        }

        .login-container{
            display: flex;
            flex-wrap: wrap;
            max-width: 1040px;
            width: 90%;
            margin: 20px auto;
            height: auto;
            border-radius: 20px;
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
            padding: 2rem;
            padding-top: 100px;
            font-size: 64px;
        }

        .login-left{
            background-color: #BF5EAF;
            color: #ffffff;
            padding: rem;
            display: flex;
            flex-direction:column;
            justify-content: left;
        }



        .login-right{
            background-color: #ffffff;
            padding: 4rem;
            padding-top: 15vh;
            color: #7E7E7E;
            display: flex;
            flex-direction: column;
            justify-content: left;   
        }
        

        .btn-gradient{
            background: linear-gradient(to right, #a566ab,#c67bb9);
        }
        
        .btn-gradient:hover{
            background: linear-gradient(to right, #934f9e, #b56aae);
            color: white;
        }
        
        .form-control{
            border-radius: 50px;
        }

        .custom-bg-pattern{
          background-image: url(http://localhost/Projeto/assets/imgs/foggy-mountain-and-forest-landscape-illustration-in-the-morning-and-evening-free-vector.jpg);
          background-size:auto;
          background-blend-mode:hard-light ;
          background-position-x: -280px;
          background-repeat: no-repeat;
        }


        h3{
          padding-bottom: 40px;
        }
        
    </style>

    </head>
<body>

      

  <div class="d-flex justify-content-center align-items-center h-100 ">
    <div class="row login-container w-100">

        <!-- Aki já está o lado esquerdo -->
      <div class="col-md-6 login-left custom-bg-pattern">
        <h2 class="fw-bold mb-3'">Welcome Back!</h2>
        <p class="fs-3">You can sign in to access with your existing account</p>
      </div>

        <!-- Aki eu  fiz o lado direito do login -->
      <div class="col-md-6 login-right">
        <h3 class="fw-bold mb-5 display-4 pt-0">Sign in</h3>
        <form action="http://localhost/Projeto/paginas/loginpage.php" method="post">
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Username or email" name="emailusername" required />
          </div>
          <div class="mb-3">
            <input type="password" class="form-control" placeholder="Password" name="password" required/>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="rememberMe" />
              <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <a href="http://localhost/Projeto/paginas/registerpage.php" class="text-muted small">Register</a>
            <a href="#" class="text-muted small">Forgot Password?</a>
          </div>
          <button type="submit" class="btn btn-gradient w-100 rounded-pill">Sign in</button>
        </form>
      </div>
    </div>
  </div>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
  </script>
</body>
</html>