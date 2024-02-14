document.addEventListener("DOMContentLoaded", function () {
  //Tous les boutons mark as read
  var markAsReadButtons = document.querySelectorAll(".mark-as-read");
  //pour chaque bouton, au click, récupere l'id du message
  //et appelle fonction markAsRead (définie plus bas) + Masque le bouton
  markAsReadButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      var messageId = button.getAttribute("data-message-id");
      button.style.display = "none";
      markAsRead(messageId);
    });
  });

  //Fonction qui utilise une Requete ajax sur le message pour faire un update du message (le marquer comme lu)
  function markAsRead(messageId) {
    var xhr = new XMLHttpRequest();
    //Va modif le msg comme lu dans la BDD grace à la route update-message-status
    xhr.open("POST", "/update-message-status/" + messageId, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        //L'élément du message correspondant à l'id
        var messageElement = document.querySelector(".message-id-" + messageId);
        //verif si message est lu ou non
        if (messageElement) {
          var isRead = messageElement.classList.contains("message-read");
          if (!isRead) {
            //Mise à jour de l'affichage si pas lu
            var notReadElement =
              messageElement.querySelector(".message-not-read");
            var readElement = messageElement.querySelector(".message-read");

            if (notReadElement && readElement) {
              notReadElement.style.display = "none";
              readElement.style.display = "block";
            }
            messageElement.classList.add("message-read");
          }
          // Après avoir marqué le message comme lu,mise à jour du nb de msg dans la barre de navigation
          //Fonction déclarée dans le JS de la navbar (accessible dans la portée globale)
          updateUnreadMessagesCount();
        }
      }
    };

    xhr.send();
  }
});
