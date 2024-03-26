// const addFormToCollection = (e) => {
//   const collectionPhoto = document.querySelector(
//     //Récupere l'element de la collection à partir de l'attribut data-collection (page twig.html)
//     e.currentTarget.dataset.collection
//   );
//   //Création Nouvelle Div
//   const item = document.createElement("div");
//   item.className = "mt-3";
//   // Remplacement de __name__ dans le prototype de la collection par l'index actuel de la collection pour générer le contenu dynamiquement
//   item.innerHTML = collectionPhoto.dataset.prototype.replace(
//     /__name__/g,
//     collectionPhoto.dataset.index
//   );
//   //Création d'un btn de suppression
//   let btnSupprimer = document.createElement("button");
//   btnSupprimer.className = "btn btn-info mt-3 btn-delete-photo";
//   btnSupprimer.id = "btn-delete-photo";
//   btnSupprimer.innerHTML = "supprimer l'image";
//   //Ajout du btn comme enfant de item
//   item.appendChild(btnSupprimer);
//   //Ajout de item à la collection
//   collectionPhoto.append(item);
//   //Incrémente l'index de la collection pour suivre nb d'elements ajoutés
//   collectionPhoto.dataset.index++;
//   document
//     .querySelectorAll(".btn-delete-photo")
//     .forEach((btn) =>
//       btn.addEventListener("click", (e) =>
//         e.currentTarget.parentElement.remove()
//       )
//     );
// };
// //Si chargement complet du DOM, pour chaque btn ajouter, au click, appel à la fonction ci dessus
// document.addEventListener("DOMContentLoaded", function () {
//   document
//     .querySelectorAll(".btn-add-photo")
//     .forEach((btn) => btn.addEventListener("click", addFormToCollection));
// });
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
