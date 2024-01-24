<?php include('../components/connect.php'); 
   /* if (isset($_COOKIE['admin_id'])) {
        $admin_id =  $_COOKIE['admin_id'];

    }else {
        $admin_id = '';
       header('location:login.php'); 
    }*/
    

    if (isset($_POST['submit'])) {
        
        $name = $_POST['name'];
        $name = filter_var($name,FILTER_SANITIZE_STRING);
        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass,FILTER_SANITIZE_STRING);
       
                   
        $select_admins = $conn->prepare("SELECT * FROM admins WHERE  name = ? AND password = ? LIMIT 1");
        $select_admins->execute([$name,$pass]);
        $row  = $select_admins->fetch(PDO::FETCH_ASSOC);
        if ($select_admins->rowCount() >  0) {
          setcookie('admin_id',$row['id'],time() + 60*60*24*30,'/');
          header('location:dashboard.php');
          
        }else {
            $warning_msg[] = "Usuário ou senha incorreta";
        }
        
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/admin.css">
    <title>Login</title>
</head>
<body>
   
    <div class="form-container">
        <form action="" method="post" >
            <h3>Seja Bem Vindo Novamente</h3>
            <input type="text" name="name" placeholder="nome" class="box" required oninput="this.value  = this.value.replace(\/s\g,'')">
            <input type="password" name="pass" placeholder="senha" class="box" required oninput="this.value  = this.value.replace(\/s\g,'')">
            
            <input type="submit" value="Entrar" class="btn" name="submit">
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../assets/js/admin_script.js"></script>
    <?php
        include('../components/message.php'); 
     ?>
</body>
</html>