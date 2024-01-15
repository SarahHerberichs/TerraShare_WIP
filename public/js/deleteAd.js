document.addEventListener("DOMContentLoaded", function () {
  var deleteButtons = document.querySelectorAll(".btn-delete-ad");

  deleteButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      var adId = button.getAttribute("data-ad-id");
      deleteAd(adId);
    });
  });

  function deleteAd(adId) {
    // Envoyer une requête AJAX au serveur pour supprimer l'annonce
    var xhr = new XMLHttpRequest();
    xhr.open("DELETE", "/my-ads/delete/" + adId, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onload = function () {
      if (xhr.status === 200) {
        // Analyser la réponse du serveur si nécessaire
        var response = JSON.parse(xhr.responseText);
        console.log(response.message);

        // Recharger la page après la suppression réussie
        window.location.reload();
      } else {
        console.error("Erreur lors de la suppression de l'annonce");
      }
    };
    xhr.send();
  }
});
