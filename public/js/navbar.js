function updateUnreadMessagesCount() {
  //contient le nb de msg non lus
  var unreadMessagesCountElement = document.getElementById(
    "unread-messages-count"
  );
  if (unreadMessagesCountElement !== null) {
    var route = unreadMessagesCountElement.dataset.route;

    //Fetch dans l'url (géré par MessageControlleur) et met à jour l'élément qui contient le nb de msg non lus
    fetch(route)
      .then((response) => response.json())
      .then((data) => {
        unreadMessagesCountElement.textContent = data.count;
      });
  }
}

document.addEventListener("DOMContentLoaded", function () {
  updateUnreadMessagesCount();
});
