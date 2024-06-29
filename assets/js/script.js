(function ($) {
  'use strict';

  // Ajoute la classe 'bouton' à tous les éléments avec la classe 'wpcf7-submit'
  $('.wpcf7-submit').addClass('bouton');

  // Variables pour la gestion des transitions et des éléments modaux
  var dureeTransitionPopup = 500; // Durée de la transition en millisecondes
  var modale = document.getElementById('modale-container'); // Conteneur de la modale
  var btnFermetureModale = document.getElementById('close-modale'); // Bouton de fermeture de la modale

  // Ouvre la modale lorsque l'on clique sur un élément avec la classe 'btn-modale'
  $('.btn-modale').click(function () {
    transitionPopup($('.modale'), 1);
  });

  // Ferme la modale lorsque le bouton de fermeture est cliqué
  btnFermetureModale.onclick = function () {
    transitionPopup($('.modale'), 0);
  };

  // Ferme la modale si on clique en dehors de celle-ci
  window.onclick = function (event) {
    if (event.target == modale) {
      transitionPopup($('.modale'), 0);
    }
  };

  // Fonction pour gérer l'animation et la visibilité des popups (modale)
  function transitionPopup(element, opacity) {
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

  // Initialisation du menu mobile
  let menuMobileOrigine = $('.header-mobile').height() * -1; // Position initiale du menu mobile hors écran
  let menuOuvert = -1; // Indicateur de l'état du menu (fermé)
  $('.header-mobile').css('margin-top', menuMobileOrigine); // Applique la position initiale au menu mobile

  // Gère l'ouverture et la fermeture du menu mobile
  $('.header__btn-menu').click(function () {
    if (menuOuvert == -1) {
      $('.header-mobile').css('opacity', '1'); // Rendre le menu mobile visible
      effetMenu(0, 0); // Applique l'effet pour ouvrir le menu
    } else {
      effetMenu(1, menuMobileOrigine); // Applique l'effet pour fermer le menu
      setTimeout(function () {
        $('.header-mobile').css('opacity', '0'); // Rend le menu mobile invisible après fermeture
      }, dureeTransitionPopup);
    }
  });

  // Fonction pour gérer les effets d'ouverture/fermeture du menu mobile
  function effetMenu(opacite, position) {
    setTimeout(function () {
      $('.header-desktop').css('opacity', opacite); // Change l'opacité de l'en-tête de bureau
    }, dureeTransitionPopup / 2);
    $('.header-mobile').animate(
      {
        'margin-top': position, // Anime la position du menu mobile
      },
      dureeTransitionPopup
    );
    menuOuvert = menuOuvert * -1; // Inverse l'état du menu (ouvert/fermé)
  }

  // Lorsque le document est prêt, ajoute un gestionnaire d'événements pour les boutons d'interaction photo
  $(document).ready(function () {
    $('.interaction-photo__btn').click(function () {
      var reference = $('#reference-photo').text(); // Récupère le texte de référence de la photo
      $('#reference-form-field').val(reference); // Remplit le champ de formulaire avec la référence
    });

    // Événements de survol pour les flèches gauche et droite
    $('.arrow-gauche, .arrow-droite').hover(function() {
      const thumbnailUrl = $(this).data("thumbnail-url");
      $('#miniaturePhoto').html('<img src="' + thumbnailUrl + '">').show();
    });

    // Gestion de la navigation des flèches
    $('.arrow-gauche').click(function() {
      window.location.href = $(this).data('target-url');
    });

    $('.arrow-droite').click(function() {
      window.location.href = $(this).data('target-url');
    });

  });

  // Gestion du chargement dynamique des photos
  let pageActuelle = 1; // Numéro de la page actuelle

  // Lorsque le bouton "charger plus" est cliqué, charge plus de contenu
  $('#btn-load-more').on('click', function () {
    pageActuelle++; // Incrémente le numéro de page
    ajaxRequest(true); // Charge plus de contenu
  });

  // Lorsque les filtres sont modifiés, recharge le contenu filtré
  $(document).on('change', '.js-filter-form', function (e) {
    e.preventDefault(); // Empêche le comportement par défaut du formulaire
    pageActuelle = 1; // Réinitialise le numéro de page
    ajaxRequest(false); // Recharge le contenu avec les nouveaux filtres
  });

  // Fonction pour envoyer une requête AJAX et mettre à jour la galerie de photos
  function ajaxRequest(chargerPlus) {
    const categorieSelection = $('#select-categorie').val(); // Récupère la catégorie sélectionnée
    const formatSelection = $('#select-format').val(); // Récupère le format sélectionné
    const ordre = $('#select-ordre').val(); // Récupère l'ordre de tri sélectionné
    let excluded = $('.photogalerie').map(function(){
      return $(this).data('id');
  }).get(); 
console.log(excluded)
if (!chargerPlus){
  excluded=""
}
    $.ajax({
      type: 'POST', // Type de requête
      url: my_ajax_obj.ajax_url, // URL de l'action AJAX
      dataType: 'html', // Type de données attendu en réponse
      data: {
        action: 'filter', // Action à effectuer côté serveur
        categorieTaxonomie: 'categories_photo', // Taxonomie de catégorie
        categorieSelection: categorieSelection, // Catégorie sélectionnée
        formatTaxonomie: 'format', // Taxonomie de format
        formatSelection: formatSelection, // Format sélectionné
        orderDirection: ordre, // Direction de l'ordre de tri
        excluded:excluded, // Page actuelle pour la pagination
      },
      success: function (resultat) {
        
        if (resultat=='undefined'||resultat=='null'){
          return
        }
        resultat=JSON.parse(resultat)
        console.log(resultat)
        if (chargerPlus) {
          $('.galerie__photos').append(resultat.template); // Ajoute les nouvelles photos à la galerie
        } else {
          $('.galerie__photos').html(resultat.template); // Remplace le contenu de la galerie par les nouvelles photos
        }

        

      },
      error: function (result) {
        console.warn(result); // Affiche une erreur en cas de problème
      },
    });
  }

})(jQuery);
