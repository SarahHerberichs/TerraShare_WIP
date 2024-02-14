function updateUnreadMessagesCount() {
  //contient le nb de msg non lus
  var unreadMessagesCountElement = document.getElementById(
    "unread-messages-count"
  );
  //url de la route qui récupere le nb de msg non lus grace à la balise data-route
  var route = unreadMessagesCountElement.dataset.route;
  //Fetch dans l'url et met à jour l'élément qui contient le nb de msg non lus
  fetch(route)
    .then((response) => response.json())
    .then((data) => {
      unreadMessagesCountElement.textContent = data.count;
    });
}

document.addEventListener("DOMContentLoaded", function () {
  document.addEventListener("DOMContentLoaded", updateUnreadMessagesCount);
});
