window.onload = function () {
  var jelszoinput = document.getElementById("jelszo");
  var jelszominszoveg = document.getElementById("jelszominszoveg");
  var korDatuminput = document.getElementById("szuldat");
  var szuldatminszoveg = document.getElementById("szuldatminszoveg");
  var telefoninput = document.getElementById("telefon");
  var telefonszamszoveg = document.getElementById("telefonszamszoveg");
  var emailinput = document.getElementById("email");
  var nevinput = document.getElementById("nev");

  var regurlap = document.getElementById("regurlap");

  function emailEllenorzes(emailinput) {
    if (emailinput.value.length >= 3 && emailinput.value.includes("@")) {
      return true;
    }
    return false;
  }

  function nevEllenorzes(nevinput) {
    if (nevinput.value.length >= 3 && nevinput.value.includes(" ")) {
      return true;
    } else return false;
  }

  function jelszoEllenorzes(jelszoinput) {
    if (jelszoinput.value.length >= 8 && jelszoinput.value.length <= 50) {
      return true;
    } else {
      jelszominszoveg.textContent = "rövid jelszó";
      return false;
    }
  }

  function telefonEllenorzes(telefoninput) {
    var regex = /^[0-9]{9}$/;
    if (regex.test(telefoninput.value)) {
      return true;
    } else {
      telefonszamszoveg.textContent = "érvénytelen telefonszám";
      return false;
    }
  }

  function korEllenorzes(korDatuminput) {
    korDatuminput = new Date(korDatuminput.value);
    var jelenlegiDatum = new Date();

    var korDatumEv = jelenlegiDatum.getFullYear() - korDatuminput.getFullYear();
    var korHonap = jelenlegiDatum.getMonth() - korDatuminput.getMonth();
    var korNap = jelenlegiDatum.getDay() - korDatuminput.getDay();

    if (korHonap < 0 || (korHonap == 0 && korNap < 0)) {
      korDatumEv--;
    }

    if (korDatumEv > 110) {
      szuldatminszoveg.style.content = "érvénytelen születési dátum";
      return false;
    }

    if (korDatumEv >= 16 && korDatumEv < 110) {
      return true;
    }

    return false;
  }

  function InputokErvenyesek() {
    if (
      !korEllenorzes(korDatuminput) ||
      !telefonEllenorzes(telefoninput) ||
      !jelszoEllenorzes(jelszoinput) ||
      !nevEllenorzes(nevinput) ||
      !emailEllenorzes(emailinput)
    ) {
      return false;
    }

    return true;
  }

  regurlap.addEventListener("submit", function (event) {
    if (!InputokErvenyesek()) {
      event.preventDefault();
    }
  });
};
