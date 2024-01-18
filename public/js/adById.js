/*----------------------------------------------------------------------------------------------------------------*/
/*-----------------------------Agrandissement + navigation sur les photos au click--------------------------------*/
/*----------------------------------------------------------------------------------------------------------------*/

document.addEventListener("DOMContentLoaded", function () {
  //container des images avant affichage popup
  var imageContainer = document.getElementById("imageContainer");
  //contenant de l'image clickée et ses fleches
  var popup = document.getElementById("popup");

  var prevArrow = document.getElementById("prevArrow");
  var popupImageContainer = document.getElementById("popupImageContainer");
  var nextArrow = document.getElementById("nextArrow");
  var currentIndex = 0;

  // Initialisation popup
  popup.style.display = "none";

  // Gestion de l'événement clic sur le conteneur d'images
  imageContainer.addEventListener("click", function (event) {
    // Vérification si l'élément cliqué est une balise IMG
    if (event.target.tagName === "IMG") {
      // Conversion de la collection d'éléments IMG en tableau
      var imageElements = Array.from(
        imageContainer.getElementsByTagName("img")
      );
      //Récup et stocke l'index de l'élément IMG cliqué dans le tableau
      currentIndex = imageElements.indexOf(event.target);
      // Appel de la fonction d'ouverture de la popup avec l'index de l'image cliquée en paramètre
      openPopup(currentIndex);
    }
  });

  function openPopup(index) {
    currentIndex = index;
    //crée une img avec une classe
    var popupImage = document.createElement("img");
    popupImage.classList.add("popup-image");
    //récupère la src de la balise image avec l'index passé en paramètre
    popupImage.src = imageContainer.getElementsByTagName("img")[index].src;
    popupImage.alt = "Popup Image";

    //initialise à vide la div et lui injecte l'image paramétrée ci-dessus
    popupImageContainer.innerHTML = "";
    popupImageContainer.appendChild(popupImage);
    updateArrowsVisibility();
    popup.style.display = "flex";
  }

  //update la visibilité des fleches selon l'index de l'image parcourue
  function updateArrowsVisibility() {
    prevArrow.style.display = currentIndex > 0 ? "block" : "none";
    nextArrow.style.display =
      currentIndex < imageContainer.getElementsByTagName("img").length - 1
        ? "block"
        : "none";
  }

  //Fermeture de la popup
  popup.addEventListener("click", function () {
    closePopup();
  });
  function closePopup() {
    popup.style.display = "none";
    popupImageContainer.innerHTML = "";
  }

  /*---------------------------------------------------------------------------------------------------------------*/
  /*Fonctions au click sur les fleches avec empêchement de fermeture de la popup(comportement normal de closePopup)*/
  /*---------------------------------------------------------------------------------------------------------------*/

  prevArrow.addEventListener("click", function (event) {
    event.stopPropagation();
    prevImage();
  });

  nextArrow.addEventListener("click", function (event) {
    event.stopPropagation();
    nextImage();
  });

  function prevImage() {
    if (currentIndex > 0) {
      openPopup(currentIndex - 1);
    }
  }
  function nextImage() {
    var images = imageContainer.getElementsByTagName("img");
    if (currentIndex < images.length - 1) {
      openPopup(currentIndex + 1);
    }
  }
  /*----------------------------------------------------------------------------------------------------------------*/
});
