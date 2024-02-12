document.addEventListener("DOMContentLoaded", function () {
  var deleteButtons = document.querySelectorAll(".btn-delete-ad");
  //boucle dans les bouttons et récupère
  deleteButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      //Récupération de l'id de l'Ad à supprimer (injecté dans data-ad-id dans page html)
      var adId = button.getAttribute("data-ad-id");
      deleteAd(adId);
    });
  });

  // Suppression en ajax de la cible
  function deleteAd(adId) {
    //Instance de l'objet XMLHttpRequest
    var xhr = new XMLHttpRequest();
    //Ouverture de requete DELETE vers l'url dont la route est configurée pour la suppression, passe en parametre l'id de l'AD
    xhr.open("DELETE", "/my-ads/delete/" + adId, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    //quand la requete est treminée
    xhr.onload = function () {
      console.log(xhr.status);
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
