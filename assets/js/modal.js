jQuery(document).ready(function($) {
    // Vérifiez si l'URL contient "/photos/"
    if (window.location.pathname.includes('/photos/')) {
        // Sélectionner le bouton qui ouvre la modal
        $('.btn-modale').on('click', function() {
            // Utiliser un délai pour s'assurer que la modal est complètement chargée
            setTimeout(function() {
                // Utiliser ACF pour récupérer la valeur du champ 'reference_photo'
                var referenceValue = $('#reference-photo').text().trim();

                if (referenceValue) {
                    // Injecter la valeur ACF dans le champ texte du formulaire Contact Form 7
                    var textField = $('input[name="your-subject"]');
                    if (textField.length > 0) {
                        textField.val(referenceValue);
                    }
                }
            }, 500); // Ajustez le délai si nécessaire
        });
    }
});
