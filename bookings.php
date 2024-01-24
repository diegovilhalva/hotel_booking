<?php
    include('components/connect.php');
    
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
        
    }else {
        setcookie('user_id',create_unique_id(), time() + 60*60*24*30, '/');
        header('location:index.php');
    }
    if (isset($_POST['cancel'])) {
        $booking_id = $_POST['booking_id'];
        $booking_id = filter_var($booking_id,FILTER_SANITIZE_STRING);

        $verify_booking = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ?");
        $verify_booking->execute([$booking_id]);
        if ($verify_booking->rowCount() > 0) {
            $delete_booking = $conn->prepare("DELETE FROM bookings WHERE booking_id = ?");
            $delete_booking->execute([$booking_id]);
            $success_msg[] = "Reserva cancalada com sucesso!";

        }else {
            $warning_msg[] = "Reserva já cancelada";
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
    <link rel="stylesheet"  href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
    <title>IHotel - Reservas</title>
</head>
<body>
   <?php include('components/user_header.php'); ?>
     
    <section class="bookings">
        <h1 class="heading">Minhas Reservas</h1>
        <div class="box-container">
            <?php 
            $select_bookings = $conn->prepare("SELECT * FROM bookings WHERE user_id = ?");
            $select_bookings->execute([$user_id]); 
            if ($select_bookings->rowCount() > 0) {
                
    
                while($fetch_booking = $select_bookings->fetch(PDO::FETCH_ASSOC)){


            ?>
            <div class="box">
                <p>Nome : <span><?= $fetch_booking['name'] ?></span></p>
                <p>E-mail : <span><?= $fetch_booking['email'] ?></span></p>
                <p>Telefone : <span> (41) <?= $fetch_booking['number'] ?></span></p>
                <p>Nº de Quartos: <span><?= $fetch_booking['rooms'] ?></span></p>
                <p> Data de Entrada: <span><?= date('d/m/Y',  strtotime($fetch_booking['check_in'])); ?></span></p>
                <p> Data de Saída: <span><?= date('d/m/Y',  strtotime($fetch_booking['check_out'])); ?></span></p>
                <p>Nº de Adultos : <span><?= $fetch_booking['adults']?></span></p>
                <p>Nº de Crianças : <span><?= $fetch_booking['childs'] ?></span></p>
                <p>Código da reserva: <span><?= $fetch_booking['booking_id'] ?></span></p>
                <form action="" method="post">
                    <input type="hidden" name="booking_id" value="<?= $fetch_booking['booking_id'] ?>">
                    <input type="submit" value="Cancelar Reserva" class="btn" name="cancel" onclick="return confirm('Deseja Cancelar?')"> 
                </form>
            </div>
            <?php

                        }
                    }else {
                        
                    
            ?>
            <div class="box" style="text-align:center;">
                        <p style="padding-bottom:.5rem; ">Ainda não há reservas</p>
                        <a href="index.php#reservation" class="btn">Fazer uma reserva</a>
            </div>
            <?php }?>


        </div>
    </section>






   <?php include('components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="assets/js/script.js"></script>
    <?php
        include('components/message.php'); 
     ?>
</body>
</html>