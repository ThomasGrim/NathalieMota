(function ($) {
  'use strict';

  // Variables pour la gestion de la lightbox
  var lightbox = document.getElementById('lightbox-container'); // Conteneur de la lightbox
  var btnFermetureLightbox = document.getElementById('close-lightbox'); // Bouton de fermeture de la lightbox

  // Ouvre une lightbox en plein écran pour afficher l'image cliquée
  $(document).on('click', '.btn-plein-ecran', function () {
    var image = $(this).parent().parent().prev(); // Récupère l'image associée
    var urlImage = image.attr('src'); // Récupère l'URL de l'image
    var creerImage = '<img src="' + urlImage + '" alt="Image agrandie" class="image-lightbox">'; // Crée l'élément img pour la lightbox
    $('.lightbox__image').html(creerImage); // Ajoute l'image à la lightbox
    transitionPopup($('.lightbox'), 1); // Affiche la lightbox
  });


  // Ferme la lightbox lorsque le bouton de fermeture est cliqué
  btnFermetureLightbox.onclick = function () {
      transitionPopup($('.lightbox'), 0);
  };

  // Ferme la lightbox lorsqu'on clique en dehors de l'image agrandie
  $(document).on('click', '.lightbox', function (event) {
      if (!$(event.target).hasClass('image-lightbox') && !$(event.target).closest('.image-lightbox').length) {
          transitionPopup($(this), 0); // Ferme la lightbox
      }
  });

  // Fonction pour gérer l'animation et la visibilité des popups (modale ou lightbox)
  function transitionPopup(element, opacity) {
      var dureeTransitionPopup = 500; // Assurez-vous de déclarer dureeTransitionPopup ici si nécessaire
      if (opacity == 1) {
          $(element).css('display', 'flex'); // Affiche l'élément en mode flex
      } else if (opacity == 0) {
          setTimeout(function () {
              $(element).css('display', 'none'); // Cache l'élément après la durée de transition
          }, dureeTransitionPopup);
      }
      $(element).animate(
          {
              opacity: opacity, // Anime l'opacité vers la valeur spécifiée (0 ou 1)
          },
          dureeTransitionPopup // Durée de l'animation
      );
  }
})(jQuery);






