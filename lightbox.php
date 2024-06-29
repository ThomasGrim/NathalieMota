<!-- Modale Lightbox -->
<?php
        $galerie = get_post(); // Assuming you want the current post object
        $reference = get_field('reference_photo');
        $categories = get_the_terms(get_the_ID(), 'categories_photo');
        

        ?>
<div class="lightbox" id="lightbox-container">
  <div class="lightbox__content">
    <button class="lightbox__close btn-close" id="close-lightbox" type="button">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/close_icon.png" alt="Croix de fermeture" />
    </button>
    <div class="lightbox_container">
            <button class="lightbox_prev">
                <img class="arrow arrowNavL" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/navArrowL.png" alt="nav arrow left"/>
                <span>Précédente</span>
            </button>
            <div>
    <div class="lightbox__image">
      <!-- L'image en plein écran sera injectée ici via JavaScript -->
    </div>
        <div class="img-infos-lightbox">
          <p><?php echo get_field('reference_photo'); ?></p>
          <p><?php echo strip_tags(get_the_term_list($post->ID, 'categories_photo')); ?></p>
        </div>
    </div>
            <button class="lightbox_next">
                <span>Suivante</span>
                <img class="arrow arrowNavR" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/navArrowR.png" alt="nav arrow right"/>
            </button>
        </div>

  </div>
</div>
