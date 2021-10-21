<style>

#input_reponse{text-align: center;
    padding: 10px 30px;
    font-size: 1.2em;
    font-family: caviar;
    margin: 20px 5px;
    background-color: white;
    border: none;
    border-bottom: 2px lightgrey solid;}
#reponse{margin:20px 0;}
#question{margin:20px 0;}
#mot_container{overflow:auto;padding:10px 40px;
background-color:white;border:1px solid lightgrey;border-radius:4px;max-width:80%;}
.repCard:hover{box-shadow:0 0 3px grey;transform:scale(0.9);}
.repCard{box-shadow:0 0 3px grey;}
#correction{display:none;}
#rewardPage{width:500px;
  max-width:100%;text-align:center;
  border: 3px white solid;
}
.coinReward{font-size: 3em;
    font-family: fantasy;}
.dialog{background-color:white;
    padding:30px;
    display: inline-block;
    -webkit-user-select: none;  /* Chrome all / Safari all */
    -moz-user-select: none;     /* Firefox all */
    -ms-user-select: none;      /* IE 10+ */
    user-select: none;          /* Likely future */ }
.word_item{color:white;border-bottom:1px solid lightgrey;}
.highlight{color:black !important;background-color:white;border:none;}
.reponse_item{background-color: white;
    color: black;
    line-height: 30px;
    padding: 20px;
    font-size:1.2em;
    display: inline-block;
    margin: 10px;
    box-shadow:0 0 3px black;transition:0.5s;}
.reponse_item:hover{background-color: var(--mycolor2);}
@-webkit-keyframes fillQCMCase {
  0%{height:100%;}
  100%{height:0;}
}
@keyframes fillQCMCase {
  0%{height:100%;}
  100%{height:0;}
}
.cardQCMfilling{
  position:absolute;
  background-color:var(--mycolor2bis);
  height:1%;
  width:10px;
  bottom:0;
  box-shadow:0 0 2px grey;
  display:inline-block;
  -webkit-animation: fillQCMCase 4s linear 1 forwards;
  animation: fillQCMCase 4s linear 1 forwards;
}
.cardQCMtank{
  position:relative;
  display:inline-block;
  bottom:0px;
  left:-15px;
  width:0px;
  overflow:visible;
}
.cardQCMtankContainer{
  position:relative;
  width:0px;
  height:0px;
  display:inline-block;
}
.tresor_item{margin:20px;}
</style>
<script>
window.onpopstate = function(event) {
  //console.log(document.location ,event.state);
  ini_memory();
};
//Script to randomize position of elements
$.fn.randomize = function(selector){
    (selector ? this.find(selector) : this).parent().each(function(){
        $(this).children(selector).sort(function(){
            return Math.random() - 0.5;
        }).detach().appendTo(this);
    });

    return this;
};
//id_a_travailler_restant+pileglissante+selected_card_done=selected_card
var id_a_travailler_restant=[];
var bilanRep=[];
var selected_card_done=[];
var tik=new Date().getTime();
var tok=0;
//gestion micro
if (typeof webkitSpeechRecognition !== "undefined") {
    var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
    var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList;
    var SpeechRecognitionEvent = SpeechRecognitionEvent || webkitSpeechRecognitionEvent;
}
else{
  $("#BtnProno").hide();
  $("#BtnReconstitution").hide();
}
if(GContent.texte==""){$("#BtnReconstitution").hide();}

//getAllcardInfo then
//suivant(-1);

function init_game()
{
  $(".enteteBtn:not(.enteteGames)").hide();
  $("#XPcontainer").show();
  $(window).off();
  $("#jouerLink").hide();
  selected_card_done=[];
  history.pushState({}, '', "");
  mode = 1;
  if(selected_card.length==0){selectAll();}
  for(i in selected_card)
  {id_a_travailler_restant[i]=selected_card[i];}
  pile_glissante=id_a_travailler_restant.slice(0, 8);
  id_a_travailler_restant=id_a_travailler_restant.slice(pile_glissante.length, id_a_travailler_restant.length);
  $(".progressbarContainer").show();
  $(".buttonRetourList").hide();
  $(".buttonRetourCards").show();
  $(".buttonRetourCards").off();
  $(".buttonRetourCards").on('click',function(){if(typeof(NextTimer)!= 'undefined'){alert("clearTimeOut",NextTimer);clearTimeout(NextTimer);}});
  $("#game_container").removeClass('shift250');
  $("#XPcontainer").removeClass('shift250');
  $("#navRight").hide();
  $(".card_back_btn").html("<a href='#' onclick='window.history.back();'></a>");
  $(".card_back_btn").off();
  $(".card_back_btn").on('click',function(){if(typeof(NextTimer)!= 'undefined'){alert("clearTimeOut",NextTimer);clearTimeout(NextTimer);}});

  bilanRep=[];
  for(gameK in games)
  {bilanRep[games[gameK]]=0;}

  suivant(-1);

}
function suivant(last_card_id)
{
  tik=new Date().getTime();
  if(typeof(NextTimer)!= 'undefined'){clearTimeout(NextTimer);}
  else{NextTimer=0;}
  len=selected_card_done.length;
  nbre_mot_total=selected_card.length;
  if(pile_glissante.length==0){fin();}
  else{
    //selection du prochain card_
    card_id = rand_parmi(pile_glissante.filter(function(elem){return elem!=last_card_id;}));
    if(!card_id){card_id=last_card_id;}
    //Selection du Game
    gamesDoable=games.filter(function(elem){return true;})
    if(cardsById[card_id]["hasAudio"]==0){
      gamesDoable=games.filter(function(elem){return elem!="dictée";})
    }
    if(cardsById[card_id]["sentences"].length==0){
      gamesDoable=gamesDoable.filter(function(elem){return elem!="bazarMot";})
    }
    //if(pile_glissante.length<5){
    //  gamesDoable=gamesDoable.filter(function(elem){return (elem!="QCMmot2image" && elem!="QCMimage2mot");})
    //}

    game=rand_parmi(gamesDoable);
    if(!game){
      removeElem(card_id,pile_glissante);
      ruissellement();
      suivant(last_card_id);
    }
    else if(game=="discover")
    {
     affichage("discover",card_id);
    }
    else
    {
     affichage(game,card_id);
    }
  }
}
function affichage(game,card_id)
{
console.log("affichage",game,card_id);
//affichage de la consigne
mot=cardsById[card_id].mot.trim();
mot_trad=cardsById[card_id].mot_trad;
hasImage=cardsById[card_id]["hasImage"];
hasAudio=cardsById[card_id]["hasAudio"];
sentences=cardsById[card_id]["sentences"];
sentence=rand_parmi(sentences);
questionCloze='<input type="text" autocomplete="off" id="input_reponse" value="" class="">';
repCloze=mot;
if(sentence)
{
  nbreEtoile=0;
  for(k in sentence)
   {if(sentence[k]=="*"){nbreEtoile++;}}
  if(nbreEtoile==1){sentence+="*";}
  if(nbreEtoile==0){sentence+="<br>*"+mot+"*";}
  repCloze=sentence.match(/\*(.*?)\*/)[0];
  repCloze=repCloze.replace('*','');
  repCloze=repCloze.replace('*','');
  questionCloze=sentence.replace("*"+repCloze+"*",'<input type="text" autocomplete="off" id="input_reponse" value="" class="">',1);
}
//Affichage
$("#game_container").html('');
$("#game_container").append('<div class="consigne"></div>');
$("#game_container").append('<div id="question"></div>');
$("#game_container").append('<div id="correction"></div>');
$("#game_container").append('<div id="reponse"></div>');
$("#game_container").append('<div class="next" title="<?php echo __("Passer à la carte suivante");?>" onclick="suivant('+card_id+');"><?php echo __("Suivant");?></div>');
$("#game_container").append('<div class="skip" title="<?php echo __("Montrer la réponse");?>" onclick="showCorrection('+game+','+card_id+');"><?php echo __("Je donne ma langue au chat");?></div>');
//$("#game_container").append('<div id="mots_restants"></div>');
//Mise en place de la carte dans question
$("#question").html('<div id="card_'+card_id+'" class="card flip-container"></div>');
$("#card_"+card_id).html('<div class="flipper"><div class="front"></div><div class="back"></div></div>')
if(mot_trad!=""){$("#card_"+card_id+">.flipper>.front").append('<span class="mot_trad_card">'+mot_trad+'</span>');}
$("#card_"+card_id+">.flipper>.back").html('<span class="mot_card">'+mot+'</span>');
$("#card_"+card_id+">.flipper>.back").append('<div class="icons_card_container"></div>');
$("#card_"+card_id+">.flipper>.back>.icons_card_container").append('<a target="_blank" class="icon_back google" href="https://www.ecosia.org/images?q='+mot+'"></a>');
if(hasImage>0){$("#card_"+card_id+" > .flipper > .front").css("background-image","url(card_img/card_"+hasImage+".png)");}
else {$("#card_"+card_id+" > .flipper > .front").css("background-image","url(img/default_card.png)");}
if(hasAudio==1){$("#card_"+card_id+">.flipper>.back>.icons_card_container").append('<div class="icon_back icon_audio" onclick="play_audio('+card_id+')"></div>');}
$(".skip").on("click",function(){
  showCorrection(game,card_id);
});
var coefSpeed=1;
switch(game) {
  case "discover":
  $("#XPcontainer").hide();
  $(".consigne").html("<?php echo __("Mémoriser les cartes");?>");
  $(".next,.skip,#reponse").hide();
  $("#game_container").append('<div class="mot_card" style="font-size:3em; margin:5px 0;">'+mot+'</div>');

  if(cardsById[card_id]["hasAudio"]!=0){
    $("#game_container").append('<div class="soundBtn" ><img src="img/haut_parleur.png" style="width:50px;margin:10px;" onclick="play_audio('+card_id+');"></div>');
  }
  if(cardsById[card_id].sentences.length>0)
  {
  $("#game_container").append('<div id="sentencesContainer"></div>');
    for(k in cardsById[card_id].sentences)
    { sentence=cardsById[card_id].sentences[k];
      sentence=sentence.replace("*","<span style='color:orange;'>",1);
      sentence=sentence.replace("*","</span>",1);
      $('#sentencesContainer').append("<div class='one_sentence'>"+sentence+"</div>");
    }
  }
  rkSelectedCard=selected_card.indexOf(card_id);
  rkSelectedCardBefore=(rkSelectedCard-1+selected_card.length)%(selected_card.length);
  rkSelectedCardAfter=(rkSelectedCard+1)%selected_card.length;
  card_idBefore=selected_card[rkSelectedCardBefore];
  card_idAfter=selected_card[rkSelectedCardAfter];
  $("#game_container").append(`<div class="navContainer">
  <span class="navBefore" onclick="affichage('discover',`+card_idBefore+`);"><img src="img/arrow_left.png" style="width:50px;margin:30px;"></span><span class="navAfter"  onclick="affichage('discover',`+card_idAfter+`);"><img src="img/arrow_left.png" style="width:50px;margin:30px;transform: scaleX(-1);"></span></div>`);
  break;
  case "xWord":
  $(".next").hide();
  $(".consigne").html("<?php echo __("Remplir la grille de mots croisés");?>");
  $("#question").html("<div class='center crossword' id='crossword'></div><br/>");
  createXword();
  PlayXword();
  $('#correction').append('<div id="duree"></div>');
  break;
  case "gridLetter":
  $(".next").hide();
  $(".skip").hide();
  $(".consigne").html("<?php echo __("Trouver les mots dans la grille.");?>");
  $("#question").html("<div class='center lettergrid' id='lettergrid'></div><br/>");
  createLetterGrid();
  PlayLetterGrid();
  $('#correction').append('<div id="duree"></div>');
  break;
  case "quizMixLetter":
  coefSpeed=0.2;
  flagFirstAttempt=true;
  $(".consigne").html("<?php echo __("Selectionnez la bonne orthographe.");?>");
  $("#reponse").html('<div class="reponse_item">'+mot+'</div>');
  //mix voyelle
    nbreWA=3;
    wrongAnswerList=generateWrongAnswer(nbreWA,mot);
    //mix consone
      for(W_Answer_rk in wrongAnswerList)
      {
        $("#reponse").append('<div class="reponse_item scaleOver">'+wrongAnswerList[W_Answer_rk]+'</div>');
      }
    $('#question .card').before("<div class='cardQCMtankContainer'><div class='cardQCMtank' style='height:190px;'><div class='cardQCMfilling'></div></div></div>");
    $(".reponse_item").randomize();
  $(".reponse_item").on("click",function(){
    $(".reponse_item").css("box-shadow","none");
    $(".reponse_item").css("background-color","auto");
    if($(this).text()==mot){
      $(this).css("box-shadow","0 0 6px lime");
      if(flagFirstAttempt){bonne_rep(game,card_id);}
      play_audio_coin();
      NextTimer=setTimeout(function(){suivant(card_id);},2000);
    }
    else{
      $(this).css("box-shadow","0 0 6px #FF557F");
      $(this).css("background-color","#FF557F");
      flagFirstAttempt=false;
      mauvaise_rep(game,card_id);
      play_audio_fail();
    }
  });
  break;
  case "QCMimage2mot":
  coefSpeed=0.5;
  $(".consigne").html("<?php echo __("Selectionnez la carte qui correspont à l'image au centre.");?>");
  console.log(game);
  //Mise en place de la grille
  $("#question").html("<span style='display:inline-block'><table id='grilleContainer' style='display:inline-block;'><tr><td id='case_0'></td><td id='case_1'></td><td id='case_2'></td></tr><tr><td id='case_3'></td><td id='case_centre'></td><td id='case_4'></td></tr><tr><td id='case_5'></td><td id='case_6'></td><td id='case_7'></td></tr></table></span>")
  $('#question table').before("<div class='cardQCMtankContainer'><div class='cardQCMtank' style='height:600px;max-height:90vw;'><div class='cardQCMfilling'></div></div></div>");

  //mise en place de la carte au centre
  $("#case_centre").html('<div id="card_'+card_id+'" class="card flip-container"></div>');
  $("#card_"+card_id).html('<div class="flipper"><div class="front"></div><div class="back"></div></div>')
  if(mot_trad!=""){$("#card_"+card_id+">.flipper>.front").append('<span class="mot_trad_card">'+mot_trad+'</span>');}
  $("#card_"+card_id+">.flipper>.back").html('<span class="mot_card">'+mot+'</span>');
  $("#card_"+card_id+">.flipper>.back").append('<div class="icons_card_container"></div>');
  if(hasImage>0){$("#card_"+card_id+" > .flipper > .front").css("background-image","url(card_img/card_"+hasImage+".png)");}
  else {$("#card_"+card_id+" > .flipper > .front").css("background-image","url(img/default_card.png)");}
  //Collecte des réponses possibles (comprenant pile_gissante en priorité)
  pile_reponses=[...pile_glissante];
  if(pile_reponses.length<8){
    for(rkCard in cardsById)
    {
      if(pile_reponses.indexOf(parseInt(rkCard))==-1 && pile_reponses.length<8){pile_reponses.push(parseInt(rkCard));}
    }
  }
  console.log(pile_reponses,pile_glissante);
  //Remplissage de la grille
  for(i in pile_reponses){
    repCard_id=pile_reponses[i];
    repMot=cardsById[repCard_id].mot.trim();
    repMot_trad=cardsById[repCard_id].mot_trad;
    repHasImage=cardsById[repCard_id]["hasImage"];

    $("#case_"+i).html('<div id="repCard_'+repCard_id+'" class="recto repCard card flip-container"></div>');
    $("#repCard_"+repCard_id).html('<div class="flipper"><div class="front"></div><div class="back"></div></div>')
    if(repMot_trad!=""){$("#repCard_"+repCard_id+">.flipper>.front").append('<span class="mot_trad_card">'+repMot_trad+'</span>');}
    $("#repCard_"+repCard_id+">.flipper>.back").html('<span class="mot_card">'+repMot+'</span>');
    $("#repCard_"+repCard_id+">.flipper>.back").append('<div class="icons_card_container"></div>');
    $("#repCard_"+repCard_id+">.flipper>.back>.icons_card_container").append('<a target="_blank" class="icon_back google" href="https://www.ecosia.org/images?q='+repMot+'"></a>');
    if(repHasImage>0){$("#repCard_"+repCard_id+" > .flipper > .front").css("background-image","url(card_img/card_"+repHasImage+".png)");}
    else {$("#repCard_"+repCard_id+" > .flipper > .front").css("background-image","url(img/default_card.png)");}
  }
  var flagFirstAttempt=true;
  //events
  $(".repCard").on("click",function(){
    $(this).removeClass("recto");
    $(this).on("mouseleave",function(){$(this).addClass("recto");$(this).find('.front').css("box-shadow","none");});
    if($(this).attr('id')=='repCard_'+card_id){$(this).find('.front').css("box-shadow","0 0 6px lime");if(flagFirstAttempt){bonne_rep(game,card_id);};NextTimer=setTimeout(function(){suivant(card_id);},2000);}
    else{$(this).find('.front').css("box-shadow","0 0 6px red");flagFirstAttempt=false;mauvaise_rep(game,card_id);play_audio_fail();}
  })
  $('#correction').append('<div id="duree"></div>');
  break;
  case "QCMmot2image":
  coefSpeed=0.2;
  $(".consigne").html("<?php echo __("Selectionnez la carte qui correspont au mot écrit au centre.");?>");
  console.log(game);
  //Mise en place de la grille
  $("#question").html("<span style='display:inline-block'><table id='grilleContainer' class='' style='display:inline-block;'><tr><td id='case_0'></td><td id='case_1'></td><td id='case_2'></td></tr><tr><td id='case_3'></td><td id='case_centre'></td><td id='case_4'></td></tr><tr><td id='case_5'></td><td id='case_6'></td><td id='case_7'></td></tr></table></span>")
  $('#question table').before("<div class='cardQCMtankContainer'><div class='cardQCMtank' style='height:600px;max-height:90vw;'><div class='cardQCMfilling'></div></div></div>");

  //mise en place de la carte au centre
  $("#case_centre").html('<div id="card_'+card_id+'" class="recto card flip-container"></div>');
  $("#card_"+card_id).html('<div class="flipper"><div class="front"></div><div class="back"></div></div>')
  if(mot_trad!=""){$("#card_"+card_id+">.flipper>.front").append('<span class="mot_trad_card">'+mot_trad+'</span>');}
  $("#card_"+card_id+">.flipper>.back").html('<span class="mot_card">'+mot+'</span>');
  $("#card_"+card_id+">.flipper>.back").append('<div class="icons_card_container"></div>');
  if(hasImage>0){$("#card_"+card_id+" > .flipper > .front").css("background-image","url(card_img/card_"+hasImage+".png)");}
  else {$("#card_"+card_id+" > .flipper > .front").css("background-image","url(img/default_card.png)");}
  //Collecte des réponses possibles (comprenant pile_gissante en priorité)
  pile_reponses=[...pile_glissante];//clone les valeurs d'une array dans une autre (pas par référence au 1er niveau)
  if(pile_reponses.length<8){
    for(rkCard in cardsById)
    {
      if(pile_reponses.indexOf(parseInt(rkCard))==-1 && pile_reponses.length<8){pile_reponses.push(parseInt(rkCard));}
    }
  }
  console.log(pile_reponses,pile_glissante);
  //Remplissage de la grille
  for(i in pile_reponses){
    repCard_id=pile_reponses[i];
    repMot=cardsById[repCard_id].mot.trim();
    repMot_trad=cardsById[repCard_id].mot_trad;
    repHasImage=cardsById[repCard_id]["hasImage"];

    $("#case_"+i).html('<div id="repCard_'+repCard_id+'" class="repCard card flip-container"></div>');
    $("#repCard_"+repCard_id).html('<div class="flipper"><div class="front"></div><div class="back"></div></div>')
    if(repMot_trad!=""){$("#repCard_"+repCard_id+">.flipper>.front").append('<span class="mot_trad_card">'+repMot_trad+'</span>');}
    $("#repCard_"+repCard_id+">.flipper>.back").html('<span class="mot_card">'+repMot+'</span>');
    $("#repCard_"+repCard_id+">.flipper>.back").append('<div class="icons_card_container"></div>');
    $("#repCard_"+repCard_id+">.flipper>.back>.icons_card_container").append('<a target="_blank" class="icon_back google" href="https://www.ecosia.org/images?q='+repMot+'"></a>');
    if(repHasImage>0){$("#repCard_"+repCard_id+" > .flipper > .front").css("background-image","url(card_img/card_"+repHasImage+".png)");}
    else {$("#repCard_"+repCard_id+" > .flipper > .front").css("background-image","url(img/default_card.png)");}
  }
  //events
  var flagFirstAttempt=true;
  $(".repCard").on("click",function(){

    $(this).addClass("recto");
    $(this).on("mouseleave",function(){$(this).removeClass("recto");});
    if($(this).attr('id')=='repCard_'+card_id){$(this).find('.back').css("background-color","lime");if(flagFirstAttempt){bonne_rep(game,card_id);}NextTimer=setTimeout(function(){suivant(card_id);},2000);}
    else{$(this).find('.back').css("background-color","red");flagFirstAttempt=false;mauvaise_rep(game,card_id);play_audio_fail();}
  })
  $('#correction').append('<div id="duree"></div>');


  break;
  case "dictée":
    //affichage
    $("body").off();
    $(".consigne").html("<?php echo __("Ecrire le mot après l'avoir entendu.");?>");
    $("#reponse").html(questionCloze);//<input type="text" id="input_reponse" value="">');
    $("#reponse").append('<br><div class="boutton_envoi"><?php echo __("Envoyer");?></div>');
    $("#card_"+card_id+" > .flipper > .front").css("background-image","url(img/haut_parleur_carte.png)");
    $("#card_"+card_id+" > .flipper > .front").off();
    $("#card_"+card_id+" > .flipper > .front").on("click",function(){play_audio(card_id);});
    if(hasImage>0){
    $("#card_"+card_id+" > .flipper > .back").css("background-image","url(card_img/card_"+hasImage+".png)");
    }
    else {
    $("#card_"+card_id+" > .flipper > .back").css("background-image","url(img/default_card.png)");
    }
    //event
    $(".boutton_envoi").on('click',function(){
      repEleve=$('#input_reponse').val();
      verif(game,card_id,repEleve,repCloze);
    });
    $("body").off();
    $("body").on("keyup",function(e){
      var code = e.keyCode || e.which;
      if(code == 13) {
        repEleve=$('#input_reponse').val();
        verif(game,card_id,repEleve,repCloze);
      }
    });
    //action
    play_audio(card_id);
    $('#input_reponse').focus();
    $('#correction').append('<div id="duree"></div>');
  break;
  case "bazarLettre":
    coefSpeed=2;
    $("body").off();
    $(".consigne").html("<?php echo __("Remettre les lettres du mot dans l'ordre.");?>");
    //$("#reponse").append('<input type="text" id="input_reponse" value=""><br><div id="lettre_container"></div>');
    $("#reponse").append(questionCloze+'<br><div id="lettre_container"></div>');
    //$('#question .card').addClass("cardQCMfillingLong");
    $('#question .card').before("<div class='cardQCMtankContainer'><div class='cardQCMtank' style='height:200px;'><div class='cardQCMfilling'></div></div></div>");
//$("#input_reponse").prop("readonly", true);
    $("#lettre_container").slideDown('slow');
    //premiere lettre
    lettre_html='<div class="lettre scaleOver" id='+0+' style="font-size: 1.8em;">'+repCloze[0]+'</div>';
    $("#lettre_container").append(lettre_html);
    //les autres lettres
    for (var i = 1; i < repCloze.length; i++) {
    lettre_html='<div class="lettre scaleOver" id='+i+' style="font-size: 2em;">'+repCloze[i]+'</div>';
    var alea=Math.floor(Math.random()*i);
    if(Math.random()*2<1){$("#"+alea).after(lettre_html);}
    else{$("#"+alea).before(lettre_html);}
    }
    //init variable nouvelles question.
    var rg_lettre_a_trouver=0;
    var fausse_lettre=0;
    //events

    //CLICK ON LETTRE
    $(".lettre").off();
    $(".lettre").on("click", function() {
      var id_lettre=$(this).attr('id');
      if(lettre_lt(repCloze[id_lettre]).toLowerCase()==lettre_lt(repCloze[rg_lettre_a_trouver]).toLowerCase())//SI LA LETTRE TAPE EST BONNE
          ///si la lettre est bonne
        {
            bonne_lettre(card_id,id_lettre,rg_lettre_a_trouver);
            rg_lettre_a_trouver++;
        }
      else//si la lettre est mauvaise
        {
          play_audio_fail();
          $("#"+id_lettre).addClass('bad');
          $("#"+id_lettre).on('animationend', function(e) {
          $("#"+id_lettre).removeClass("bad");
          });
            fausse_lettre++;
        }
        if(fausse_lettre>=3)
          {
          $('.lettre').removeClass('next_letter');
          $('#'+rg_lettre_a_trouver).addClass('next_letter');
          if(fausse_lettre==3){mauvaise_rep(game,card_id);}
          }
        $('#input_reponse').focus();
  });
  //KEYPRESS on INPUT_REPONSE
    $("#input_reponse").off();
    $("#input_reponse").on("input",function(e){//si on a tapé une lettre au clavier
      lettre_tape=$(this).val().substr($(this).val().length-1,$(this).val().length);
      console.log("lettre tapé : "+lettre_tape);
      if(lettre_lt(lettre_tape).toLowerCase()==lettre_lt(repCloze[rg_lettre_a_trouver]).toLowerCase())//SI LA LETTRE TAPE EST BONNE
          //la lettre est la bonne
          { id_lettre=$(".lettre:contains('"+repCloze[rg_lettre_a_trouver]+"'):not(.good):first").attr('id');;
            bonne_lettre(card_id,id_lettre,rg_lettre_a_trouver);
            rg_lettre_a_trouver++;
          }
      else//la lettre n'est pas la bonne
          { $('#input_reponse').addClass('bad');
            $('#input_reponse').on('animationend', function(e) {
              $('#input_reponse').removeClass("bad");
            });
            fausse_lettre++;
          }
          if(fausse_lettre>=3)
            {
              if(fausse_lettre==3){mauvaise_rep(game,card_id);}
            $('.lettre').removeClass('next_letter');
            $('#'+rg_lettre_a_trouver).addClass('next_letter');
            }
    });
    //action
    $('#input_reponse').focus();
    $('#correction').append('<div id="duree"></div>');
  break;
  case "bazarMot":
    //affichage
    $("body").off();
    $(".consigne").html("<?php echo __("Remettre les éléments de la phrase dans l'ordre.");?>");
    $("#question").append('<div id="question_order"></div>');
    //$('#question .card').addClass("cardQCMfillingLong");
    $("#reponse").append('<div id="mot_container"></div>');
    sentence=sentence.replace("*","").replace("*","")
    list_mots=sentence.split(",").join(" ,").split(".").join(" .").split(" ");
    //1er mot
    mot_html='<div class="mot_item scaleOver" id='+0+' style="font-size: 1.8em;">'+list_mots[0]+'</div>';
    $("#mot_container").append(mot_html);
    //les autres lettres
    for (var i = 1; i < list_mots.length; i++) {
    //$('.reponse').val($('.reponse').text()+'-');
    mot_html='<div class="mot_item scaleOver" id='+i+' style="font-size: 2em;">'+list_mots[i]+'</div>';
    var alea=Math.floor(Math.random()*i);
    if(Math.random()*2<1){$("#"+alea).after(mot_html);}
    else{$("#"+alea).before(mot_html);}
    }
    var rg_mot_a_trouver=0;
    //init variable nouvelles question.
    var faux_mot=0;
    //events
    //-------------------------------------------GESTION CLICK SUR CARTE/LETTRE APPARENT
    $("#mot_container").sortable({
      tolerance: "pointer",
  items: ".mot_item",
  update: function (event, ui) {
    var sortedIDs = $(this).sortable( "toArray" );
      console.log(sortedIDs);
      $('.mot_item').removeClass("good_item");
      i=0;while(i<sortedIDs.length && list_mots[sortedIDs[i]].toLowerCase()==list_mots[i].toLowerCase()){$('#'+sortedIDs[i]).addClass('good_item');i++}
      if($('.mot_item:not(.good_item)').length==0){bonne_rep(game,card_id);}
    }});
$('#correction').append('<div id="duree"></div>');
    //action
  break;
  case "prononciation":
    coefSpeed=2;
    $("body").off();
    $(".consigne").html("<?php echo __("Cliquez sur le microphone et lisez le mot.");?>");
    //$('#question .card').addClass("cardQCMfillingLong");
    $('#question .card').before("<div class='cardQCMtankContainer'><div class='cardQCMtank' style='height:190px;'><div class='cardQCMfilling'></div></div></div>");

    //affichage
    $("#reponse").append(questionCloze+'<br><div id="input_audio"></div><div id="microphone"><div id="waves" style="display:none;">'
    +'<div style="animation-delay: -350ms;" class="wave"></div>'
    +'<div style="animation-delay: -400ms;" class="wave"></div>'
    +'<div style="animation-delay: -500ms;" class="wave"></div>'
    +'<div style="animation-delay: -200ms;" class="wave"></div>'
    +'<div style="animation-delay: -300ms;" class="wave"></div>'
    +'<div class="wave"></div>'
    +'</div><div id="recorder"><img src="img/micro.png" width="70px"></div></div>');
    $("#input_reponse").prop("readonly", true);
    //events
    $("body").on('keypress',function(e){if(e.which==32){startMic(game,repCloze,card_id);evt.preventDefault();}});
    $("#recorder").on('click',function(){startMic(game,repCloze,card_id);});
    $('#correction').append('<div id="duree"></div>');
    //action
  break;
  case "validation":
    coefSpeed=2;
    $("body").off();
    //$('#question .card').addClass("cardQCMfillingLong");
    $('#question .card').before("<div class='cardQCMtankContainer'><div class='cardQCMtank' style='height:190px;'><div class='cardQCMfilling'></div></div></div>");

    $(".consigne").html("<?php echo __("Ecrire le mot correspondant à la carte");?>");
    $("#reponse").html(questionCloze);//<input type="text" id="input_reponse" value="">');
    $("#reponse").append('<br><div class="boutton_envoi"><?php echo __("Envoyer");?></div>');
    if (typeof SpeechRecognition !== "undefined")
    {
      $("#input_reponse").after('<div id="microphoneVal" ><div id="waves" style="display:none;width:70px;">'
      +'<div style="animation-delay: -350ms;" class="wave"></div>'
      +'<div style="animation-delay: -400ms;" class="wave"></div>'
      +'<div style="animation-delay: -500ms;" class="wave"></div>'
      +'<div style="animation-delay: -200ms;" class="wave"></div>'
      +'<div style="animation-delay: -300ms;" class="wave"></div>'
      +'<div class="wave"></div>'
      +'</div><div id="recorderVal"><img src="img/micro.png" width="30px"></div></div>');

      //event
      $("#recorderVal").off();
      //$("body").on('keypress',function(e){if(e.which==32){startMicVal(repCloze,card_id);}});
      $("#recorderVal").on('click',function(){startMic(game,repCloze,card_id);});
    }
    $(".boutton_envoi").on('click',function(){
      repEleve=$('#input_reponse').val();
      verif(game,card_id,repEleve,repCloze);
    });
    $("body").off();
    $("body").on("keyup",function(e){
      var code = e.keyCode || e.which;
      if(code == 13) {
        repEleve=$('#input_reponse').val();
        verif(game,card_id,repEleve,repCloze);
      }
    });
    //action
    $('#input_reponse').focus();
    $('#correction').append('<div id="duree"></div>');
  break;
  default:
    $(".consigne").html("--");
}
animationDuration=4+coefSpeed*Math.max(0,repCloze.length-4);
animationDuration+="s";
$('.cardQCMfilling').css("animation-duration",animationDuration);
$('.cardQCMfilling').css("webkit-animation-duration",animationDuration);
update_mots_restant();
$(window).scrollTop(0);


}
function showCorrection(game,card_id){
  console.log("showCorrection",game,card_id);
  $(".next").fadeIn();
  $(".skip").hide();
  $(".duree").show();

  switch(game) {
    case "quizMixLetter":
      $('#repCard_'+card_id).removeClass('recto');
      $('.repCard').off();
    break;
    case "xWord":
      createXword();
      $(".next").hide();
      $("#reponse").hide();
    break;
    case "QCMmot2image":
      $('#repCard_'+card_id).addClass('recto');
      $('.repCard').off();
    break;
    case "QCMimage2mot":
      $('#repCard_'+card_id).removeClass('recto');
      $('.repCard').off();
    break;
    case "dictée":
    $("#card_"+card_id).addClass('recto');
      $("#input_reponse").prop("readonly", true);
      $("#reponse").slideUp();
    break;
    case "bazarLettre":
    $("#card_"+card_id).addClass('recto');
    $("#reponse .boutton_envoi").hide();
    $("#input_reponse").hide();
    $("#lettre_container").hide();
    $("#input_reponse").after("<span style='color:orange;'>"+repCloze+"</span>");
    break;
    case "bazarMot":
    $("#card_"+card_id).addClass('recto');
    $("#mot_container").off();
    sentence=sentence.replace('*','');
    $("#correction").html(sentence);
    break;
    case "prononciation":
    $("#card_"+card_id).addClass('recto');
    $("#microphone").hide();
    $("#input_reponse").hide();
    $("#input_reponse").after("<span style='color:orange;'>"+repCloze+"</span>");
    break;
    case "validation":
    $("#card_"+card_id).addClass('recto');
    //$("#reponse").slideUp();
    $("#reponse #microphoneVal,#reponse .boutton_envoi").hide();
    $("#input_reponse").hide();
    $("#input_reponse").after("<span style='color:orange;'>"+repCloze+"</span>");
    break;
    default:
  }
  $("body").off();
  $("body").on("keyup",function(e){
    var code = e.keyCode || e.which;
    if(code == 13) {
      suivant(card_id);
    }
  });
}
function verif(game,card_id,repEleve,rep)
{
  console.log("verif",game,card_id,repEleve,rep);
  repEleve=lettre_lt(repEleve.toLowerCase());
  rep=lettre_lt(rep.toLowerCase());
  if(repEleve==rep)
  {bonne_rep(game,card_id);}
  else{mauvaise_rep(game,card_id);}
}
function bonne_rep(game,card_id)
{
  switch(game) {
    case 'quizMixLetter':
      exo_id=1;
    break;
    case "QCMmot2image":
      exo_id=10;
    break;
    case "QCMimage2mot":
      exo_id=9;
    break;
    case "dictée":
      exo_id=6;
    break;
    case "bazarLettre":
      exo_id=7;
    break;
    case "bazarMot":
      exo_id=5;
    break;
    case "prononciation":
      exo_id=4;
    break;
    case "validation":
      exo_id=8;
    break;
    default:
  }
  tok=new Date().getTime();
  ResponseTime=tok-tik;
  $('.cardQCMfilling,.cardQCMfillingLong').removeClass('cardQCMfillingLong cardQCMfilling');
  $.getJSON("ajax.php?action=addActiviteGlobal&exo_id="+exo_id+"&card_id="+card_id+"&game="+game+"&correctness=1&responseTime="+ResponseTime, function(result){});


  console.log("bonne_rep",game,card_id);
  //affichage
  $("#input_reponse").prop("readonly", true).addClass('good');
  //si OptimalRD>now pas de coins
  var now=Math.floor(Date.now() / 1000);
  console.log(cardsById[card_id].OptimalRD,now,cardsById[card_id].OptimalRD>now)
  if(cardsById[card_id].OptimalRD<now || cardsById[card_id].OptimalRD==null)
  {bilanRep[game]++;}
  removeElem(card_id,pile_glissante);
  if(selected_card_done.indexOf(card_id)==-1)
  {selected_card_done.push(card_id);}
//s'il reste des id dans la grande pile, on en prend une au hasard que l'on met dans la pile glissante
  ruissellement();
  $(".skip").slideUp();
  showCorrection(game,card_id);
  statusReward="";
  var exo_id=0;
  switch(game) {
    case 'quizMixLetter':
      exo_id=1;
      if(ResponseTime<1000*4){puissance=15;statusReward="<?php echo __("Waow");?>";}else{puissance=10;statusReward="<?php echo __("Trop Lent");?>";}
    break;
    case "QCMmot2image":
      exo_id=10;
      if(ResponseTime<1000*4){puissance=20;statusReward="<?php echo __("Waow");?>";}else{puissance=13;statusReward="<?php echo __("Trop Lent");?>";}
    break;
    case "QCMimage2mot":
      exo_id=9;
      if(ResponseTime<1000*4){puissance=20;statusReward="<?php echo __("Waow");?>";}else{puissance=13;statusReward="<?php echo __("Trop Lent");?>";}
    break;
    case "dictée":
      exo_id=6;
      puissance=30;
    break;
    case "bazarLettre":
      exo_id=7;
      if(ResponseTime<1000*8){puissance=40;statusReward="<?php echo __("Waow");?>";}else if(ResponseTime<1000*20){puissance=30;}else{puissance=20;statusReward="<?php echo __("Pas assez rapide");?>";}
    break;
    case "bazarMot":
      exo_id=5;
      if(ResponseTime<1000*8){puissance=50;statusReward="<?php echo __("Waow");?>";}else{puissance=30;}
    break;
    case "prononciation":
      exo_id=4;
      if(ResponseTime<1000*8){puissance=90;statusReward="<?php echo __("Waow");?>";}else if(ResponseTime<1000*20){puissance=70;}else{puissance=50;statusReward="<?php echo __("Pas assez rapide");?>"}
    break;
    case "validation":
      exo_id=8;
      if(ResponseTime<1000*8){puissance=100;statusReward="<?php echo __("Waow");?>";}else if(ResponseTime<1000*20){puissance=70;}else{puissance=50;statusReward="<?php echo __("Pas assez rapide");?>"}
    break;
    default:
  }
  //if(game=="validation"){
  console.log("CardLearned");
    $.getJSON("ajax.php?action=cardLearned&exo_id="+exo_id+"&card_id="+card_id+"&puissance="+puissance+"&responseTime="+ResponseTime, function(result){

      if(cardsById[card_id].puissance==0){addOneWordInMemory();}

      //$('.nbreCoins').html(result.nbreCoins);
      updateXp(result.nbreCoins);
      //for(k=0;k<result.coins2add;k++){
      //  delay=100*k;
        $('#XPcontainer').append('<div class="animatedXP" style="">+'+result.coins2add+'<img src="img/lightWhite.png" style="width:50px;vertical-align:middle;"><br><span style="font-size:0.8em;">'+statusReward+'</span></div>');
        if(result.bankStatus=="limit"){$(".animatedXP").html("<span style='font-size:0.8em;'><?php echo __("Limite quotidienne atteinte");?></span>");}
        play_audio_coin();
      //}
      $(".animatedXP").on("animationend",function(){$(this).remove();});

      cardsById[card_id].puissance=result.puissance;
      //gestion durée
      // Create a new JavaScript Date object based on the timestamp
      // multiplied by 1000 so that the argument is in milliseconds, not seconds.
      Current_TimeStamp=Math.floor(Date.now() / 1000);
      cardsById[card_id]["LastRD"]=Current_TimeStamp;
      cardsById[card_id]["OptimalRD"]=result.nextOptimalRD;
      Delta=result.nextOptimalRD-Current_TimeStamp;
      console.log(Delta,result.nextOptimalRD,Current_TimeStamp);
      DeltaY=Math.floor(Delta/(365*24*60*60));
      DeltaM=Math.floor((Delta-DeltaY*365*24*60*60)/(30*24*60*60));
      DeltaS=Math.floor((Delta-DeltaY*365*24*60*60-DeltaM*30*24*60*60)/(7*24*60*60));
      DeltaJ=Math.floor((Delta-DeltaY*365*24*60*60-DeltaM*30*24*60*60-DeltaS*7*24*60*60)/(24*60*60));
      DeltaText="";
      if(DeltaJ==1){DeltaText="<?php echo __('En mémoire pour un peu plus de ');?>"+DeltaJ+" <?php echo __("jour");?>";}
      if(DeltaJ>1){DeltaText="<?php echo __('En mémoire pour un peu plus de ');?>"+DeltaJ+" <?php echo __("jours");?>";}
      if(DeltaS==1){DeltaText="<?php echo __('En mémoire pour un peu plus de ');?>"+DeltaS+" <?php echo __("semaine");?>";}
      if(DeltaS>1){DeltaText="<?php echo __('En mémoire pour un peu plus de ');?>"+DeltaS+" <?php echo __("semaines");?>";}
      if(DeltaM!=0){DeltaText="<?php echo __('En mémoire pour un peu plus de ');?>"+DeltaM+" <?php echo __("mois");?>";}
      if(DeltaY!=0){DeltaText="<?php echo __('En mémoire pour un peu plus de ');?>"+DeltaY+" <?php echo __("ans");?>";}

      $("#duree").html("<img src='img/sablier.png' width='50px'>"+DeltaText);
      $("#duree").show();
    });
  //}
}
function mauvaise_rep(game,card_id)
{
  switch(game) {
    case 'quizMixLetter':
      exo_id=1;
    break;
    case "QCMmot2image":
      exo_id=10;
    break;
    case "QCMimage2mot":
      exo_id=9;
    break;
    case "dictée":
      exo_id=6;
    break;
    case "bazarLettre":
      exo_id=7;
    break;
    case "bazarMot":
      exo_id=5;
    break;
    case "prononciation":
      exo_id=4;
    break;
    case "validation":
      exo_id=8;
    break;
    default:
  }
  play_audio_fail();
  console.log("mauvaise_rep",game,card_id);
  $("#input_reponse").addClass('bad');
  $("#input_reponse").on('animationend', function(e) {
  $("#input_reponse").removeClass("bad");
  });
  $.getJSON("ajax.php?action=addActiviteGlobal&exo_id="+exo_id+"&card_id="+card_id+"&game="+game+"&correctness=0", function(result){});
}

function fin()
{
console.log("fin");
//check if tresor available
$.getJSON("ajax.php?action=checkExoTresor", function(result){
  console.log(result);
  tresorData=result.tresorData;

$('body').append(`<div class='fenetreSombre' onclick='$(this).remove();'>
  <div class='fenetreClaire' onclick='event.stopPropagation();' style="width:600px;">
    <img src='img/close.png' class='closeWindowIcon' onclick='$(\".fenetreSombre\").remove();'>
  </div>
</div>`);

$('.fenetreClaire').append(`
  <div class="rewardWindow" style="max-width:90%;margin:auto;text-align:center;">
    <h3 style='width:80%;display:inline-block;text-align:center;'><?php echo __("Bravo !");?></h3>
    <div class="tresorContainer"></div>
  </div>`);

for(tresor_rk in tresorData)
{
  tresor_id=tresorData[tresor_rk].tresor_id;
  tresorValue=tresorData[tresor_rk].value;
  cadeauNum=tresor_id%10;
  $(".tresorContainer").append(`<div class='tresor_item' id='tresor_`+tresor_id+`' onclick="openTresor(`+tresor_id+`,`+tresorValue+`);">
    <img class="imgTresor" src="img/cadeau`+cadeauNum+`.png">
  </div>`);
}
selected_card_Tmp=selectOther(8);
if(selected_card_Tmp.length>0){
  $(".rewardWindow").append(`<button class='BtnStd1' onClick='$(".fenetreSombre").remove();selected_card = [].concat(selected_card_Tmp);init_game();'><?php echo __("Même jeu avec d'autres cartes");?></button>`);
}
harderGame=getHarderGame();
if(harderGame){
  $(".rewardWindow").append(`<button class='BtnStd1' onClick='$(".fenetreSombre").remove();games=["`+harderGame+`"];init_game();'><?php echo __("Mêmes cartes avec des jeux plus difficiles");?></button>`);
}
$(".rewardWindow").append(`<button class='BtnStd1' onClick='$(".fenetreSombre").remove();'><?php echo __("Retour");?></button>`);
});
coinsReward=0;
var flagVal=0;
for(i in bilanRep)
  {
    if(i=="validation"){coinsReward+=3*bilanRep[i];flagVal=1;}
    else if(i=="QCMmot2image" || i=="QCMimage2mot" ){coinsReward+=1*bilanRep[i];}
    else{coinsReward+=2*bilanRep[i];}
  }
if(coinsReward==0){ini_memory();}
else if(coinsReward<10){$('#rewardPage').append("<img src='img/smallcoins.png' width='40%'>");}
else if(coinsReward<50){$('#rewardPage').append("<img src='img/mediumcoins.png' width='50%'>");}
else{$('#rewardPage').append("<img src='img/bigcoins.png' width='60%'>");}

/*$.getJSON("ajax.php?action=addCoins&nbre="+coinsReward, function(result){
  console.log(result);
    $('.coinReward').html(result.coins2add);
    $('.nbreCoins').html(result.nbreCoins);
    for(k=0;k<result.coins2add;k++){
      delay=100*k;
      $('.nbreCoins').parent().append('<div class="animatedCoin" style="animation-delay:'+delay+'ms;"><img src="img/golden_coin.png" width="40px"></div>');
    }
    $(".animatedCoin").on("animationend",function(){$(this).remove();});
    $('#rewardPage').append("<div class='coinReward'>+"+coinsReward+"</div>");

    if(flagVal==0){$('#rewardPage').append("<div class='btnVali btnGoValidation' onclick='$(\".fenetreSombre\").remove();games=[\"validation\"];init_game();'>Validation !</div>");}

});*/
ini_memory();
}
function openTresor(tresor_id,tresorValue)
{
  $("#tresor_"+tresor_id+" .imgTresor").hide(300);
  if(tresorValue==0)
  {$("#tresor_"+tresor_id).html(`<div><?php echo __("C'est vide. Vous aurez plus de chance la prochaine fois");?></div>`);}
  else{
  $("#tresor_"+tresor_id).html(`<div style="font-size:2em;">+`+tresorValue+`<span class='ruby ruby_inline_L'></span></div>`);
  }
  $.getJSON("ajax.php?action=openTresor&tresor_id="+tresor_id, function(result){
    console.log(result);});
}

function getHarderGame()
{//return the next harder game false if valisation.
  var thisLastGame=games[games.length-1];
  if(thisLastGame=="validation"){return false;}
  else if(thisLastGame=="quizMixLetter"){return "QCMmot2image";}
  else if(thisLastGame=="QCMmot2image" || thisLastGame=="QCMimage2mot" || thisLastGame=="dictée"){return "bazarLettre";}
  else {return "validation";}
}
//FUNCTION SPECIFIQUE
function bonne_lettre(card_id,id_lettre,rg_lettre)
{
  $('#input_reponse').val('');
    for (var i = 0; i < repCloze.length; i++) {
    if(i<rg_lettre+1){$('#input_reponse').val($('#input_reponse').val()+repCloze[i]);}
    }
    console.log(rg_lettre);
  rg_lettre++;//on augmente le nombre de lettre d'1
  $("#"+id_lettre).addClass('good');//la lettre devient verte
    if(repCloze.length==rg_lettre)
    {//SI LE MOT EST TROUVE
      bonne_rep("bazarLettre",card_id);
    }
}
nbreCheck=-100;
function escapeRegExp(stg) {
  return stg.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
}
function checkAndReveal(transcript)
{
  nbreCheck++;
  if(nbreCheck>5){transcript="";$(".transcriptEleve").val("");}
  console.log("checkAndReveal",transcript);
  transcript=transcript.toLowerCase();
  transcript=escapeRegExp(transcript);
  //speechResultArray=transcript.match(/([^ ]+)/gi);
  speechResultArray=transcript.split(/([\.| |,|:|;|!|\?|-|_]+)/gm);
    //joinReg='[s]?[\\W]{0,4}';
    if($(".mode").val()=="mots")
    {
      for(k in speechResultArray){
        filteredWordList=WordList.filter(function(word){
          return new RegExp('^[-]?'+speechResultArray[k]+'[x|s]?$','gi').test(word);
        });

        for(r in filteredWordList){
          for(m in ReverseWordList[filteredWordList[r]])
              {$(".word_"+ReverseWordList[filteredWordList[r]][m]).addClass("highlight");}
        }
      }
    }
    else{
      wordsIdFound=[];
        for(k in speechResultArray){
          filteredWordList=WordList.filter(function(word){
            return new RegExp('^[-]?'+speechResultArray[k]+'[x|s]?$','gi').test(word);
          });

          for(r in filteredWordList){
            for(m in ReverseWordList[filteredWordList[r]])
                {
                  var thisId=ReverseWordList[filteredWordList[r]][m];
                  if(wordsIdFound.indexOf(thisId)==-1)
                  {wordsIdFound.push(parseInt(thisId));}
                }
          }
        }
        wordsIdFound=wordsIdFound.sort(function(a, b){return a-b});
        console.log(wordsIdFound);
        var maxLenConsList=0;
        var tmpMaxLenConsList=0;
        var consList=[];
        var tmpConsList=[];
        if(wordsIdFound.length>1){
          maxLenConsList=1;
          consList=[[wordsIdFound[0]]];
        }
        for(k=0;k<wordsIdFound.length-1;k++)
        {
          if(wordsIdFound[k]+1==wordsIdFound[k+1]){
            tmpConsList.push(wordsIdFound[k+1]);
            tmpMaxLenConsList=tmpConsList.length;
            maxLenConsList=consList[0].length;

            console.log(maxLenConsList,tmpMaxLenConsList,consList,tmpConsList);

            if(tmpMaxLenConsList>maxLenConsList){
              maxLenConsList=tmpMaxLenConsList;
              consList=[tmpConsList];
            }
            else if(tmpMaxLenConsList==maxLenConsList)
            {
              consList.push(tmpConsList);
            }
            else{
              console.log(maxLenConsList,consList);
            }
          }
          else{//le suivant n'est pas consecutif
            tmpMaxLenConsList=1;
            tmpConsList=[wordsIdFound[k+1]];
          }
        }
        for(k in consList)
        {
          for(i in consList[k])
          {
            $(".word_"+consList[k][i]).addClass("highlight");
          }
        }
      }



    foundLen=parseInt($(".word_item.highlight").length);
    dialogLen=parseInt($(".word_item").length);
    pcentFound=Math.round((foundLen)*100/dialogLen);
    if(pcentFound>75){$('.word_item').addClass('highlight');pcentFound=100;}
    $(".feedBackFound").html(pcentFound+"%");
    var HLids = [];
    $(".highlight").each(function(){ HLids.push(this.id); });
    CookiesHightLight=JSON.stringify(HLids);
    createCookie('dialog_'+deck_id,CookiesHightLight,365);

}
function startMic(game,mot,card_id)
{

if(game=="prononciation"){record_id="#recorder";output_id="#input_audio";}
else{record_id="#recorderVal";output_id="#input_reponse";}
if(game!='reconstitution'){
  $(record_id).hide();
  $("#waves").show();
  console.log("mot mic :"+mot);
  var grammar = '#JSGF V1.0; grammar phrase; public <phrase> = ' + cardsById[card_id]["mot"] +';';
  var recognition = new SpeechRecognition();
  var speechRecognitionList = new SpeechGrammarList();
  speechRecognitionList.addFromString(grammar, 1);
  recognition.grammars = speechRecognitionList;
  //récupération de la langue de la carte
  //if(cardsById[card_id]["lang"]=='fr'){recognitionLang="fr_FR";}
  //else if(cardsById[card_id]["lang"]=='en'){recognitionLang="en_US";}
  //else{recognitionLang="fr_FR";}
  recognition.lang = cardsById[card_id]["lang_code2_2"];
  recognition.interimResults = false;
  recognition.maxAlternatives = 1;
  recognition.start();

  recognition.onresult = function(event) {
    $(record_id).show();
    $("#waves").hide();
    var speechResult = event.results[0][0].transcript;
    $(output_id).html(speechResult);
    mot_modif=mot;
    if(speechResult.replace(mot_modif.toLowerCase(),"") == speechResult)
    {
    console.log("faux");
    $(output_id).addClass('bad_rep_audio');
    $(output_id).on('animationend', function(e) {
    $(output_id).removeClass("bad_rep_audio");
  });}
    else{bonne_rep(game,card_id);}
  //$("#microphone").html(parseInt(event.results[0][0].confidence*100)+"%");
  }
}
else {//reconstitution
  nbreCheck=0;
  record_id="#recorderRec";
  $("#recorderRec").hide();
  $("#waves").show();
  var grammar = '#JSGF V1.0; grammar phrase; public <phrase> = ;';
  //var recognition = new SpeechRecognition();
  var recognition = new webkitSpeechRecognition();
  //var speechRecognitionList = new SpeechGrammarList();
  var speechRecognitionList = new webkitSpeechGrammarList();
  speechRecognitionList.addFromString(grammar, 1);
  recognition.grammars = speechRecognitionList;
  recognition.lang = lang_code2_2_deck;
  recognition.interimResults = false;
  recognition.maxAlternatives = 1;
  recognition.start();
  recognition.onresult = function(event) {
    $("#recorderRec").show();
    $("#waves").hide();
    var speechResult = event.results[0][0].transcript.toLowerCase();
    $(".transcriptEleve").val(speechResult);
    checkAndReveal(speechResult);


}
}
recognition.onspeechend = function() {
  recognition.stop();
  $(record_id).show();
  $("#waves").hide();}
recognition.onerror = function(event) {
  $(record_id).show();
  $("#waves").hide();
  console.log('error : '+event.error);}
recognition.onaudiostart = function(event) {    //Fired when the user agent has started to capture audio.
    console.log('SpeechRecognition.onaudiostart');}
recognition.onaudioend = function(event) {    //Fired when the user agent has finished capturing audio.
    console.log('SpeechRecognition.onaudioend');}
recognition.onend = function(event) {    //Fired when the speech recognition service has disconnected.
    console.log('SpeechRecognition.onend');}
recognition.onnomatch = function(event) {    //Fired when the speech recognition service returns a final result with no significant recognition. This may involve some degree of recognition, which doesn't meet or exceed the confidence threshold.
    console.log('SpeechRecognition.onnomatch');}
recognition.onsoundstart = function(event) {    //Fired when any sound — recognisable speech or not — has been detected.
    console.log('SpeechRecognition.onsoundstart');}
recognition.onsoundend = function(event) {   //Fired when any sound — recognisable speech or not — has stopped being detected.
    console.log('SpeechRecognition.onsoundend');}
recognition.onspeechstart = function (event) {    //Fired when sound that is recognised by the speech recognition service as speech has been detected.
    console.log('SpeechRecognition.onspeechstart');}
recognition.onstart = function(event) {    //Fired when the speech recognition service has begun listening to incoming audio with intent to recognize grammars associated with the current SpeechRecognition.
    console.log('SpeechRecognition.onstart');}
}
//FUNCTIONS UTILITAIRES
function generateWrongAnswer(nbreWA,mot)
{
  var WAList=[];
  var WrongAnswerMap = [
      {'base':'tt', 'changeTo':'t'},
      {'base':'ff', 'changeTo':'f'},
      {'base':'ss', 'changeTo':'s'},
      {'base':'ss', 'changeTo':'c'},
      {'base':'rr', 'changeTo':'r'},
      {'base':'mm', 'changeTo':'m'},
      {'base':'nn', 'changeTo':'n'},
      {'base':'t', 'changeTo':'tt'},
      {'base':'m', 'changeTo':'mm'},
      {'base':'èr', 'changeTo':'err'},
      {'base':'c', 'changeTo':'ss'},
      {'base':'z', 'changeTo':'s'},
      {'base':'f', 'changeTo':'ff'},
      {'base':'é', 'changeTo':'è'},
      {'base':'é', 'changeTo':'e'},
      {'base':'è', 'changeTo':'e'},
      {'base':'è', 'changeTo':'ai'},
      {'base':'une ', 'changeTo':'un'},
      {'base':'un', 'changeTo':'une'},
      {'base':'le', 'changeTo':'la'},
      {'base':'la', 'changeTo':'le'},
      {'base':'oi', 'changeTo':'oua'},
      {'base':'oi', 'changeTo':'oa'},
      {'base':'on', 'changeTo':'un'},
      {'base':'ein', 'changeTo':'un'},
      {'base':'in', 'changeTo':'ein'},
      {'base':'eau', 'changeTo':'au'},
      {'base':'au', 'changeTo':'eau'},
      {'base':'au', 'changeTo':'o'},
      {'base':'o', 'changeTo':'au'},
      {'base':'o', 'changeTo':'eau'},
      {'base':'ch', 'changeTo':'sh'},
      {'base':'sh', 'changeTo':'ch'},
      {'base':'p', 'changeTo':'b'},
      {'base':'b', 'changeTo':'v'},
      {'base':'eu', 'changeTo':'e'},
      {'base':'eur', 'changeTo':'er'},
      {'base':'er', 'changeTo':'eur'},
      {'base':'é', 'changeTo':'er'},
      {'base':'t', 'changeTo':'d'},
      {'base':'a', 'changeTo':'o'},
      {'base':'e', 'changeTo':'eu'},
      {'base':'i', 'changeTo':'y'},
      {'base':'o', 'changeTo':'ou'},
      {'base':'u', 'changeTo':'ou'},
      {'base':'y', 'changeTo':'i'},
      {'base':'à', 'changeTo':'a'},
      {'base':'x', 'changeTo':'s'},
      {'base':'û', 'changeTo':'u'}
    ];
for(Wmap_rk in WrongAnswerMap){
  if(mot.replace(WrongAnswerMap[Wmap_rk]["base"],WrongAnswerMap[Wmap_rk]["changeTo"])!=mot){
    Wmot=mot.replace(WrongAnswerMap[Wmap_rk]["base"],WrongAnswerMap[Wmap_rk]["changeTo"]);
    WAList.push(Wmot);
    }
  }

  shuffle(WAList);
  return WAList.slice(0,nbreWA);
}
function shuffle(a) {
    var j, x, i;
    for (i = a.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = a[i];
        a[i] = a[j];
        a[j] = x;
    }
    return a;
}
function play_audio(card_id)
{console.log("on demande l'écoute de "+card_id);
if(cardsById[card_id]["hasAudio"]==1)
{
if(!$("#audio_"+card_id).length){
	console.log("creation de l'élem Audio");
	soundFile="card_audio/card_"+card_id+".wav";
	$("body").append('<audio id="audio_'+card_id+'" src="'+soundFile+'">');
	}
$("#audio_"+card_id).get(0).play();
}
}

function play_audio_coin()
{
if(!$("#audio_coin").length){
	soundFile="audio/coin.wav";
	$("body").append('<audio id="audio_coin" src="'+soundFile+'">');
	}
$("#audio_coin").get(0).play();
}
function play_audio_fail()
{
if(!$("#audio_fail").length){
	soundFile="audio/fail.wav";
	$("body").append('<audio id="audio_fail" src="'+soundFile+'">');
	}
$("#audio_fail").get(0).play();
}


function ruissellement()
{
  if(id_a_travailler_restant.length!=0)
    {
    id_rand_grande_pile = rand_parmi(id_a_travailler_restant);
    pile_glissante.push(id_rand_grande_pile);
    removeElem(id_rand_grande_pile,id_a_travailler_restant);
    }
}
function rand_parmi(liste)
{
nbre_elem=liste.length;
rang=Math.floor(nbre_elem*Math.random());
return liste[rang];
}
var defaultDiacriticsRemovalMap = [
    {'base':'A', 'letters':/[\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F]/g},
    {'base':'AA','letters':/[\uA732]/g},
    {'base':'AE','letters':/[\u00C6\u01FC\u01E2]/g},
    {'base':'AO','letters':/[\uA734]/g},
    {'base':'AU','letters':/[\uA736]/g},
    {'base':'AV','letters':/[\uA738\uA73A]/g},
    {'base':'AY','letters':/[\uA73C]/g},
    {'base':'B', 'letters':/[\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181]/g},
    {'base':'C', 'letters':/[\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E]/g},
    {'base':'D', 'letters':/[\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779]/g},
    {'base':'DZ','letters':/[\u01F1\u01C4]/g},
    {'base':'Dz','letters':/[\u01F2\u01C5]/g},
    {'base':'E', 'letters':/[\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E]/g},
    {'base':'F', 'letters':/[\u0046\u24BB\uFF26\u1E1E\u0191\uA77B]/g},
    {'base':'G', 'letters':/[\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E]/g},
    {'base':'H', 'letters':/[\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D]/g},
    {'base':'I', 'letters':/[\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197]/g},
    {'base':'J', 'letters':/[\u004A\u24BF\uFF2A\u0134\u0248]/g},
    {'base':'K', 'letters':/[\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2]/g},
    {'base':'L', 'letters':/[\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780]/g},
    {'base':'LJ','letters':/[\u01C7]/g},
    {'base':'Lj','letters':/[\u01C8]/g},
    {'base':'M', 'letters':/[\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C]/g},
    {'base':'N', 'letters':/[\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4]/g},
    {'base':'NJ','letters':/[\u01CA]/g},
    {'base':'Nj','letters':/[\u01CB]/g},
    {'base':'O', 'letters':/[\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C]/g},
    {'base':'OI','letters':/[\u01A2]/g},
    {'base':'OO','letters':/[\uA74E]/g},
    {'base':'OU','letters':/[\u0222]/g},
    {'base':'P', 'letters':/[\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754]/g},
    {'base':'Q', 'letters':/[\u0051\u24C6\uFF31\uA756\uA758\u024A]/g},
    {'base':'R', 'letters':/[\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782]/g},
    {'base':'S', 'letters':/[\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784]/g},
    {'base':'T', 'letters':/[\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786]/g},
    {'base':'TZ','letters':/[\uA728]/g},
    {'base':'U', 'letters':/[\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244]/g},
    {'base':'V', 'letters':/[\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245]/g},
    {'base':'VY','letters':/[\uA760]/g},
    {'base':'W', 'letters':/[\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72]/g},
    {'base':'X', 'letters':/[\u0058\u24CD\uFF38\u1E8A\u1E8C]/g},
    {'base':'Y', 'letters':/[\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE]/g},
    {'base':'Z', 'letters':/[\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762]/g},
    {'base':'a', 'letters':/[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g},
    {'base':'aa','letters':/[\uA733]/g},
    {'base':'ae','letters':/[\u00E6\u01FD\u01E3]/g},
    {'base':'ao','letters':/[\uA735]/g},
    {'base':'au','letters':/[\uA737]/g},
    {'base':'av','letters':/[\uA739\uA73B]/g},
    {'base':'ay','letters':/[\uA73D]/g},
    {'base':'b', 'letters':/[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/g},
    {'base':'c', 'letters':/[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/g},
    {'base':'d', 'letters':/[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/g},
    {'base':'dz','letters':/[\u01F3\u01C6]/g},
    {'base':'e', 'letters':/[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g},
    {'base':'f', 'letters':/[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/g},
    {'base':'g', 'letters':/[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/g},
    {'base':'h', 'letters':/[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/g},
    {'base':'hv','letters':/[\u0195]/g},
    {'base':'i', 'letters':/[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g},
    {'base':'j', 'letters':/[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/g},
    {'base':'k', 'letters':/[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/g},
    {'base':'l', 'letters':/[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/g},
    {'base':'lj','letters':/[\u01C9]/g},
    {'base':'m', 'letters':/[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/g},
    {'base':'n', 'letters':/[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/g},
    {'base':'nj','letters':/[\u01CC]/g},
    {'base':'o', 'letters':/[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g},
    {'base':'oi','letters':/[\u01A3]/g},
    {'base':'ou','letters':/[\u0223]/g},
    {'base':'oo','letters':/[\uA74F]/g},
    {'base':'p','letters':/[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/g},
    {'base':'q','letters':/[\u0071\u24E0\uFF51\u024B\uA757\uA759]/g},
    {'base':'r','letters':/[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/g},
    {'base':'s','letters':/[\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/g},
    {'base':'t','letters':/[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/g},
    {'base':'tz','letters':/[\uA729]/g},
    {'base':'u','letters':/[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g},
    {'base':'v','letters':/[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/g},
    {'base':'vy','letters':/[\uA761]/g},
    {'base':'w','letters':/[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/g},
    {'base':'x','letters':/[\u0078\u24E7\uFF58\u1E8B\u1E8D]/g},
    {'base':'y','letters':/[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/g},
    {'base':'z','letters':/[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/g}
];

function lettre_lt(str) {
    //    defaultDiacriticsRemovalMap;
    //for(var i=0; i<defaultDiacriticsRemovalMap.length; i++) {
    //    str = str.replace(defaultDiacriticsRemovalMap[i].letters, defaultDiacriticsRemovalMap[i].base);
    //}
    return str;
}

function update_mots_restant()
{
len=selected_card_done.length;
nbre_mot_total=selected_card.length;
//if(nbre_mot_total==1){$("#mots_restants").html(len+"/"+nbre_mot_total+" <?php //echo __("carte");?>");}
//else{$("#mots_restants").html(len+"/"+nbre_mot_total+" <?php //echo __("cartes");?>");}
pcentProgress=Math.floor(100*(len/nbre_mot_total))+"%";
$(".progressbarDeck").show();
$(".progressbarDeck").css("width",pcentProgress);
}

function removeElem(elem,liste)
{
	var index = liste.indexOf(elem);
	if(index > -1){
	liste.splice(index,1);
	}
}


</script>
