<?php include('../components/connect.php'); 
    if (isset($_COOKIE['admin_id'])) {
        $admin_id =  $_COOKIE['admin_id'];

    }else {
        $admin_id = '';
       header('location:login.php'); 
    }
    

    if (isset($_POST['submit'])) {
        $id = create_unique_id();
        $name = $_POST['name'];
        $name = filter_var($name,FILTER_SANITIZE_STRING);
        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass,FILTER_SANITIZE_STRING);
        $c_pass = sha1($_POST['c_pass']);
        $c_pass = filter_var($c_pass,FILTER_SANITIZE_STRING);
                   
        $select_admins = $conn->prepare("SELECT * FROM admins WHERE  name = ?");
        $select_admins->execute([$name]);
        if ($select_admins->rowCount() >  0) {
            $warning_msg[] =  "Nome de usuário ja utlizado";
        }else {
            if ($pass != $c_pass) {
                $warning_msg[] = "as senhas não são iguais";
            }else{
                $insert_admin = $conn->prepare("INSERT INTO admins (id,name,password) VALUES (?,?,?)");
                $insert_admin->execute([$id,$name,$c_pass]);
                $success_msg[] = "administrador criado com sucesso!";
            }
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
    <title>Registro</title>
</head>
<body>
    <?php include('../components/admin_header.php'); ?>
    <div class="form-container">
        <form action="" method="post">
            <h3>Nova Conta</h3>
            <input type="text" name="name" placeholder="nome" class="box" required oninput="this.value  = this.value.replace(\/s\g,'')">
            <input type="password" name="pass" placeholder="senha" class="box" required oninput="this.value  = this.value.replace(\/s\g,'')">
            <input type="password" name="c_pass" placeholder="comfirme a senha" class="box" required oninput="this.value  = this.value.replace(\/s\g,'')">
            <input type="submit" value="Registrar agora" class="btn" name="submit">
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../assets/js/admin_script.js"></script>
    <?php
        include('../components/message.php'); 
     ?>
</body>
</html>