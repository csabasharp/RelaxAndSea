<?php

if (!defined("BUILT_BY_PHP")) {
    header("Location: ".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'],true,301);
    die();
}

?>

<div class="popup">
    <form id="regurlap" action="./assets/php/account.php" method="post">
        <p>
          Hogy foglalhasson, foglalását nyomonkövethesse vagy módosítsa,
          elősször egy új fiókot kell létrehoznia.
        </p>
        <p><b>Adatait nem osztjuk senkivel.</b></p>
        <img src="<?=$this->toPlace("assets/images/regpic.jpg");?>" alt="regurlapKep" />
        <div class="form-group">
          <label for="nev">Teljes név:</label>
          <span></span>

          <input
            required
            type="text"
            class="form-control uszoveg"
            id="nev"
            name="nev"
            placeholder="Gipsz Jakab"
          />
        </div>
        <div class="form-group">
          <label for="email">Email cím:</label>
          <span></span>
          <input
            required
            type="email"
            class="form-control uszoveg"
            id="email"
            name="email"
            aria-describedby="emailHelp"
            placeholder="gipszjakab@gmail.com"
          />
        </div>

        <div class="form-group">
          <label for="telefon">Telefonszám:</label>
          <span class="form-text text-muted alszoveg" id="telefonszamszoveg">
            7
          </span>
          <div style="display: flex">
            <input
              readonly
              type="tel"
              class="form-control"
              id="prefix"
              value="06"
            />
            <input
              required
              type="tel"
              class="form-control uszoveg"
              id="telefon"
              name="telefon"
              placeholder="701234567"
              pattern="[0-9]{1,7}"
              maxlength="9"
              minlength="9"
            />
          </div>
        </div>

        <div class="form-group">
          <label for="szuldat">Születési dátum:</label>
          <span class="form-text text-muted alszoveg" id="szuldatminszoveg"
            >legalább 16 éves kell legyen a foglaláshoz</span
          >
          <input
            required
            type="date"
            class="form-control uszoveg"
            id="szuldat"
            name="szuldat"
          />
        </div>
        <div class="form-group">
          <label for="jelszo">Jelszó:</label>
          <span class="form-text text-muted alszoveg" id="jelszominszoveg"
            >minimum 8, maximum 50 karakter</span
          >
          <input
            required
            type="password"
            class="form-control uszoveg"
            id="jelszo"
            placeholder="************"
            name="jelszo"
          />
        </div>
        <br />

        <div class="form-check">
          <input
            required
            type="checkbox"
            class="form-check-input"
            id="adatelf"
          />
          <b>
            <label class="form-check-label" for="adatelf">
              Elfogadom, az adatvédelmi nyilatkozatot.</label
            >
          </b>
        </div>
        <br />
        <button
          type="submit"
          value="regist"
          name="submit"
          id="regist"
          class="btn"
        >
          Regisztráció
        </button>
      </form>
      <div class="container">
        <form class="loginurlap">
          <div class="form-group">
            <label for="email">E-mail cím:</label>
            <input
              type="email"
              class="form-control uszoveg"
              id="email"
              name="email"
            />
          </div>
          <div class="form-group">
            <label for="jelszo">Jelszó:</label>
            <input
              type="password"
              class="form-control uszoveg"
              id="jelszo"
              name="jelszo"
            />
          </div>
          <button type="submit" name="submit" class="btn" onsubmit="">
            Bejelentkezés
          </button>
        </form>
      </div>
</div>