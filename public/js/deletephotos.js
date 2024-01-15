document.addEventListener("DOMContentLoaded", function () {
  var deleteButtons = document.querySelectorAll(".btn-delete-photo");

  deleteButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      var photoId = button.getAttribute("data-photo-id");
      deletePhoto(photoId);
    });
  });

  function deletePhoto(photoId) {
    // Envoyer une requête AJAX au serveur pour supprimer la photo
    var xhr = new XMLHttpRequest();
    xhr.open("DELETE", "/delete-photo/" + photoId, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onload = function () {
      if (xhr.status === 200) {
        // Supprimer l'élément HTML de la photo côté client
        var photoElement = document.getElementById("photo_" + photoId);
        if (photoElement) {
          photoElement.remove();
        }
      } else {
        console.error("Erreur lors de la suppression de la photo");
      }
    };

    xhr.send();
  }
});
