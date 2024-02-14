document.getElementById("payButton").onclick = function () {
  // Récupere la valeur entrée dans l'input
  var sum = document.getElementById("sum").value;

  // Récupere l'URL stockée dans l'attribut data-url
  var url = this.getAttribute("data-url");

  // Construction de l'URL avec la valeur de sum interpolée
  var finalUrl = url + "?sum=" + sum;

  // Redirection vers la page de paiement
  window.location.href = finalUrl;
};
