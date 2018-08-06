<?php

$months = array(1=>'Janvier', 2=>'Février', 3=>'Mars',
              4=>'Avril', 5=>'Mai', 6=>'Juin',
          7=>'Juillet', 8=>'Aout', 9=>'Septembre',
          10=>'Octobre', 11=>'Novembre', 12=>'Decembre');

?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">

  <head>
    <meta charset="utf-8">
    <title>The Hub - Inscription</title>
    <link rel="stylesheet" href="../../public/css/reset.css">
    <link rel="stylesheet" href="../../public/css/style.css">
  </head>

  <body>
    <div id="signup">
      <h1 id="signup-title">The Hub</h1>
      <h2 id="signup-subtitle">Inscription</h2>

      <form id="signup-form" action="" autocomplete="off">
        <div class="signup-form-section">
          <div class="signup-form-category">
            <label class="signup-form-label" for="pseudo">Pseudo</label>
            <input class="signup-form-input" type="text" id="pseudo" name="pseudo" placeholder="Pseudo">
          </div>
          <div class="signup-form-category">
            <label class="signup-form-label" for="email">Email (celui où tu as reçu le lien)</label>
            <input class="signup-form-input" type="email" id="email" name="email" placeholder="Email">
          </div>
          <div class="signup-form-category">
            <label class="signup-form-label" for="password">Mot de passe</label>
            <input class="signup-form-input" type="password" id="password" name="password" placeholder="Mot de passe">
          </div>
          <div class="signup-form-category">
            <label class="signup-form-label" for="confirm_password">Corfirmer mot de passe</label>
            <input class="signup-form-input" type="password" id="confirm_password" name="confirm_password" placeholder="Corfirmer mot de passe">
          </div>
          <div class="signup-form-category">
            <label class="signup-form-label" for="code_by_mail">Code reçu par mail</label>
            <input class="signup-form-input" type="text" id="code_by_mail" name="code_by_mail" placeholder="Code reçu par mail">
          </div>
        </div>
        <div class="signup-form-section">
          <div class="signup-form-category">
            <label class="signup-form-label" for="firstname">Prénom</label>
            <input class="signup-form-input" type="text" id="firstname" name="firstname" placeholder="Prénom">
          </div>
          <div class="signup-form-category">
            <label class="signup-form-label" for="lastname">Nom</label>
            <input class="signup-form-input" type="text" id="lastname" name="lastname" placeholder="Nom">
          </div>
          <div class="signup-form-category">
            <label class="signup-form-label" for="birthday">Date de naissance</label>
            <div>
              <select name="birthday_day" id="birthday_day">
                <?php
                  for ($day=1; $day <= 31; $day++) { 
                    echo '<option value="' . $day . '">' . $day . '</option>';
                  };
                ?>
              </select>
              <select name="birthday_month" id="birthday_month">
                <?php
                  foreach($months as $key => $month) {
                    echo '<option value="' . $key . '">' . $month . '</option>';
                  };
                ?>
              </select>
              <select name="birthday_year" id="birthday_year">
                <?php
                  for ($year=date('Y')-120; $year <= date('Y'); $year++) {
                    if ($year == date('Y')) {
                      $selected = 'selected="selected"';
                    };
                    echo '<option value="' . $year . '"' . $selected . '>' . $year . '</option>';
                  };
                ?>
              </select>
            </div>
          </div>
          <div class="signup-form-category">
            <label class="signup-form-label" for="city">Ville</label>
            <input class="signup-form-input" type="text" id="city" name="city" placeholder="Ville">
          </div>
          <div class="signup-form-category">
            <label class="signup-form-label" for="country">Pays</label>
            <input class="signup-form-input" type="text" id="country" name="country" placeholder="Pays">
          </div>
          <div class="signup-form-category">
            <label class="signup-form-label" for="gender">Sexe</label>
            <div>
              <label class="signup-form-label-test" for="gender">Homme
                <input class="signup-form-radio" type="radio" id="gender" name="gender" value="man">
              </label>
              <label class="signup-form-label" for="gender">Femme
                <input class="signup-form-radio" type="radio" id="gender" name="gender" value="woman">
              </label>
            </div>
          </div>
        </div>
        <button class="signup-form-btn">S'inscrire</button>
      </form>
    </div>
  </body>

</html>