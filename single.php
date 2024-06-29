<?php get_header();
/* if (have_posts()) { */
    /* while (have_posts()) {
        the_post(); */

// Get ACF fields
$photo = get_field('photos');
$reference = get_field('reference_photo');
$type = get_field('type_photo');
$title = get_the_title(); // Ajout de cette ligne pour récupérer le titre de la photo

// Get taxonomy terms
$annee_terms = get_the_terms(get_the_ID(), 'slug');
$categories = get_the_terms(get_the_ID(), 'categories_photo');
$format_terms = get_the_terms(get_the_ID(), 'format');
$categorie_name = $categories[0]->name;

$next_photo = get_next_post();
$previous_photo = get_previous_post();
$previous_thumbnail = $previous_photo ? get_the_post_thumbnail_url($previous_photo->ID, 'thumbnail') : '';
$next_thumbnail = $next_photo ? get_the_post_thumbnail_url($next_photo->ID, 'thumbnail') : '';
?>
<div class="page-photo bloc-page">

  <section class="bloc-photo colonnes">
    <div class="bloc-photo__description colonne">
      <h1><?php the_title() ?></h1>
      <p>Référence : <span id="reference-photo"><?php echo get_field('reference_photo'); ?></span></p>
      <p>Catégorie : <?php echo strip_tags(get_the_term_list($post->ID, 'categories_photo')); ?></p>
      <p>Format : <?php echo strip_tags(get_the_term_list($post->ID, 'format')); ?></p>
      <p>Type : <?php echo get_field('type_photo'); ?></p>
      <p>Année : <?php echo get_the_date('Y'); ?></p>
    </div>
    <img class="bloc-photo__image colonne" src="<?php the_post_thumbnail_url(); ?>">
  </section>

  <section class="interaction-photo colonnes">
    <div>
      <p class="texte">Cette photo vous intéresse ?</p>
      <input class="interaction-photo__btn bouton btn-modale" type="button" value="Contact">
    </div>

    
    <div class="interaction-photo__navigation" id="interaction-photo__navigation">
    <div class="arrows">
            <?php if (!empty($previous_photo)) : ?>
                <img class="arrow arrow-gauche" src="<?php echo get_template_directory_uri() . '/assets/images/arrow_left.png'; ?>" alt="Photo précédente" data-thumbnail-url="<?php echo $previous_thumbnail; ?>" data-target-url="<?php echo esc_url(get_permalink($previous_photo->ID)); ?>">
            <?php endif; ?>
            <?php if (!empty($next_photo)) : ?>
                <img class="arrow arrow-droite" src="<?php echo get_template_directory_uri() . '/assets/images/arrow_right.png'; ?>" alt="Photo suivante" data-thumbnail-url="<?php echo $next_thumbnail; ?>" data-target-url="<?php echo esc_url(get_permalink($next_photo->ID)); ?>">
            <?php endif; ?>
        </div>
    <div class="miniaturePhoto" id="miniaturePhoto">
        
    </div>
</div>




  </section>

  <section class="recommandations">
    <h2>Vous aimerez aussi</h2>
    <div class="recommandations__images colonnes">
      <?php
                $categorie = strip_tags(get_the_term_list($post->ID, 'categories_photo'));
                $random_images = new WP_Query(array (
                    'post_type' => 'photos',
                    'post__not_in' => array($post->ID),
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'categories_photo',
                            'field' => 'slug',
                            'terms' => $categorie,
                        ),
                    ),
                    'orderby' => 'rand',
                    'posts_per_page' => '2'));

                $numberOfSimilarPictures = $random_images->post_count;
                if ($numberOfSimilarPictures > 0) {
                    displayImages($random_images, false, false, false);
                }
                else {
                    echo '<p class="texte">Il n\'y a pas encore d\'autres photos à afficher dans cette catégorie.</p>';
                }
                /* wp_reset_postdata(); */
            ?>

    </div>
    <button class="recommandations__btn bouton" onclick="window.location.href='<?php echo site_url() ?>'">
      Toutes les photos
    </button>

  </section>

</div>

<?php

/* }
else {

} */

get_footer(); ?>
