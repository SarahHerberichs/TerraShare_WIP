// document.addEventListener("DOMContentLoaded", function () {
//   var shortText = document.querySelectorAll(".short-text");
//   var fullText = document.querySelectorAll(".full-text");
//   var seeMoreTextBtn = document.querySelectorAll(".btn-more-text");
//   var reduceTextBtn = document.querySelectorAll(".btn-reduce-text");

//   console.log(shortText.length);
//   if (shortText.textContent < 250) {
//     fullText.style.display = "none";
//   }

//   reduceTextBtn.style.display = "none";
//   fullText.style.display = "none";

//   seeMoreTextBtn.addEventListener("click", function () {
//     shortText.style.display = "none";
//     fullText.style.display = "block";
//     reduceTextBtn.style.display = "inline-block";
//   });

//   reduceTextBtn.addEventListener("click", function () {
//     shortText.style.display = "block";
//     fullText.style.display = "none";
//     reduceTextBtn.style.display = "none";
//   });

//   // Vérifie initialement si le texte est tronqué pour afficher ou non le bouton "Réduire"
//   if (shortText.innerText.length < message.text.length) {
//     reduceTextBtn.style.display = "inline-block";
//   }
// });
document.addEventListener("DOMContentLoaded", function () {
  var shortTexts = document.querySelectorAll(".short-text");
  var fullTexts = document.querySelectorAll(".full-text");
  var seeMoreTextBtns = document.querySelectorAll(".btn-more-text");
  var reduceTextBtns = document.querySelectorAll(".btn-reduce-text");

  shortTexts.forEach(function (shortText, index) {
    var fullText = fullTexts[index];
    var seeMoreTextBtn = seeMoreTextBtns[index];
    var reduceTextBtn = reduceTextBtns[index];

    // Initial hide
    fullText.style.display = "none";
    reduceTextBtn.style.display = "none";

    // Event listeners
    seeMoreTextBtn.addEventListener("click", function () {
      shortText.style.display = "none";
      fullText.style.display = "block";
      reduceTextBtn.style.display = "inline-block";
    });

    reduceTextBtn.addEventListener("click", function () {
      shortText.style.display = "block";
      fullText.style.display = "none";
      reduceTextBtn.style.display = "none";
    });
  });
});
