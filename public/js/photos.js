console.log("salut");
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
  console.log(item);

  let btnSupprimer = document.createElement("button");
  btnSupprimer.className = "btn btn-info mt-3 btn-supprimer";
  btnSupprimer.id = "btn-supprimer";
  btnSupprimer.innerHTML = "supprimer l'image";
  item.appendChild(btnSupprimer);
  collectionPhoto.append(item);
  collectionPhoto.dataset.index++;
  document
    .querySelectorAll(".btn-supprimer")
    .forEach((btn) =>
      btn.addEventListener("click", (e) =>
        e.currentTarget.parentElement.remove()
      )
    );
};

document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelectorAll(".btn-ajouter")
    .forEach((btn) => btn.addEventListener("click", addFormToCollection));
});
