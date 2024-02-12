document.addEventListener("DOMContentLoaded", function () {
  var markAsReadButtons = document.querySelectorAll(".mark-as-read");
  //pour chaque bouton, au click, récupere l'id du message
  //et appelle fonction markAsRead (définie plus bas)
  markAsReadButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      var messageId = button.getAttribute("data-message-id");
      button.style.display = "none";
      markAsRead(messageId);
    });
  });
  //Requete ajax sur le message pour faire un update du message

  function markAsRead(messageId) {
    // Faites une requête AJAX pour mettre à jour le statut isRead dans la base de données
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/update-message-status/" + messageId, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        //Déclare un event à dispatcher dans toute l'appli(pour utiliser dans navbar)
        var event = new Event("messageRead");
        document.dispatchEvent(event);

        var messageElement = document.querySelector(".message-id-" + messageId);
        if (messageElement) {
          //verif si message est lu ou non
          var isRead = messageElement.classList.contains("message-read");

          if (!isRead) {
            //Mise à jour si pas lu
            var notReadElement =
              messageElement.querySelector(".message-not-read");
            var readElement = messageElement.querySelector(".message-read");

            if (notReadElement && readElement) {
              notReadElement.style.display = "none";
              readElement.style.display = "block";
            }
            messageElement.classList.add("message-read");
          }
          // Après avoir marqué le message comme lu, mettez à jour le nombre de messages non lus dans la barre de navigation
          updateUnreadMessagesCount();
        }
      }
    };

    xhr.send();
  }
});
