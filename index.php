<?php
    include('components/connect.php');
    
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
        
    }else {
        setcookie('user_id',create_unique_id(), time() + 60*60*24*30, '/');
        header('location:index.php');
    }

    if (isset($_POST['check'])) {
        $check_in = $_POST['check_in'];
        $check_in = filter_var($check_in,FILTER_SANITIZE_STRING);

        $total_rooms =  0;
        
        $check_bookings = $conn->prepare("SELECT *  FROM bookings WHERE  check_in = ?");

        $check_bookings->execute([$check_in]);

        while($fetch_bookings  =  $check_bookings->fetch(PDO::FETCH_ASSOC)){
            $total_rooms += $fetch_bookings['rooms'];
        }
        if ($total_rooms >= 30) {
            $warning_msg[] = 'Não há quartos disponíveis';
        }else {
            $success_msg[] = 'Há quartos disponíveis';
        }

    }

    if (isset($_POST['book'])) { 
        $booking_id =  create_unique_id();   
        $name = $_POST['name'];
        $name = filter_var($name,FILTER_SANITIZE_STRING);
        $email = $_POST['email'];
        $email = filter_var($email,FILTER_SANITIZE_STRING);
        $number = $_POST['number'];
        $number = filter_var($number,FILTER_SANITIZE_STRING);
        $rooms = $_POST['rooms'];
        $rooms = filter_var($rooms,FILTER_SANITIZE_STRING);
        $check_in = $_POST['check_in'];
        $check_in = filter_var($check_in,FILTER_SANITIZE_STRING);
        $check_out = $_POST['check_out'];
        $check_out = filter_var($check_out,FILTER_SANITIZE_STRING);
        $adults = $_POST['adults'];
        $adults = filter_var($adults,FILTER_SANITIZE_STRING);
        $childs = $_POST['childs'];
        $childs = filter_var($childs,FILTER_SANITIZE_STRING);

        $total_rooms =  0;
        
        $check_bookings = $conn->prepare("SELECT *  FROM `bookings` WHERE  check_in = ?");

        $check_bookings->execute([$check_in]);

        while($fetch_bookings  =  $check_bookings->fetch(PDO::FETCH_ASSOC)){
            $total_rooms += $fetch_bookings['rooms'];
        }
        if ($total_rooms >= 30) {
            $warning_msg[] = 'Não há quartos disponíveis';
        }else {
            $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number =  ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
            $verify_bookings->execute([$user_id,$name,$email,$number,$rooms,$check_in,$check_out,$adults,$childs]);
            if ($verify_bookings->rowCount() > 0) {
                $warning_msg[] =  'Quarto já reservado';
            }else{
                $book_room = $conn->prepare("INSERT INTO `bookings` (booking_id,user_id,name,email,number,rooms,check_in,check_out,adults,childs) VALUES (?,?,?,?,?,?,?,?,?,?)");
                $book_room->execute([$booking_id,$user_id,$name,$email,$number,$rooms,$check_in,$check_out,$adults,$childs]);
                $success_msg[] = 'reserva feita com sucesso!';  
            }

        }
    }
    if (isset($_POST['send'])) {
        $id = create_unique_id();
        $name = $_POST['name'];
        $name = filter_var($name,FILTER_SANITIZE_STRING);
        $email = $_POST['email'];
        $email = filter_var($email,FILTER_SANITIZE_STRING);
        $number = $_POST['number'];
        $number = filter_var($number,FILTER_SANITIZE_STRING);
        $message = $_POST['message'];
        $message = filter_var($message,FILTER_SANITIZE_STRING);
        
        $verify_message = $conn->prepare("SELECT * FROM messages WHERE  name = ? AND email = ? AND  number = ? AND message = ?");
        $verify_message->execute([$name,$email,$number,$message]);
        if ($verify_message->rowCount() > 0) {
            $warning_msg[] = 'Mensagem já enviada';
        }else {
            $send_message = $conn->prepare("INSERT INTO messages (id,name,email,number,message) VALUES (?,?,?,?,?)");
            $send_message->execute([$id,$name,$email,$number,$message]);
            $success_msg[] = 'Mensagem enviada com sucesso!';
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
    <title>IHotel</title>
</head>
<body>
   <?php include('components/user_header.php'); ?>
    <section class="home" id="home">
        <div class="home-slider swiper">
        <div class="swiper-wrapper">
            <div class="box swiper-slide">
                <img src="assets/img/home-img-1.jpg" alt=" Home image">
               <div class="flex">
                <h3>Quartos Luxuosos</h3>
                <a href="#availability" class="btn">Ver Disponibildade</a>
               </div>
            </div>
            <div class="box swiper-slide">
                <img src="assets/img/home-img-2.jpg" alt=" Home image">
              <div class="flex">
                <h3>Luxuosa Aréa de Alimentação</h3>
                <a href="#reservation" class="btn">Fazer uma reserva</a>
              </div>
            </div>
            <div class="box swiper-slide">
                <img src="assets/img/home-img-3.jpg" alt=" Home image">
                <div class="flex">
                    <h3>Hall incrível</h3>
                    <a href="#contact" class="btn">Entre em contato conosco</a>
                </div>
            </div>
        </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>
    <section class="availability" id="availability">
        <form action="" method="post">
            <div class="flex">
                <div class="box">
                    <p>Entrada <span>*</span></p>
                    <input type="date" name="check_in" id="" class="input" required>
                </div>
                <div class="box">
                    <p>Saída<span>*</span></p>
                    <input type="date" name="check_out" id="" class="input" required>
                </div>
                <div class="box">
                    <p>Adultos <span>*</span></p>
                    <select name="adults" id="" class="input" required>
                        <option value="1"> 1 Adulto</option>
                        <option value="2"> 2 Adultos</option>
                        <option value="3"> 3 Adultos</option>
                        <option value="4"> 4 Adultos</option>
                        <option value="5"> 5 Adultos</option>
                        <option value="6"> 6 Adultos</option>
                    </select>
                </div>
                <div class="box">
                    <p>Crianças <span>*</span></p>
                    <select name="child" id="" class="input" required>
                        <option value="0"> 0 Criança</option>
                        <option value="1"> 1 Criança</option>
                        <option value="2"> 2 Crianças</option>
                        <option value="3"> 3 Crianças</option>
                        <option value="4"> 4 Crianças</option>
                        <option value="5"> 5 Crianças</option>
                        <option value="6"> 6 Crianças</option>
                    </select>
                </div>
                <div class="box">
                    <p>Quartos <span>*</span></p>
                    <select name="rooms" id="" class="input" required>
                        <option value="1"> 1 Quarto</option>
                        <option value="2"> 2 Quartos</option>
                        <option value="3"> 3 Quartos</option>
                        <option value="4"> 4 Quartos</option>
                        <option value="5"> 5 Quartos</option>
                        <option value="6"> 6 Quartos</option>
                    </select>
                </div>
                <input type="submit" value=" Verificar Disponibildade" name="check" class="btn">
            </div>
           
        </form>
    </section>
    <section class="about" id="about">
        <div class="row">
            <div class="image">
                <img src="assets/img/about-img-1.jpg" alt=" about us image 1">
            </div>
            <div class="content">
                <h3>A melhor equipe</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id eos amet obcaecati numquam libero vitae!</p>
                <a href="#reservation" class="btn">Faça uma reserva</a>
            </div>
        </div>
        <div class="row revers">
            <div class="image">
                <img src="assets/img/about-img-2.jpg" alt=" about us image 1">
            </div>
            <div class="content">
                <h3>Alimentação de Qualidade</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id eos amet obcaecati numquam libero vitae!</p>
                <a href="#contact" class="btn">Entre em contato</a>
            </div>
        </div>
        <div class="row">
            <div class="image">
                <img src="assets/img/about-img-3.jpg" alt=" about us image 1">
            </div>
            <div class="content">
                <h3>Área de Lazer</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id eos amet obcaecati numquam libero vitae!</p>
                <a href="#availability" class="btn">Checar Disponibildade</a>
            </div>
        </div>
    </section>
    <section class="services">
        <div class="box-container">
            <div class="box">
                <img src="assets/img/icon-1.png" alt="services">
                <h3>Comida e bebidas</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum, eius.</p>
            </div>
            <div class="box">
                <img src="assets/img/icon-2.png" alt="services">
                <h3>Área aberta</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum, eius.</p>
            </div>
            <div class="box">
                <img src="assets/img/icon-3.png" alt="services">
                <h3>Vista para o mar</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum, eius.</p>
            </div>
            <div class="box">
                <img src="assets/img/icon-4.png" alt="services">
                <h3>Jardins</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum, eius.</p>
            </div>
            <div class="box">
                <img src="assets/img/icon-5.png" alt="services">
                <h3>Piscinas</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum, eius.</p>
            </div>
            <div class="box">
                <img src="assets/img/icon-6.png" alt="services">
                <h3>Trilhas para a praia</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum, eius.</p>
            </div>
        </div>
    </section>
    <section class="reservation" id="reservation">
     
        <form action="" method="post">
            <h3>Fazer uma reserva</h3>
            <div class="flex">
                <div class="box">
                    <p>Nome <span>*</span></p>
                    <input type="text" name="name" id="name" class="input" placeholder="nome">
                </div>
                <div class="box">
                    <p>E-mail <span>*</span></p>
                    <input type="email" name="email" id="" class="input" placeholder="email">
                </div>
                <div class="box">
                    <p>Telefone <span>*</span></p>
                    <input type="number" name="number"  class="input" required min="0" max="9999999999999" placeholder=" Telefone">
                </div>
                <div class="box">
                    <p>Quartos <span>*</span></p>
                    <select name="rooms" id="" class="input" required>
                        <option value="1"> 1 Quarto</option>
                        <option value="2"> 2 Quartos</option>
                        <option value="3"> 3 Quartos</option>
                        <option value="4"> 4 Quartos</option>
                        <option value="5"> 5 Quartos</option>
                        <option value="6"> 6 Quartos</option>
                    </select>
                </div>
                <div class="box">
                    <p>Entrada <span>*</span></p>
                    <input type="date" name="check_in" id="" class="input" required>
                </div>
                <div class="box">
                    <p>Saída<span>*</span></p>
                    <input type="date" name="check_out" id="" class="input" required>
                </div>
                <div class="box">
                    <p>Adultos <span>*</span></p>
                    <select name="adults" id="" class="input" required>
                        <option value="1"> 1 Adulto</option>
                        <option value="2"> 2 Adultos</option>
                        <option value="3"> 3 Adultos</option>
                        <option value="4"> 4 Adultos</option>
                        <option value="5"> 5 Adultos</option>
                        <option value="6"> 6 Adultos</option>
                    </select>
                </div>
                <div class="box">
                    <p>Crianças <span>*</span></p>
                    <select name="childs" id="" class="input" required>
                        <option value="0"> 0 Criança</option>
                        <option value="1"> 1 Criança</option>
                        <option value="2"> 2 Crianças</option>
                        <option value="3"> 3 Crianças</option>
                        <option value="4"> 4 Crianças</option>
                        <option value="5"> 5 Crianças</option>
                        <option value="6"> 6 Crianças</option>
                    </select>
                </div>
              
                
            </div>
            <input type="submit" value="Reservar" name="book" class="btn">
        </form> 
    </section>
    <section class="gallery" id="gallery">
        <div class=" swiper gallery-slider">
            <div class="swiper-wrapper">
                <img src="assets/img/gallery-img-1.jpg" alt="gallery image" class="swiper-slide">
                <img src="assets/img/gallery-img-2.webp" alt="gallery image" class="swiper-slide">
                <img src="assets/img/gallery-img-3.webp" alt="gallery image" class="swiper-slide">
                <img src="assets/img/gallery-img-4.webp" alt="gallery image" class="swiper-slide">
                <img src="assets/img/gallery-img-5.webp" alt="gallery image" class="swiper-slide">
                <img src="assets/img/gallery-img-6.webp" alt="gallery image" class="swiper-slide">
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
    <section class="contact" id="contact">
        <div class="row">
            <form action="" method="post">
                <h3>Entre em contato conosco</h3>
                <input type="text" name="name" required maxlenght="50" class="box" placeholder="Nome">
                <input type="email" name="email" required class="box" placeholder="E-mail">
                <input type="number" name="number"  class="box" required min="0" max="9999999999999" placeholder=" Telefone">
                <textarea name="message"  class="box" id="" required cols="30" placeholder="Mensagem" rows="10"></textarea>
                <input type="submit" value="Enviar" class="btn" name="send">
            </form>
            <div class="faq">
                <h3 class="title">Peguntas Frequentes</h3>
                <div class="box ">
                    <h3>Como cancelar uma reserva?</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et consequatur repellat non harum recusandae velit.</p>

                </div>
                <div class="box">
                    <h3>Como saber se há vagas?</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et consequatur repellat non harum recusandae velit.</p>
                    
                </div>
                <div class="box">
                    <h3>Quais são as formas de pagamento?</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et consequatur repellat non harum recusandae velit.</p>
                    
                </div>
                <div class="box">
                    <h3>Onde consigo cupoms de de desconto?</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et consequatur repellat non harum recusandae velit.</p>
                    
                </div>
                <div class="box">
                    <h3>O que o é programa de pontos?</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et consequatur repellat non harum recusandae velit.</p>
                    
                </div>
            </div>
        </div>
    </section>
    <section class="reviews" id="reviews">
        <div class="swiper reviews-slider">
            <div class="swiper-wrapper">
                <div class="box swiper-slide ">
                    <img src="assets/img/pic-1.png" alt="customer">
                    <h3>João da Silva</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum aliquam eaque, minus delectus dolore incidunt.</p>
                </div>
                <div class="box swiper-slide">
                    <img src="assets/img/pic-2.png" alt="customer">
                    <h3>Maria Fernanda</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum aliquam eaque, minus delectus dolore incidunt.</p>
                </div>
                <div class="box swiper-slide">
                    <img src="assets/img/pic-3.png" alt="customer">
                    <h3>André Oliveira</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum aliquam eaque, minus delectus dolore incidunt.</p>
                </div>
                <div class="box swiper-slide">
                    <img src="assets/img/pic-4.png" alt="customer">
                    <h3>Mariana Cristina</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum aliquam eaque, minus delectus dolore incidunt.</p>
                </div>
                <div class="box swiper-slide">
                    <img src="assets/img/pic-5.png" alt="customer">
                    <h3>Jamal Abumjara</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum aliquam eaque, minus delectus dolore incidunt.</p>
                </div>
                <div class="box swiper-slide">
                    <img src="assets/img/pic-6.png" alt="customer">
                    <h3>Laura Nakazawa</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum aliquam eaque, minus delectus dolore incidunt.</p>
                </div>
                
            </div>
            <div class="swiper-pagination"></div>
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