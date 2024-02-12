// document.addEventListener("DOMContentLoaded", function () {
//   var unreadMessagesCountElement = document.getElementById(
//     "unread-messages-count"
//   );
//   //utilisation de l'api dataset pour récupérer dataroute (dans la balise qui attend le nb de message recu dont la data-route mene au chemin recupérant le nb de msg)
//   var route = unreadMessagesCountElement.dataset.route;

//   fetch(route)
//     .then((response) => response.json())
//     .then((data) => {
//       //injection du nb de msg recus
//       unreadMessagesCountElement.textContent = data.count;
//     });
// });
// Ajoutez la méthode updateUnreadMessagesCount à votre fichier navbar.js

function updateUnreadMessagesCount() {
  var unreadMessagesCountElement = document.getElementById(
    "unread-messages-count"
  );
  var route = unreadMessagesCountElement.dataset.route;

  fetch(route)
    .then((response) => response.json())
    .then((data) => {
      unreadMessagesCountElement.textContent = data.count;
    });
}

document.addEventListener("DOMContentLoaded", function () {
  var unreadMessagesCountElement = document.getElementById(
    "unread-messages-count"
  );

  var route = unreadMessagesCountElement.dataset.route;

  fetch(route)
    .then((response) => response.json())
    .then((data) => {
      unreadMessagesCountElement.textContent = data.count;
    });
});
