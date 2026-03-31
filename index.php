<?php
session_start();
?>

<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT atbalsts</title>
    <link rel="stylesheet" href="style.css?v=0.1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    <script src="aktualitates.js?v=0.1" defer></script>
</head>

<body>
    <header>
        <a href="./" class="logo">
            <i class="fa fa-server"></i> IT atbalsts
        </a>
        <nav>
            <a href="aktualitates.php" class="btn" data-lang-key="nav_aktualitates">Aktualitātes</a>
            <a data-target="#modal-ticket" class="btn" data-lang-key="nav_ticket">Izveidot pieteikumu</a>
            <a data-target="#modal-pro" class="btn active" data-lang-key="nav_pro">Iegādāties PRO</a>
        </nav>
    </header>

    <section id="home" class="info">
        <div class="content">
            <h1 data-lang-key="hero_title">Uzticams IT atbalsts</h1>
            <p data-lang-key="hero_desc">
                Sniedzam kvalitatīvu IT atbalstu privātpersonām un uzņēmumiem dažādās problēmsituācijās ar datoru, tā perifērijas ierīcēm, programmatūru un internetu gan attālināti, gan dodoties pie klienta klātienē. Iesniedz savu pieteikumu un mēs ar Jums sazināsimies!
            </p>
            <a data-target="#modal-ticket" class="btn active">
                <i class="fa fa-check-circle"></i> <span data-lang-key="nav_ticket">Izveidot pieteikumu</span>
            </a>
        </div>

        <div class="image">
            <img src="images/main.png">
        </div>
    </section>

    <section class="services">
        <h1 data-lang-key="services_title">Mūsu piedāvātie <span>pakalpojumi</span></h1>
        <div class="box-container">
            <div class="box">
                <i class=" fa-solid fa-house-laptop"></i>
                <h2 data-lang-key="service_1">Klientu atbalsts</h2>
            </div>
            <div class="box">
                <i class=" fa-solid fa-computer"></i>
                <h2 data-lang-key="service_2">Datoru remonts</h2>
            </div>
            <div class="box">
                <i class=" fa-solid fa-code"></i>
                <h2 data-lang-key="service_3">Programmu izstrāde</h2>
            </div>
            <div class="box">
                <i class=" fa-solid fa-wrench"></i>
                <h2 data-lang-key="service_4">Sistēmu uzturēšana</h2>
            </div>
        </div>
    </section>

    <section id="home-pro" class="info">
        <div class="image">
            <img src="images/pro.png">
        </div>
        <div class="content">
            <h1 data-lang-key="pro_title">Iegādājies <span>PRO</span> plānu</h1>
            <p data-lang-key="pro_desc">
                Izvēloties maksas plānu PRO, mūsu speciālisti ar Jums sazināsimies daudz ātrāk nekā tas ir bezmaksas versijā. Turklāt visiem klātienes pakalpojumiem tiks piešķirta 50% atlaide. Izmanto iespēju tikai par 49.99 EUR mēnesī!
            </p>
            <a data-target="#modal-pro" class="btn active">
                <i class="fa fa-check-circle"></i> <span data-lang-key="pro_btn_text">Iegādājies jau tagad!</span>
            </a>
        </div>
    </section>

    <section class="blog">
        <h1 data-lang-key="blog_title">IT nozares <span>aktualitātes</span></h1>
        <div class="blog-container" id="aktualitates-container-home">
            
        </div>
    </section>

    <section id="kapec" class="info">
        <div class="content">
            <h1 data-lang-key="why_title">Kāpēc izvēlēties mūs?</h1>
            <p data-lang-key="why_desc">
                Mēs nodrošinām ātru, drošu un profesionālu IT atbalstu gan privātpersonām, gan uzņēmumiem, piedāvājot individuālu pieeju katram klientam risinot problēmas iespējami īsākajā laikā.
            </p>

            <br>

            <div class="uzskaitijums">
                <ul>
                    <li><i class="fa fa-check"></i><span data-lang-key="why_1">10+ gadu pieredze IT atblasta jomā</span></li>
                    <li><i class="fa fa-check"></i><span data-lang-key="why_2">Ātra un profesionāla reakcija uz jūsu pieteikumiem</span></li>
                    <li><i class="fa fa-check"></i><span data-lang-key="why_3">Sertificēti un pieredzējuši speciālisti</span></li>
                    <li><i class="fa fa-check"></i><span data-lang-key="why_4">Garantija visiem veiktajiem darbiem</span></li>
                </ul>
            </div>

        </div>
        <div class="image">
            <img src="images/why-us.png">
        </div>
    </section>

    <section class="komanda">
        <h1 data-lang-key="team_title">Mūsu <span>komanda</span></h1>
        <div class="komanda-container">
            <div class="komanda-dalibnieks">
                <img src="images/team/team-1.jpg" alt="darbinieka bilde">
                <h2>Jānis Bērziņš</h2>
                <p><i data-lang-key="role_director">Direktors</i></p>
                <div class="soc-container">
                    <div class="soctikls">
                        <i class="fa-brands fa-facebook"></i>
                        <i class="fa-brands fa-linkedin"></i>
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                </div>
            </div>
            <div class="komanda-dalibnieks">
                <img src="images/team/team-2.jpg" alt="darbinieka bilde">
                <h2>Uldis Kļaviņš</h2>
                <p><i data-lang-key="role_lead">Vadošais IT speciālists</i></p>
                <div class="soc-container">
                    <div class="soctikls">
                        <i class="fa-brands fa-facebook"></i>
                        <i class="fa-brands fa-linkedin"></i>
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                </div>
            </div>
            <div class="komanda-dalibnieks">
                <img src="images/team/team-3.jpg" alt="darbinieka bilde">
                <h2>Andris Ozoliņš</h2>
                <p><i data-lang-key="role_it">IT speciālists</i></p>
                <div class="soc-container">
                    <div class="soctikls">
                        <i class="fa-brands fa-facebook"></i>
                        <i class="fa-brands fa-linkedin"></i>
                    </div>
                </div>
            </div>
            <div class="komanda-dalibnieks">
                <img src="images/team/team-4.jpg" alt="darbinieka bilde">
                <h2>Ilze Eglīte</h2>
                <p><i data-lang-key="role_it_f">IT speciāliste</i></p>
                <div class="soc-container">
                    <div class="soctikls">
                        <i class="fa-brands fa-facebook"></i>
                        <i class="fa-brands fa-linkedin"></i>
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                </div>
            </div>
            <div class="komanda-dalibnieks">
                <img src="images/team/team-5.jpg" alt="darbinieka bilde">
                <h2>Mārtiņš Zariņš</h2>
                <p><i data-lang-key="role_dev">Programmētājs</i></p>
                <div class="soc-container">
                    <div class="soctikls">
                        <i class="fa-brands fa-facebook"></i>
                        <i class="fa-brands fa-linkedin"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="sazinies">
        <h1 data-lang-key="contact_header">Sazinies ar mums:</h1>
        <div class="content">
            <div id="karte">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2200.074025651445!2d21.02424327714048!3d56.535369731445215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46faa7ccad99801d%3A0xa3ec271b48fef50b!2sVentspils%20iela%2051%2C%20Liep%C4%81ja%2C%20LV-3405!5e0!3m2!1slv!2slv!4v1771505605203!5m2!1slv!2slv" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="sazinaasanas">
                <form action="mail.php" method="POST">
                    <input type="text" name="vards" placeholder="Jūsu vārds" data-lang-placeholder="ph_name">
                    <input type="email" name="epasts" placeholder="Jūsu e-pasts" required data-lang-placeholder="ph_email">
                    <textarea name="zinojums" placeholder="Jūsu ziņa" required data-lang-placeholder="ph_msg"></textarea>
                    <button type="submit" name="nosutit" data-lang-key="btn_send">Nosūtīt ziņu</button>
                </form>

            </div>
        </div>
    </section>

    <footer>
        <div class="konteineris-apaksai">
            <div class="nosaukums">
                <h3 data-lang-key="footer_langs">Valodas</h3>
                <ul>
                    <li><a href="#" onclick="setLanguage('lv')"><i class="fa-solid fa-location-pin"></i>Latviski</a></li>
                    <li><a href="#" onclick="setLanguage('en')"><i class="fa-solid fa-location-pin"></i>English</a></li>
                    <li><a href="#" onclick="setLanguage('ru')"><i class="fa-solid fa-location-pin"></i>Русский</a></li>
                </ul>
            </div>
            <div class="nosaukums">
                <h3 data-lang-key="footer_contacts">Kontakti</h3>
                <ul>
                    <li><i class="fa-solid fa-phone"></i>+371 29 999 999</li>
                    <li><i class="fa-solid fa-envelope"></i>it@atbalsts.lv</li>
                    <li><i class="fa-solid fa-location-dot"></i>Ventspils iela 51, Liepāja</li>
                </ul>
            </div>
            <div class="nosaukums">
                <h3 data-lang-key="footer_social">Seko mums</h3>
                <ul>
                    <li><a href="instagram.com"><i class="fa-brands fa-instagram"></i>Instagram</a></li>
                    <li><a href="facebook.com"><i class="fa-brands fa-facebook"></i>Facebook</a></li>
                </ul>
            </div>
        </div>
        <p data-lang-key="footer_copy">Visas autortiesības aizsargātas - IT atbalsts © 2026</p>
    </footer>

    <div class="modal" id="modal-ticket">
        <div class="modal-box">
            <div class="close-modal" data-target="#modal-ticket">
                <i class="fa-solid fa-square-xmark"></i>
            </div>
            <h2 data-lang-key="modal_ticket_title">Izveidot jaunu pieteikumu</h2>
            <form action="pieteikumi.php" method="POST">
                <label data-lang-key="label_name">Vārds:</label>
                <input type="text" name="vards" required>
                <label data-lang-key="label_surname">Uzvārds:</label>
                <input type="text" name="uzvards" required>
                <label data-lang-key="label_email">E-pasts:</label>
                <input type="email" name="epasts" required>
                <label data-lang-key="label_phone">Tālr. nr.:</label>
                <input type="tel" name="talrunis" pattern="[0-9]{8}" required>
                <label data-lang-key="label_desc">Problēma / veicāmā uzdevuma apraksts:</label>
                <textarea name="apraksts" rows="4" required></textarea>
                <button type="submit" name="nosutit" class="btn active" data-lang-key="btn_submit_ticket">Nosūtīt pieteikumu</button>
            </form>
        </div>
    </div>

    <div class="modal" id="modal-pro">
        <div class="modal-box">
            <div class="close-modal" data-target="#modal-pro">
                <i class="fa-solid fa-square-xmark"></i>
            </div>
            <h2 data-lang-key="modal_pro_title">Iegādājies <span>PRO</span> plānu!</h2>
            <div class="buy-pro">
                <p data-lang-key="modal_pro_benefits">Ieguvumi iegādājoties PRO versiju:</p>
                <ul>
                    <li><i class="fa-solid fa-check"></i><span data-lang-key="pro_ben_1">Komuninācija ar klientu dažu minūšu laikā</span></li>
                    <li><i class="fa-solid fa-check"></i><span data-lang-key="pro_ben_2">50% atlaide visiem klātienes pakalpojumiem</span></li>
                    <li><i class="fa-solid fa-check"></i><span data-lang-key="pro_ben_3">Pietikuma statusa un vēstures apkalpošana</span></li>
                </ul>
            </div>
            <div class="buy-pro" data-lang-key="pro_price">
                Cena 49.99 EUR/mēnesī
            </div>

            <a href="payment/checkout.php" class="btn active" data-lang-key="btn_buy">Iegādāties</a>
        </div>
    </div>

    <?php
    if (isset($_SESSION["pazinojums"])):
    ?>
        <div class="modal modal-active" id="modal-message">
            <div class="modal-box">
                <div class="close-modal" data-target="#modal-message">
                    <i class="fa-solid fa-square-xmark"></i>
                </div>
                <div class="notif">
                    <?= $_SESSION["pazinojums"]; ?>
                </div>
            </div>
        </div>
    <?php
        unset($_SESSION['pazinojums']);
    endif;
    ?>
</body>

</html>