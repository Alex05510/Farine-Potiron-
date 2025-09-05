document.addEventListener("DOMContentLoaded", function () {
  let lastModalContent = "";

  document
    .getElementById("voir-farines")
    // Affichage de la liste des farines
    .addEventListener("click", function () {
      fetch("https://api.mywebecom.ovh/play/fep/catalogue.php")
        .then((response) => response.json())
        .then((farines) => {
          let html = "<ul>";
          for (const ref in farines) {
            html +=
              '<li><a href="#" class="voir-farine" data-ref="' +
              ref +
              '">' +
              farines[ref] +
              "</a></li>";
          }
          html += "</ul>";
          lastModalContent = html;
          document.getElementById("modal").style.display = "block";
          document.getElementById("modal-overlay").style.display = "block";
          document.getElementById("modal-content").innerHTML = html;
        });
    });

  document.getElementById("modal-close").addEventListener("click", function () {
    // Fermeture de la modale
    document.getElementById("modal").style.display = "none";
    document.getElementById("modal-overlay").style.display = "none";
  });

  // Détail d'une farine au clic
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("voir-farine")) {
      e.preventDefault();
      var ref = e.target.getAttribute("data-ref");
      fetch(
        "https://api.mywebecom.ovh/play/fep/catalogue.php?ref=" +
          encodeURIComponent(ref)
      )
        .then((response) => response.json())
        .then((data) => {
          let html =
            "<button id='modal-retour'>Retour</button>" +
            "<h2>" +
            data.libelle +
            "</h2><p>" +
            "<p>Référence : " +
            data.reference +
            "</p>" +
            data.description +
            "</p>";
          document.getElementById("modal-content").innerHTML = html;
        });
    }
  });

  // Gestion du bouton retour dans la modale
  document
    .getElementById("modal-content")
    .addEventListener("click", function (e) {
      if (e.target.id === "modal-retour") {
        document.getElementById("modal-content").innerHTML = lastModalContent;
      }
    });
});
