<?php
    include('../components/connect.php');
     if (isset($_COOKIE['admin_id'])) {
        $admin_id =  $_COOKIE['admin_id'];

    }else {
        $admin_id = '';
       header('location:login.php'); 
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
    <title>Dashboard</title>
</head>
<body>
    <?php include('../components/admin_header.php'); ?>
    <section class="dashboard">
        <h1 class="heading">Dashboard</h1>
        <div class="box-container">
            <div class="box">
                    <?php
                        $select_profile = $conn->prepare("SELECT * FROM admins WHERE id  = ?");
                        $select_profile->execute([$admin_id]);
                        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC); 
                    ?>
                    <h3>Bem Vindo </h3>
                    <p><?= $fetch_profile['name'] ?></p>
                    <a href="update.php" class="btn" >Atualizar perfil</a>
            </div>
            <div class="box">
                <?php
                        $select_bookings = $conn->prepare("SELECT * FROM bookings");
                        $select_bookings->execute();
                        $count_bookings = $select_bookings->rowCount();                       
                 ?>
                 <h3><?= $count_bookings ?></h3>
                 <p>Total de reservas</p>
                 <a href="bookings.php" class="btn">Ver Reservas</a>
            </div>
            <div class="box">
                <?php
                        $select_admins = $conn->prepare("SELECT * FROM admins");
                        $select_admins->execute();
                        $count_admins = $select_admins->rowCount();                       
                 ?>
                 <h3><?= $count_admins ?></h3>
                 <p>Total de Administradores</p>
                 <a href="admins.php" class="btn">Ver Reservas</a>
            </div>
            <div class="box">
                <?php
                        $select_messages = $conn->prepare("SELECT * FROM messages");
                        $select_messages->execute();
                        $count_messages = $select_messages->rowCount();                       
                 ?>
                 <h3><?= $count_messages ?></h3>
                 <p>Total de Mensagens</p>
                 <a href="messages.php" class="btn">Ver Mensagens</a>
            </div>
            <div class="box">
                
                 <h3>Navegação Rápida</h3>
                 <p>Fazer login ou registrar</p>
                 <a href="login.php" class="btn" style="margin-left:1rem;">Login</a>
                 <a href="register.php" class="btn" style="margin-left:1rem;">Registrar</a>
            </div>
        </div>
    </section>
    <script src="../assets/js/admin_script.js"></script>
    
</body>
</html>