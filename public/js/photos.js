// Fonction pour ajouter un formulaire à la collection
const addFormToCollection = (e) => {
  const collectionPhoto = document.querySelector(
    e.currentTarget.dataset.collection
  );

  const item = document.createElement("div");
  item.className = "mt-3";
  item.innerHTML = collectionPhoto.dataset.prototype.replace(
    /__name__/g,
    collectionPhoto.dataset.index
  );

  let btnSupprimer = document.createElement("button");
  btnSupprimer.className = "btn btn-info mt-3 btn-delete-photo";
  btnSupprimer.id = "btn-delete-photo";
  btnSupprimer.innerHTML = "supprimer l'image";

  item.appendChild(btnSupprimer);
  collectionPhoto.append(item);
  collectionPhoto.dataset.index++;

  btnSupprimer.addEventListener("click", (e) =>
    e.currentTarget.parentElement.remove()
  );
};

// Ajout d'un écouteur d'événement sur tous les boutons "Ajouter une image"
document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelectorAll(".btn-add-photo")
    .forEach((btn) => btn.addEventListener("click", addFormToCollection));
});
