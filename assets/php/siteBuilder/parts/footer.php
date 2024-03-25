<?php

if (!defined("BUILT_BY_PHP")) {
    header("Location: ".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'],true,301);
    die();
}
else {
?>
    <footer class="footer">
      <div
        id="kapcsolatfelvetel"
        class="text-reset d-flex justify-content-center"
      >
        <ul class="list-group">
          <li class="list-group-item contactelem d-flex align-items-center">
            <i class="bi bi-envelope"></i> Email: relax.and.sea.hotel@gmail.com
          </li>
          <li class="list-group-item contactelem d-flex align-items-center">
            <i class="bi bi-telephone align-items-center"></i>
            Telefonszám: 06 112345678
          </li>
          <li class="list-group-item contactelem d-flex align-items-center">
            <i class="bi bi-geo-alt"></i> Cím: Budapest, Létező Tengerpart utca
            4.
          </li>
        </ul>
      </div>

      <div id="jog" class="text-reset">
        Copyright © 2023-2024 - Hotel Relax & Sea - Minden jog fenntartva.

        <p id="jogilinkek">
          <a
            href="https://www.fontfabric.com/fontfabric-commercial-eula-for-free-fonts/"
            target="_blank"
            >Fontfabric</a
          >
          <a
            href="https://gist.github.com/kn9ts/cbe95340d29fc1aaeaa5dd5c059d2e60"
            >Licensz</a
          >
          <a href="">Adatvédelem</a>
        </p>
      </div>
    </footer>

<?php
}

?>