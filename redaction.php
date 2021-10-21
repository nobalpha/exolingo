<!DOCTYPE html>
<?php
session_start();
include_once ("db.php");
$explodeURI=explode('/',$_SERVER['REQUEST_URI']);
$_SESSION['url']=end($explodeURI);
if(!isset($_SESSION['user_id'])){
	header("location:checkLoginCookie.php");
	echo "pas de user_id";
	exit();}
    // Makes it easier to read
	  $user_id = $_SESSION['user_id'];
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
    $active = $_SESSION['active'];
		$type = $_SESSION['type'];
    echo "<script>fullUserName='".$first_name." ".$last_name."';</script>";
		echo "<script>type='".$type."';</script>";
		echo "<script>user_id=".$user_id.";</script>";
?>
<!DOCTYPE html>
<html >
 <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rédaction</title>
    <link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="img/favicon-16x16.png" sizes="16x16" />

    <!-- Bootstrap -->
	  <link href="css/main.css?ver=<?php echo filemtime('css/main.css');?>" rel="stylesheet">
		<link href="css/styleEntete.css?ver=<?php echo filemtime('css/styleEntete.css');?>" rel="stylesheet">
	  <link href="css/deck.css?ver=<?php echo filemtime('css/deck.css');?>" rel="stylesheet">
	  <link href="css/card.css?ver=<?php echo filemtime('css/card.css');?>" rel="stylesheet">

    <link href="css/style.css?ver=<?php echo filemtime('css/style.css');?>" rel="stylesheet">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rédaction</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

      <link href="css/styleLanguage.css?v8" rel="stylesheet" type="text/css" media="screen"/>
      <link href="js/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" media="screen"/>
      <link href="css/zebra_dialog.css" rel="stylesheet" type="text/css"/>
      <link href="css/tablesorter-style.css" rel="stylesheet" type="text/css"/>
      <link href="css/lib/dropkick/dropkick.css" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="js/jquery-1.7.0.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.js"></script>
    <!-- used only for development:
    <script type="text/javascript" src="/cdnjs.cloudflare.com/ajax/libs/less.js/1.5.0/less.min.js"></script>
    -->

      <script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
      <script type="text/javascript" src="js/zebra_dialog.js"></script>
        <script type="text/javascript" src="online-check/tiny_mce/tiny_mce.js"></script>
      <script type="text/javascript" src="online-check/tiny_mce/plugins/atd-tinymce/editor_plugin3.js?v3014013"></script>
        <script type="text/javascript"></script>
        <link href="css/navStyle.css?ver=<?php echo filemtime('css/navStyle.css');?>" rel="stylesheet">
        <link href="css/myStyle.css?ver=<?php echo filemtime('css/myStyle.css');?>" rel="stylesheet">
		<style>
      .navbar{
        margin-bottom:0;
        border-radius:0;
      }
    </style>
</head>
<body class="fond">
  <style>
    .navbar{
      margin-bottom:0;
      border-radius:0;
    }
  </style>
	<?php include "entete.php";?>
	<script>
  	$('.desktop').menuBreaker();


  </script>

<div class="center bodyContent">

        <script type="text/javascript">
        $(document).ready(function() {
          $("a.fancyboxImage").fancybox({
            'hideOnContentClick': true,
            'titlePosition': 'inside'
          });
        });




     // translation of language variant names:

     tinyMCE.init({
         mode : "textareas",
         plugins                     : "AtD,paste",
         directionality              : 'auto',   // will display e.g. Persian in right-to-left

         //Keeps Paste Text feature active until user deselects the Paste as Text button
         paste_text_sticky : true,
         //select pasteAsPlainText on startup
         setup : function(ed) {
             ed.onInit.add(function(ed) {
                 ed.pasteAsPlainText = true;
                 doit();  // check immediately when entering the page
             });
             ed.onKeyDown.add(function(ed, e) {
                 if (e.ctrlKey && e.keyCode == 13) {  // Ctrl+Return
                     doit(true);
                     tinymce.dom.Event.cancel(e);
                 } else if (e.keyCode == 27) {   // Escape
                     // doesn't work in firefox, the re-init in turnOffFullScreenView()
                     // might clash with event handling:
                     if ($('form#checkform').hasClass('fullscreen')) {
                         setTimeout(turnOffFullScreenView, 100);  // use timeout to prevent problems on Firefox
                     }
                 }
             });
             // remove any 'no errors found' message:
             ed.onKeyUp.add(function(ed, e) {
                 if (!e.keyCode || e.keyCode != 17) {  // don't hide if user used Ctrl+Return
                     $('#feedbackMessage').html('');
                 }
             });
             ed.onPaste.add(function(ed, e) {
                 $('#feedbackMessage').html('');
             });
         },

         /* translations: */
         languagetool_i18n_no_errors :
            {
             'fr': 'Aucune erreur trouvée.'
            },
         languagetool_i18n_explain :
            {
             // "Explain..." - shown if there's an URL with a more detailed description:
             'fr': 'Plus d’informations…'
            },

         languagetool_i18n_ignore_once :
            {
             // "Ignore this type of error" -- for non-spelling errors:
             'fr': 'Ignorer ce type d’erreur'
            },
         languagetool_i18n_ignore_all :
         {
             // "Ignore error for this word" -- for spelling errors:
             'fr': 'Ignorer l’erreur pour ce mot'
         },

         languagetool_i18n_rule_implementation :
            {
             // "Rule implementation":
             'fr': 'Implémentation de la règle…'
            },

         languagetool_i18n_suggest_word :
            {
             // "Suggest word for dictionary...":
             // *** Also set languagetool_i18n_suggest_word_url below if you set this ***
             'fr': 'Suggerer un mot pour le dictionnaire…'
            },
         languagetool_i18n_suggest_word_url :
            {
             // "Suggest word for dictionary...":
              'fr': 'Suggerer un mot pour le dictionnaire…'
            },

         languagetool_i18n_current_lang :    function() { return "fr"; },
         /* the URL of your proxy file: */
         languagetool_rpc_url                 : "https://www.exolingo.com/languageTool",
         /* edit this file to customize how LanguageTool shows errors: */
         languagetool_css_url                 : "/online-check/tiny_mce/plugins/atd-tinymce/css/content.css?v5",
         /* this stuff is a matter of preference: */
         theme                              : "advanced",
         theme_advanced_buttons1            : "",
         theme_advanced_buttons2            : "",
         theme_advanced_buttons3            : "",
         theme_advanced_toolbar_location    : "none",
         theme_advanced_toolbar_align       : "left",
         theme_advanced_statusbar_location  : "bottom",  // activated so we have a resize button
         theme_advanced_path                : false,     // don't display path in status bar
         theme_advanced_resizing            : true,
         theme_advanced_resizing_use_cookie : false,
         /* disable the gecko spellcheck since AtD provides one */
         gecko_spellcheck                   : false
     });

      function fullscreen_toggle() {
        if ($('form#checkform').hasClass('fullscreen')) {
          turnOffFullScreenView();
        } else {
          turnOnFullScreenView();
          if (_paq) { _paq.push(['trackEvent', 'Action', 'SwitchToFullscreen']); } // Piwik tracking
        }
        return false;
      }

     function turnOffFullScreenView() {
         // re-init the editor - this way we lose the error markers, but it's needed
         // to get proper position of the context menu:
         // source: http://stackoverflow.com/questions/4651676/how-do-i-remove-tinymce-and-then-re-add-it
         tinymce.EditorManager.execCommand('mceRemoveControl',true, 'checktext');
         tinymce.EditorManager.execCommand('mceAddControl', true, 'checktext');
         $('form#checkform').removeClass('fullscreen');
         $('body').removeClass('fullscreen');
         $('iframe#checktext_ifr').height(270);
         tinymce.execCommand('mceFocus', false, 'checktext');
     }

     function turnOnFullScreenView() {
         tinymce.EditorManager.execCommand('mceRemoveControl',true, 'checktext');
         tinymce.EditorManager.execCommand('mceAddControl', true, 'checktext');
         $('body').addClass('fullscreen');
         $('form#checkform').addClass('fullscreen');
         $('iframe#checktext_ifr').height( $(window).height() - $('#editor_controls').outerHeight() - $('#handle').outerHeight() );
         tinymce.execCommand('mceFocus', false, 'checktext');
     }

     function doit(doLog) {
         document.checkform._action_checkText.disabled = true;
         var langCode = 'fr';
         if (doLog) {
             if (_paq) { _paq.push(['trackEvent', 'Action', 'CheckText', langCode]); } // Piwik tracking
         }
         tinyMCE.activeEditor.execCommand('mceWritingImprovementTool', langCode);
     }

     $(function(){
      $(window).resize(function(){
        if ($('form#checkform').hasClass('fullscreen')) {
          $('iframe#checktext_ifr').height( $(window).height() - $('#editor_controls').outerHeight() );
        }
      });
     });

     </script>

      <script type="text/javascript" src="css/lib/dropkick/jquery.dropkick.js"></script>
      <script type="text/javascript">

          $(function(){
              var languageToText = [];
              languageToText['fr'] = 'Copiez votre texte ici ou vérifiez cet exemple contenant plusieurs erreur que LanguageTool doit doit pouvoir detecter.';
          });
      </script>

      <script type="text/javascript">
          function resize_buttons(){
              var max_height = 0;
              $('.button_container .title').each(function(){
                  $(this).height('auto');
                  if ($(this).height() > max_height) {
                      max_height = $(this).height();
                  }
              });
              $('.button_container .title').height(max_height);
          }
          $(function(){
              $(window).resize(function(){
                  resize_buttons();
              });
              resize_buttons();
          });
      </script>





  <style>
  #menu_checktext_spellcheckermenu{width:300px;}
  </style>
  <div id="stage" class="start">

    <div class="inner">
      <h1 style="color:white;">Ma rédaction</h1>
      <div id="editor">
        <div class="inner">

          <noscript class="warning">Please turn on Javascript to use this form, or use <a href="simple-check/">the fallback form</a></noscript>

  <form id="checkform" class="" name="checkform" action="#" method="post">
      <div id="handle"><div id="feedbackMessage"></div></div>
      <div class="window">

          <div id="checktextpara" style="margin: 0;position:relative;">

                <textarea id="checktext" name="text" style="width: 100%" rows="10">Ceci est un text qui contien des faute. on va voir ce ce qui va être repérer</textarea>
                <div class="fullscreen-toggle">
                      <a href="#" title="toggle fullscreen mode" onClick="fullscreen_toggle();return false;"></a>
                </div>
          </div>
          <div id="editor_controls">
              <div class="message"></div>
              <div id="feedbackErrorMessage"></div>
              <div class="submit">
                  <input type="submit" name="_action_checkText" value="Check Text"
                         onClick="doit(true);return false;" title="Vérifier le texte">
              </div>
              <div style="clear:both;"></div>
              <div id="dialog" style="padding:10px;"></div>
          </div>
      </div>
  </form>
           </div>
    </div>


  </div>
  </div>

  <script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://openthesaurus.stats.mysnip-hosting.de/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 2]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
  </script>
  <noscript><p><img src="http://openthesaurus.stats.mysnip-hosting.de/piwik.php?idsite=2" style="border:0;" alt="" /></p></noscript>
  <!-- End Piwik Code -->
</div>
    </body>
  </html>
