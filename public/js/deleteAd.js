document.addEventListener("DOMContentLoaded", function () {
  var deleteButtons = document.querySelectorAll(".btn-delete-ad");
  //Boucle dans les bouttons et récupère l'id de l'Ad à supprimer (injecté dans data-ad-id ), puis la supprime
  deleteButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      var adId = button.getAttribute("data-ad-id");
      deleteAd(adId);
    });
  });

  // Function pour Suppression en ajax de la cible
  function deleteAd(adId) {
    //Instance de l'objet XMLHttpRequest
    var xhr = new XMLHttpRequest();
    //Ouverture de requete DELETE vers l'url dont la route est configurée pour la suppression, passe en parametre l'id de l'AD
    xhr.open("DELETE", "/my-ads/delete/" + adId, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    //quand la requete est terminée
    xhr.onload = function () {
      //Vérifie le statut de la réponse
      if (xhr.status === 200) {
        // Pour analyser la réponse
        var response = JSON.parse(xhr.responseText);
        // Rechargement de la page si requete OK
        window.location.reload();
      } else {
        console.error("Erreur lors de la suppression de l'annonce");
      }
    };
    //Envoi de la requete Delete
    xhr.send();
  }
});
