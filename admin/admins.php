<?php
    include('../components/connect.php');
     if (isset($_COOKIE['admin_id'])) {
        $admin_id =  $_COOKIE['admin_id'];

    }else {
        $admin_id = '';
       header('location:login.php'); 
    }

    if (isset($_POST['delete_admin'])) {
        $delete_id =   $_POST['delete_id'];
        $delete_id =  filter_var($delete_id,FILTER_SANITIZE_STRING);
        $verify_delete =  $conn->prepare("SELECT * FROM admins WHERE id = ?");
        $verify_delete->execute([$delete_id]);
        if ($verify_delete->rowCount() > 0) {
            $delete_booking =  $conn->prepare("DELETE FROM admins WHERE id = ?");
            $delete_booking->execute([$delete_id]);
            $success_msg[] = "Administrador  deletado com sucesso";
        }else {
            $warning_msg[] = "Adminstrador já deletado";
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
    <title>Administradores</title>
</head>
<body>
    <?php include('../components/admin_header.php'); ?>
    <section class="grid">
        <h1 class="heading">Gerenciar Administradores</h1>
        <div class="box-container">
            <div class="box" style="text-align:center;">
                <p>Novo Admin</p>
                <a href="register.php" class="btn">registrar </a>
            </div>
            <?php
                $select_admins = $conn->prepare("SELECT * FROM admins");
                $select_admins->execute();
                if ($select_admins->rowCount() > 0) {
                    while ($fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC)) {
                        
               
             ?>
            <div class="box">
               <p>Nome: <span><?= $fetch_admins['name'] ?></span></p>
                
                <form action="" method="post">
                    <input type="hidden" name="delete_id" value="<?=$fetch_admins['id'] ?>">
                    <input type="submit" value="Deletar Admin" name="delete_admin" onclick="return confirm('Deseja mesmo apagar esse admin?')" class="btn">
                </form>
            </div>
             <?php
                   }
                }else {          
              ?>
              <div class="box" style="text-align:center;">
                <p style="padding-bottom:.5rem;">Não há Administradores</p>
                <a href="dashboard.php" class="btn">Voltar para a dashboard</a>
              </div>
              
            <?php
                       } 
             ?>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../assets/js/admin_script.js"></script>
    <?php
        include('../components/message.php'); 
     ?>
</body>
</html>