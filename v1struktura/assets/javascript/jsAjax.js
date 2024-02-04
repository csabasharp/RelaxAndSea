window.onload = function () {






  
    .getElementById("regurlap")
    .addEventListener("submit", function (event) {
      if (!InputokErvenyesek()) {
        event.preventDefault();
      }
    });
};
