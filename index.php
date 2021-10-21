<?php
include_once ("db.php");
session_start();
if($_SERVER['HTTP_HOST']=="www.vocabulaire.ovh" || $_SERVER['HTTP_HOST']=="vocabulaire.ovh"){header('Location: https://exolingo.com');exit();}
include_once ("local_lang.php");
//récupération des infos pour le compteur.
		$ActiviteGlobal=array();
		$sql="SELECT COUNT(*) as num FROM activiteGlobal WHERE 1";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
						$nbreTotalExo=$row["num"];
		$result->close();

		$nbreTotalUsers=array();
		$sql="SELECT COUNT(*) as num,type FROM users WHERE active=1 GROUP BY type";
		$result = $mysqli->query($sql);
		while($row = $result->fetch_assoc())
		{$nbreTotalUsers[$row["type"]]=$row["num"];}

		$result->close();


		$sql="SELECT COUNT(*) as nbreCards FROM cards WHERE 1";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
		$nbreTotalCards=$row["nbreCards"];
		$result->close();
		$connextButtonText=__("Se connecter");
		if(isset($_SESSION["first_name"])){$connextButtonText=__("Salut")." <span class='nameUser'>".$_SESSION["first_name"]."</span>".__("! Clique ici");}
		echo "<script>lang_interface=".json_encode($lang_interface).";</script>";
?>
<!DOCTYPE html>
<html translate="no" lang="<?php echo($lang_interface); ?>">

<head>
	<script data-ad-client="ca-pub-6857139934529342" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
	<meta name="google" content="notranslate">
	<link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="img/favicon-16x16.png" sizes="16x16" />
  <title>ExoLingo</title>
	<script src="js/jquery-3.3.1.min.js"></script>

  <!-- Custom fonts for this theme -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

  <!-- Theme CSS -->
	<link href="css/myStyle.css?ver=<?php echo filemtime('css/myStyle.css');?>" rel="stylesheet">
  <link href="css/freelancer.min.css" rel="stylesheet">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script src="js/cookiesManager.js"></script>
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-140408884-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
		//gtag call moved to cookiePolicyPopUp.php

	</script>
	<!-- <script src="js/confeti.js"></script> -->

</head>
<style>
.feat-item-fo:hover {

    -webkit-box-shadow: 0px 10px 25px rgba(0,0,0,.1);
    -moz-box-shadow: 0px 10px 25px rgba(0,0,0,.1);
    -o-box-shadow: 0px 10px 25px rgba(0,0,0,.1);
    box-shadow: 0px 10px 25px rgba(0,0,0,.1);
    position: relative;
    z-index: 9;
    transform: translate(0,-5px);
    border: 0px solid transparent;}
.feat-item-fo {padding:20px;
    text-align:center;}
.feat-item-fo img{width:70px;}
/*linear-gradient(90deg,#0aff9e,#1eb289)*/
@keyframes zoom {
	0%{transform:scale(1);}
	70%{transform:scale(1.1);}
	100%{transform:scale(1);}
}
.btnStartNow{background: linear-gradient(270deg,var(--primary),var(--info));
    border-radius: 4px;
    color: #fff;
    font-size: 1.3em;
    padding: 17px 27px;
    display: inline-block;
    border-radius:40px;
  border:none;
  margin:50px 30px;
transition:1s;
text-decoration:none;color:white;
text-align:center;
font-size:large-xx;
-webkit-animation: zoom 2s ease 3 forwards;
animation: zoom 2s ease 3 forwards;}

.btnStartNow:hover{background: linear-gradient(270deg,var(--info),var(--primary));transform:scale(1.2);color:white;text-decoration:none;}
.flag{margin:15px;display:inline-block;transition:0.2s;padding:10px;}
.flag{box-shadow:0px 0 2px #e0e0e0;border-radius:5px;}
.flag:hover{box-shadow:0px 0 5px grey;}
.flag img{width:60px; height:40px;box-shadow:0 0 2px grey;}
.conter{color:#e13e5e;background: #f0f0f0;border-radius:10px;margin:20px;font-size:1em;}
.imgConter{background: linear-gradient(0deg,#e13e5e,#e918c2);padding:10px;object-fit:contain;height:70px;border-radius:35px;margin:10px;}
.numconter{font-size:1.5em;font-weight:bold;}
.labelconter{opacity:0.8;color:grey;}
.page-section{padding:2rem 0}
.bg-secondary {
    background-color: white!important;
}
#mainNav .navbar-nav li.nav-item a.nav-link {
    color: #303030;}
#mainNav .navbar-brand {
    color:  #303030;
}
.justifyP{text-align: justify;
  text-justify: inter-word;}
	#mainNav .navbar-nav li.nav-item a.nav-link:active, #mainNav .navbar-nav li.nav-item a.nav-link:focus {
	 color: #303030;
	}
.nameUser{text-transform:capitalize;}
.choixLangue{width: 300px;
    max-width: 100%;
    text-align: left;min-width:100px;position:relative;top:-5px;display:inline-block; border-radius:0 0 30px 30px;text-align:center;z-index:1031;}
    .selectLang ul li{list-style-type: none;}
    .selectLang ul {padding:0;}
#mainNav{box-shadow:0 0 3px grey;}
.tiretteLang{background-color:blue;min-width:100px;transition:1s;z-index:1032;overflow:visible;height:0;right:20px;text-align:center;position:absolute;bottom:0;}
.lang_item{text-decoration:none;color:black;padding:5px; margin:10px;display:inline-block;border-radius:10px;}
.flagStd {width:80px;height:50px;margin:5px;}
@keyframes showFromRight {
	0%{left:200px;opacity:0;}
	100%{left:0;opacity:1;}
}
@keyframes slideRightLeft {
	0%{left:10px;}
	50%{left:0;}
	100%{left:10px;}
}
.imageFunQuiz{-webkit-animation: showFromRight 1s ease 1 forwards;
animation: showFromRight 1s ease 1 forwards;position:relative;}
.choixLang_item{display:inline-block;width:90px;margin:3px;padding:10px;background-color:white;border-radius:5px;text-align:left;}
.choixLang_item:hover{transform:scale(1.1);}
.lang_name{padding-left:10px;text-transform: uppercase;}
.choixLang_item:not(.choixLang_itemActive){box-shadow:0 0 3px grey;}
.choixLang_itemActive{box-shadow:0px 2px 1px grey;}
.arrowright{vertical-align:middle;width:30px;margin:auto 10px;position:relative;
	-webkit-animation: slideRightLeft 2s linear infinite forwards;
	animation: slideRightLeft 2s linear infinite forwards;
animation-delay:3s}
.fondTache{padding-top:130px;background-image:url(img/fond2.svg);background-repeat:no-repeat;background-position:top right;background-size:30%;}
.fondTacheLeft:before{content:"";position:absolute;
padding-top:0px;
background-image:url(img/fond3.svg);
background-repeat:no-repeat;
background-position:200px left;
background-size:15%;
width:100%;
height:100%;
top:80%;
left:0;
}
#canvasConfeti{pointer-events: none;width:100%;top:0;bottom:0;left:0;position:absolute;}
.infoNews{background-color: #e624a1;
    display: block;
    padding: 15px;
    color: white;
    margin-top: -40px;
		font-size:1.2em;text-align:center;padding-top: 53px;}
</style>
<body id="page-top" translate="no">

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg bg-secondary fixed-top" id="mainNav">

    <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="#page-top"><img src="img/logo.svg" width="40px" style="margin-right:30px;">ExoLingo</a>
      <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <?php echo __("Menu");?>
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">

        <ul class="navbar-nav ml-auto">

					<li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="quizEleve.php"><?php echo __("Quiz");?></a>
          </li>

          <li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#conteur"><?php echo __("Communauté");?></a>
          </li>

          <li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#kesako"><?php echo __("Philosophie");?></a>
          </li>

					<!--<li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="./Payments"><?php echo __("Tarifs");?></a>
          </li>-->
          <li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#contact"><?php echo __("Contact");?></a>
          </li>



        </ul>
				<a class="nav-link py-3 px-0 px-rg-0 rounded js-scroll-trigger" href="loginPage.php" style="float:right;margin-left:2rem;"><?php echo $connextButtonText;?></a>
      </div>
			<div class="tiretteLang">
				<div class="choixLangue" onclick="$('.selectLang').slideToggle();">
					<div class="interfaceLangActive"></div>
					<div class="selectLang" style="display:none;">
						<ul class="interfaceLangChoice">
						<ul>
					</div>
			</div>
			</div>
    </div>

  </nav>

<!-- <canvas id="canvasConfeti"></canvas> -->
  <!-- Masthead -->
  <header class="masthead text-black text-center fondTache">
		<!-- <div class="infoNews"><?php echo __("+ de 1.000.000 d'exercices faits depuis septembre 2019 !");?><img src="img/close.png" style="width:30px;float:right;" onclick="$(this).parent().slideUp();"></div> -->
		<div class="container">
			<div class="row">
				<div class="col-md" style="text-align:left;margin-top:40px;">
					<!-- <div class="container d-flex align-items-center flex-column" > -->

			      <!-- Masthead Avatar Image -->
			      <!--<img src="img/logo1.svg" alt="" style="width:200px;max-width:90%;margin:30px 0 50px;">-->

			      <!-- Masthead Heading -->
			      <!--<h1 class="heading mb-0"><?php //echo __("Apprentissage ludique du vocabulaire en classe et à la maison");?></h1>-->


						<h1 class="heading mb-0" style="font-size:3.3rem;"><?php echo __("Vocabulaire en classe et à la maison");?></h1>
			      <!-- Icon Divider -->
			            <!-- Masthead Subheading -->
			      <p class="masthead-subheading font-weight-light mb-0" style="margin:30px 0;font-size:1.3em;opacity:0.7;"><?php echo __("Gardez le contrôle avec ExoLingo. Choisissez quoi et comment apprendre.");?></p>
			      <div style="text-align:center;">
			      	<a href="loginPage.php" class="btnStartNow" style="margin-bottom:10px;"><?php echo __("Commencer maintenant")?><img src="img/right.png" class="arrowright"></a>
							<div style="color:grey;"><?php echo __("C'est gratuit");?></div>
						</div>
				</div>
				<div class="col-md" style="display:flex;">
						<img src="img/screen.svg" class="imageFunQuiz" style="width:800px;max-width:100%;margin:auto;">
			</div>
		</div>
	</div>
	<div class="container" style="margin-top:50px;">
		<h3 style="padding-bottom:10px;text-align:left;"><?php echo __("Je m'amuse avec ...");?></h3>
		<div>
				<a href="setLang.php?target_lang=1" class="lang_item scaleOver" ><div class="flagStd flag_fr"></div><div><?php echo __("le français");?></div></a>
				<a href="setLang.php?target_lang=21" class="lang_item scaleOver" ><div class="flagStd flag_en"></div><div><?php echo __("l'anglais");?></div></a>
				<a href="setLang.php?target_lang=28" class="lang_item scaleOver" ><div class="flagStd flag_de"></div><div><?php echo __("l'allemand");?></div></a>
				<a href="setLang.php?target_lang=42" class="lang_item scaleOver" ><div class="flagStd flag_it"></div><div><?php echo __("l'italien");?></div></a>
				<a href="setLang.php?target_lang=20" class="lang_item scaleOver" ><div class="flagStd flag_nl"></div><div><?php echo __("le néerlandais");?></div></a>
				<a href="setLang.php?target_lang=54" class="lang_item scaleOver" ><div class="flagStd flag_lt"></div><div><?php echo __("le lituanien");?></div></a>
				<a href="setLang.php?target_lang=85" class="lang_item scaleOver" ><div class="flagStd flag_es"></div><div><?php echo __("l'espagnol");?></div></a>
				<a href="setLang.php?target_lang=70" class="lang_item scaleOver" ><div class="flagStd flag_pl"></div><div><?php echo __("le polonais");?></div></a>
				<a href="setLang.php?target_lang=71" class="lang_item scaleOver" ><div class="flagStd flag_pt"></div><div><?php echo __("le portugais");?></div></a>
				<a href="setLang.php?target_lang=74" class="lang_item scaleOver" ><div class="flagStd flag_ru"></div><div><?php echo __("le russe");?></div></a>
				<a href="setLang.php?target_lang=82" class="lang_item scaleOver" ><div class="flagStd flag_sk"></div><div><?php echo __("le slovaque");?></div></a>
				<a href="setLang.php?target_lang=94" class="lang_item scaleOver" ><div class="flagStd flag_tr"></div><div><?php echo __("le turc");?></div></a>
		</div>
		<p style="padding-bottom:10px;text-align:right;"><?php echo __("Si vous souhaitez que nous ajoutions une langue,");?> <a href="#contact"><?php echo __("contactez-moi");?></a></p>
</div>
					<!--<h3 style="padding-bottom:10px;color:white;text-align:right;"><a href="lang.php" style="color:white;"><?php //echo __("... et bien d'autres !");?></a></h3>-->


      <!--<a href="loginPage.php" class="font-weight-light mb-0" style="display:inline-block;font-size:0.8em;text-decoration:underline; color:white;margin:30px 0;font-size:1.3em;opacity:0.7;">Se connecter</a>-->


      <!--<div class="divider-custom divider-light">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon">
          <img src="img/lightWhite.png" width="40px">
        </div>
        <div class="divider-custom-line"></div>
      </div>-->



  </header>

	  <section class="page-section" id="conteur" style="padding-top:0rem;text-align:center;">
    <h3 class="text-center text-secondary mb-0" style="padding-bottom:40px;"><?php echo __("Rejoignez la communauté");?></h3>

    <div class="container">
    <div class="row">
      <div class="col-md-3 ">
        <div class="feat-item-fo conter">
          <img class="imgConter" src="img/statW.png"><div class="numconter"><?php echo number_format ( $nbreTotalExo , 0 , "," ,  " " );?></div><div class="labelconter"><?php echo __("exercices");?></div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="feat-item-fo conter">
          <img class="imgConter" src="img/userProfW.png"><div class="numconter"><?php echo number_format ($nbreTotalUsers['prof'], 0 , "," ,  " ");?></div><div class="labelconter"><?php echo __("professeurs");?></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="feat-item-fo conter">
          <img class="imgConter" src="img/usersW.png"><div class="numconter"><?php echo number_format ($nbreTotalUsers['eleve'], 0 , "," ,  " ");?></div><div class="labelconter"><?php echo __("élèves");?></div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="feat-item-fo conter">
          <img class="imgConter" src="img/cardsW.png"><div class="numconter"><?php echo number_format ($nbreTotalCards, 0 , "," ,  " ");?></div><div class="labelconter"><?php echo __("cartes");?></div>
        </div>
      </div>
    </div>
  </div>
  </section>


  <!-- Portfolio Section -->
  <section class="page-section portfolio" id="kesako">
    <div class="container">

      <!-- Portfolio Section Heading -->
      <h3 class="text-center text-secondary mb-0" style="padding-bottom:40px;"><?php echo __("Philosophie");?></h3>



      <!-- Portfolio Grid Items -->
      <div class="row">
        <div class="col-md-4">
          <div class="feat-item-fo">
            <img src="img/iconContext.png">
            <h4><?php echo __("L'apprenant garde le contrôle");?></h4>
            <p class="justifyP"><?php echo __("L'apprenant garde le contrôle de ce qu'il souhaite apprendre et comment l'apprendre en choisissant ses cartes puis un type de jeu.");?></p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feat-item-fo">
            <img src="img/iconColaboration.png">
            <h4><?php echo __("Collaboratif");?></h4>
            <p class="justifyP"><?php echo __("La création de contenu peut se faire à plusieurs simultanément. ");?><br><?php echo __("Créer de super listes et gagner des points si vos amis les utilisent.");?></p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feat-item-fo">
            <img src="img/iconGame.png">
            <h4><?php echo __("Ludique");?></h4>
            <p class="justifyP"><?php echo __("En plus des révisions sous forme de jeu, gagnez des points et passez des niveaux. Soyez le premier de votre classe dans le tableau d'avancement hebdomadaire.");?></p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feat-item-fo">
            <img src="img/iconLearning.png">
            <h4><?php echo __("Apprendre pour toujours");?></h4>
            <p class="justifyP"><?php echo __("On sait que la méthode la plus efficace est de travailler en espacant les révisions.");?> <br><?php echo __("ExoLingo vous proposera de revoir les cartes que vous êtes sur le point d'oublier.");?></p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feat-item-fo">
            <img src="img/iconVictory.png">
            <h4><?php echo __("Quiz en classe");?></h4>
            <p class="justifyP"><?php echo __("Une activité que les élèves adorent avec ExoLingo. Faite votre évaluation en classe avec les smartphones ou les ordinateurs des élèves. 15 seconde par question... Qui sera le champion ?");?></p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feat-item-fo">
            <img src="img/iconSchool.png">
            <h4><?php echo __("Dans la classe");?></h4>
            <p class="justifyP"><?php echo __("ExoLingo s'intègre parfaitement en classe avec les évaluations et un suivi individualisé des élèves par leurs professeurs.");?></p>
          </div>
        </div>

      </div>
      <!-- /.row -->
        <center><a href="loginPage.php" class="btnStartNow" style=""><?php echo __("Commencer maintenant !");?></a></center>

    </div>

  </section>

  <!-- Contact Section -->
  <section class="page-section" id="contact">
    <div class="container">

      <!-- Contact Section Heading -->
      <h3 class="text-center text-secondary mb-0"><?php echo __("Pour me contacter");?></h3>



      <!-- Contact Section Form -->
      <div class="row">


        <div class="col-lg-8 mx-auto">
          <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19. -->
          <form name="sentMessage" id="contactForm" novalidate="novalidate">
            <div class="control-group">
              <div class="form-group floating-label-form-group controls mb-0 pb-2">
                <label><?php echo __("Prénom");?></label>
                <input class="form-control" id="name" type="text" placeholder="<?php echo __("Nom");?>" required="required" data-validation-required-message="<?php echo __("Please enter your name.");?>">
                <p class="help-block text-danger"></p>
              </div>
            </div>
            <div class="control-group">
              <div class="form-group floating-label-form-group controls mb-0 pb-2">
                <label><?php echo __("Adresse email");?></label>
                <input class="form-control" id="email" type="email" placeholder="<?php echo __("Adresse email");?>" required="required" data-validation-required-message="<?php echo __("Please enter your email address.");?>">
                <p class="help-block text-danger"></p>
              </div>
            </div>

            <div class="control-group">
              <div class="form-group floating-label-form-group controls mb-0 pb-2">
                <label><?php echo __("Message");?></label>
                <textarea class="form-control" id="message" rows="5" placeholder="<?php echo __("Message");?>" required="required" data-validation-required-message="<?php echo __("Please enter a message.");?>"></textarea>
                <p class="help-block text-danger"></p>
              </div>
            </div>
            <br>
            <div id="success"></div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-xl" id="sendMessageButton"><?php echo __("Envoyer");?></button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </section>

  <!-- Footer -->
  <footer class="footer text-center">
    <div class="container">
      <div class="row">
				<ul>
				<li><a href='CGV.php'>les CGU et les CGV</a></li>
				<li><a href='TermsAndConditions.php'>les termes et conditions d'utilisation</a></li>
				</ul>
				<!--<li><?php //echo $local_lang_cause;?></li>
				<li><?php //echo substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,5);?></li>
				<li><?php //echo in_array($local_lang, $acceptLang);?></li>
				<li><?php //echo 'locallang:'.$local_lang;?></li>
				<li><?php //echo 'langinterface:'.$lang_interface;?></li>
				<li><?php //echo 'session locallang:'.$_SESSION['local_lang'];?></li>
				<li><?php //echo 'old session:'.$oldSessionLang;?></li>
         Footer Location
        <div class="col-lg-4 mb-5 mb-lg-0">
          <h4 class="text-uppercase mb-4">Location</h4>
          <p class="lead mb-0">30 Bld Béranger
            <br>37000 Tours</p>
        </div>

        <!-- Footer Social Icons -->


        <!-- Footer About Text -->


      </div>
    </div>
  </footer>
  <!-- Copyright Section -->
  <section class="copyright py-4 text-center text-white">
    <div class="container">
      <small>Copyright &copy; ExoLingo 2019</small>
    </div>
  </section>

  <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
  <div class="scroll-to-top d-lg-none position-fixed ">
    <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top">
      <i class="fa fa-chevron-up"></i>
    </a>
  </div>
	<script>
	function FillInLang(){
		console.log("fillinlang")
		$.getJSON("ajax.php?action=getAllLang", function(result)
		{
			console.log(result);
			$(".interfaceLangActive").html("<div class='choixLang_item choixLang_itemActive'><span class='tinyFlag flag_"+lang_interface+"'></span><span class='lang_name'>"+lang_interface+"</span></div>");
			for(k in result)
			{
			lang_code2=result[k].lang_code2;
			lang_interface_active=result[k].lang_interface;
			if(lang_interface_active==1 && lang_interface!=lang_code2){
				lang_name=result[k].lang_name_Origin;
				if($('interfaceLangChoice_'+lang_code2).length==0)
					{$(".interfaceLangChoice").append('<a href="index.php?lang='+lang_code2+'"><li class="interfaceLangChoice_'+lang_code2+' choixLang_item"><span class="tinyFlag flag_'+lang_code2+'"></span><span class="lang_name">'+lang_code2+'</span></li></a>');
					}
				}
			}
		});
	}
	FillInLang();
	</script>
  <!-- Portfolio Modals -->
  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Contact Form JavaScript -->
  <script src="js/jqBootstrapValidation.js"></script>
  <script src="js/contact_me.js"></script>

  <!-- Custom scripts for this template -->
  <script src="js/freelancer.min.js"></script>
	<?php
	include_once ("cookiePolicyPopUp.php");
	?>
</body>

</html>
