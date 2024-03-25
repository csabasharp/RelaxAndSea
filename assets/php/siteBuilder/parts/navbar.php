<?php

if (!defined("BUILT_BY_PHP")) {
  header("Location: ".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'],true,301);
  die();
}
else {
    ?>

<header id="roof">
      <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
          <div class="logo-container">
            <a class="navbar-brand relaxlogoszoveg" href="<?= $this->toPlace()?>">
              <img src=<?php echo $this->toPlace("assets/images/svgLogoSargaSB.svg")?> alt="Menulogo" />
            </a>
          </div>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
              <li class="nav-item">
                <a class="nav-link <?php if($page == "home" || $page == ""){ echo 'active';}?>" aria-current="page" href="<?= $this->toPlace()?>">
                  Kezdőlap
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if($page == "szobak"){ echo 'active';}?>" href="<?= $this->toPlace("szobak/")?>">
                    Szobáink és árai
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link <?php if($page == "etterem"){ echo 'active';}?>" href="<?= $this->toPlace("etterem/")?>">Étterem</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if($page == "rolunk"){ echo 'active';}?>" href="<?= $this->toPlace("rolunk/")?>">Szállodánkról</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if($page == "wellness"){ echo 'active';}?>" href="<?= $this->toPlace("wellness/")?>">Wellness</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if($page == "contact"){ echo 'active';}?>" href="<?= $this->toPlace("contact/")?>">Elérhetőség</a>
              </li>
            </ul>

            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link foglalasgomb" href="#regurlap">Foglalás</a>
              </li>
            </ul>
            <ul class="navbar-nav">
              <li class="nav-item">
                <div class="login-link">
                  <?php if(isset($_SESSION) && !is_null($_SESSION["username"])):?>
                  <div>
                  <span class="nav-item">Üdv, <?php echo $_SESSION["username"];?></span>
                  <a
                    class="nav-link text-gradient"
                    id="login"
                    href="<?php echo $this->toPlace("account/");?>"
                    >Fiók
                  </a>
                  <?php else: ?>
                  <a
                    class="nav-link text-gradient"
                    id="login"
                    href="#loginurlap"
                    data-bs-toggle="modal" 
                    data-bs-target="#reglog"
                    >Bejelentkezés</a
                  >
                  <?php endif; ?>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>

<?php
    if (isset($page)) {
        //aktiv oldal tag formázás
    }
}

?>