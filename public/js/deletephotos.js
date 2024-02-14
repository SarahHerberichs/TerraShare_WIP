document.addEventListener("DOMContentLoaded", function () {
  var deleteButtons = document.querySelectorAll(".btn-delete-photo");
  //Boucle dans tous les boutons de suppression et Récup l'ID de la photo pour la supprimer
  deleteButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      var photoId = button.getAttribute("data-photo-id");
      deletePhoto(photoId);
    });
  });
  //Fonction pour supprimer une photo en requete AJAX
  function deletePhoto(photoId) {
    var xhr = new XMLHttpRequest();
    xhr.open("DELETE", "/delete-photo/" + photoId, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    //Gestionnaire d'evenement pour le chargement de la requete
    xhr.onload = function () {
      if (xhr.status === 200) {
        // Supprimer l'élément HTML de la photo côté client si requete OK
        var photoElement = document.getElementById("photo_" + photoId);
        if (photoElement) {
          photoElement.remove();
        }
      } else {
        console.error("Erreur lors de la suppression de la photo");
      }
    };
    //Envoi de la requete delete
    xhr.send();
  }
});
