
## P5 OPENCLASSROOMS

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/35f828e84d514f5e99df7c71662602c7)](https://www.codacy.com/manual/bangix28/Forum-P5-Oc/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=bangix28/Forum-P5-Oc&amp;utm_campaign=Badge_Grade)
## 

## Installation

 - **Etape 1 :** Transférer les fichiers dans le dossier racine de votre serveur web.
- **Etape 2:** Importez la base de donnée sql sur votre SGBD .
- **Etape 3:** Dans le fichier config/orm.php Modifiez les parametre suivants:
 -   host : 'mysql:host=AdresseDB;
 -   dbname: dbname=NomDB; (mom par défaut = blog);
 -   user : 'utilisateurDB';
 -   password : passwordDB'
 - Pour lancer le serveur php allez dans le répertoire public avec votre invite de commandes est taper la commande suivante : php -S localhost:8000
 - Pour utilisez les commande doctrine vous devez taper la commande suivante php vendor/doctrine/orm/bin/doctrine  
 
 ##
 ## Paramétrage de Swift Mail
 - **Etape 1:** Allez dans src/services/mail/Mail.php et Modifiez les paramètre suivants dans  la fonction transport :
 - $transport = (new Swift_SmtpTransport('Votre stmp,'la sécurité de celui-ci'))  
 ->setUsername('L'addresse email que vous voulez utilisez')  
 ->setPassword('Le mot de de passe de celle ci')  
 ## Paramétrage de Google Recaptcha
 
 - **Etape 1:** Allez sur https://www.google.com/recaptcha/admin et enregistrer votre site dessus.
 - **Etape 2:** Allez dans src/services/captcha/captcha.php et inserez votre clef coté serveur $recaptcha_secret = 'Votre clef';
 - **Etape 3:** Allez dans templates index/index.html.twig et security/recoverPassword.html.twig ensuite dans la fonction javascript  de google changer la clef et mettez la clef coté client
 -  grecaptcha.execute('Votre clef', {action: 'contact'}).then(function (token) {
 - src="https://www.google.com/recaptcha/api.js?render=votre clef serveur"

 ## Compte verifier 

-   Dans votre base de données et dans la table "user", modifier la colonne "role" de l'utilisateur que vous venez de créer et insérez la valeur 1.
  
-   Enregistrez la modification.

