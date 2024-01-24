<?php include('../components/connect.php'); 
    if (isset($_COOKIE['admin_id'])) {
        $admin_id =  $_COOKIE['admin_id'];

    }else {
        $admin_id = '';
       header('location:login.php'); 
    }
    
    $select_profile = $conn->prepare("SELECT * FROM admins  WHERE id = ? LIMIT 1");
    $select_profile->execute([$admin_id]);
    $fetch_admin = $select_profile->fetch(PDO::FETCH_ASSOC); 

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
            $name = filter_var($name,FILTER_SANITIZE_STRING);
            if (!empty($name)) {
                $verify_name = $conn->prepare("SELECT * FROM admins WHERE name = ?");
                $verify_name->execute([$name]);
                if ($verify_name->rowCount() > 0) {
                        $warning_msg[] = "Nome de usuário ja existe";
                }else{

                    $update_name = $conn->prepare("UPDATE admins SET name =  ? WHERE id =  ?");
                    $update_name->execute([$name,$admin_id]);
                    $success_msg[] = "Nome de usuário atualizado com sucesso";         
                }
            }
        
        $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
        $prev_pass = $fetch_admin['password'];
        $old_pass = sha1($_POST['old_pass']);
        $old_pass = filter_var($old_pass,FILTER_SANITIZE_STRING);
        $new_pass = sha1($_POST['new_pass']);
        $new_pass = filter_var($new_pass,FILTER_SANITIZE_STRING);
        $c_pass = sha1($_POST['c_pass']);
        $c_pass = filter_var($c_pass,FILTER_SANITIZE_STRING);
        if ($old_pass != $empty_pass) {
            if ($old_pass != $prev_pass) {
                $warning_msg[] = "Senha antiga incorreta";
            }elseif ($c_pass != $new_pass) {
                $warning_msg[] = "As senhas não são iguais";
            }else {
                if ($new_pass != $empty_pass) {
                    $update_pass = $conn->prepare("UPDATE admins SET password =  ? WHERE id =  ?");
                    $update_pass->execute([$new_pass,$admin_id]);
                    $success_msg[] = "Senha atualizada com sucesso"; 
                }else {
                    $warning_msg[] = "Por favor digite uma nova senha";
                }
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
    <div class="form-container" style="min-height:90vh;">
        <form action="" method="post">
            <h3>Atualizar Usuário</h3>
            <input type="text" name="name"  placeholder="<?= $fetch_admin['name'] ?>" class="box"  oninput="this.value  = this.value.replace(\/s\g,'')">
            <input type="password" name="old_pass" placeholder="digite senha antiga" class="box"  oninput="this.value  = this.value.replace(\/s\g,'')">
            <input type="password" name="new_pass" placeholder="diegite a nova senha" class="box"  oninput="this.value  = this.value.replace(\/s\g,'')">
            <input type="password" name="c_pass" placeholder="comfirme a nova senha " class="box"  oninput="this.value  = this.value.replace(\/s\g,'')">
            <input type="submit" value="Atualizar" class="btn" name="submit">
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../assets/js/admin_script.js"></script>
    <?php
        include('../components/message.php'); 
     ?>
</body>
</html>