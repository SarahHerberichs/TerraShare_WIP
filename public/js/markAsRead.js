document.addEventListener("DOMContentLoaded", function () {
  var markAsReadButtons = document.querySelectorAll(".mark-as-read");

  markAsReadButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      var messageId = button.getAttribute("data-message-id");
      button.style.display = "none";
      markAsRead(messageId);
    });
  });

  function markAsRead(messageId) {
    // Faites une requête AJAX pour mettre à jour le statut isRead dans la base de données
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/update-message-status/" + messageId, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        // Mettez à jour l'affichage côté client immédiatement
        var messageElement = document.querySelector(".message-id-" + messageId);
        if (messageElement) {
          // Vérifiez d'abord l'état actuel avant de faire des modifications
          var isRead = messageElement.classList.contains("message-read");

          if (!isRead) {
            // Mettez à jour l'élément uniquement si le message n'est pas déjà lu
            var notReadElement =
              messageElement.querySelector(".message-not-read");
            var readElement = messageElement.querySelector(".message-read");

            if (notReadElement && readElement) {
              notReadElement.style.display = "none";

              readElement.style.display = "block";
            }

            // Ajoutez une classe pour indiquer que le message est lu
            messageElement.classList.add("message-read");
          }
        }
      }
    };

    xhr.send();
  }
});
