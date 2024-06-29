<?php

// Ajout des fonctionnalités du thème
add_action('after_setup_theme', function() {
    // Prise en charge des balises de titre <title> du thème
    add_theme_support('title-tag');

    // Prise en charge des menus personnalisés
    add_theme_support('menus');

    // Enregistrement des emplacements de menus : Menu principal et Menu pied de page
    register_nav_menus(array(
        'primary_menu' => __('Menu Principal'),
	    'footer_menu'  => __('Menu Pied de Page'),
    ));
});

function modal_scripts() {
    if (strpos($_SERVER['REQUEST_URI'], '/photos/') !== false) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('modal-js', get_stylesheet_directory_uri() . '/assets/js/modal.js', array('jquery'), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'modal_scripts');




// Enqueue les styles du thème parent et le style personnalisé généré à partir de Sass
function theme_enqueue_styles() {
    // Ajout du style du thème parent
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // Ajout du style personnalisé généré à partir de Sass, dépendant du style du thème parent
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/styles/style.css', array('parent-style'), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');


// Fonction pour charger le script lightbox.js
function charger_lightbox_js() {
    // Utilisation de wp_enqueue_script pour ajouter le fichier JS
    wp_enqueue_script(
        'lightbox-js', // Handle du script
        get_template_directory_uri() . '/assets/js/lightbox.js', // Chemin vers le fichier lightbox.js dans assets/js
        array(), // Dépendances (si vous avez besoin que d'autres scripts soient chargés avant, indiquez-les ici)
        null, // Version du script (utilisez null pour désactiver la gestion de la version)
        true // Charger le script dans le footer (true) ou dans le header (false)
    );
}

// Accrocher la fonction au hook wp_enqueue_scripts
add_action('wp_enqueue_scripts', 'charger_lightbox_js');


// Enqueue le script "script.js" dépendant de jQuery et utilise AJAX
function custom_enqueue_scripts() {
    wp_enqueue_script('script', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), '', true);

    // Localize the script with the AJAX URL
    wp_localize_script('script', 'my_ajax_obj', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'custom_enqueue_scripts');
// Prise en charge des images mises en avant
add_theme_support('post-thumbnails');

function displayTaxonomies($nomTaxonomie) {
    if($terms = get_terms(array(
        'taxonomy' => $nomTaxonomie,
        'orderby' => 'name'
    ))) {
        foreach ( $terms as $term ) {
            echo '<option class="js-filter-item" value="' . $term->slug . '">' . $term->name . '</option>';
        }
    }
}



function filter() {
    $myAjaxRequest = new WP_Query(array(
        'post_type' => 'photos',
        'orderby' => 'date',
        'order' => $_POST['orderDirection'],
        'posts_per_page' => 4,
        'post__not_in' => $_POST['excluded'],
        
        'tax_query' =>
            array(
                'relation' => 'AND',
                $_POST['categorieSelection'] != "all" ?
                    array(
                        'taxonomy' => $_POST['categorieTaxonomie'],
                        'field' => 'slug',
                        'terms' => $_POST['categorieSelection'],
                    )
                : '',
                $_POST['formatSelection'] != "all" ?
                    array(
                        'taxonomy' => $_POST['formatTaxonomie'],
                        'field' => 'slug',
                        'terms' => $_POST['formatSelection'],
                    )
                : '',
            )
        )
    );
    $postnumber=$myAjaxRequest -> found_posts;
    displayImages($myAjaxRequest, $postnumber, true, true);
}
add_action('wp_ajax_nopriv_filter', 'filter');
add_action('wp_ajax_filter', 'filter');



function displayImages($galerie, $postnumber, $exit, $ajax=false) {
    $template= '';
    if($galerie->have_posts()) {
        while ($galerie->have_posts()) {
            ?>
<?php $galerie->the_post();  
$id= get_the_ID(); 
$thumbnail= get_the_post_thumbnail_url();
$fullscreen= get_template_directory_uri()."/assets/images/fullscreen.png";
$href= get_post_permalink();
$eyeicon= get_template_directory_uri()."/assets/images/eye_icon.png";
$imginfo= get_the_title();
$categoriesphoto= strip_tags(get_the_term_list($galerie->ID, 'categories_photo'));

$template.= <<<EOD

<div class="colonne photogalerie" data-id="$id">
  <div class="rangee">
    <img class="img-medium" src="$thumbnail" />
    <div>
      <div class="img-hover">
        <img class="btn-plein-ecran" src="$fullscreen"
          alt="Icône de plein écran" />
        <a href="$href">
          <img class="btn-oeil" src="$eyeicon"
            alt="Icône en fome d'oeil" />
        </a>
        <div class="img-infos">
          <p>$imginfo</p>
          <p>$categoriesphoto</p>
        </div>
      </div>
    </div>
  </div>
</div>
EOD;

 } 
 if ($ajax==true){
    echo json_encode(["template"=>$template, "postnumber"=>$postnumber]);
    
 } else {echo $template;}
    }
    else {
        echo json_encode("end");
    }
    wp_reset_postdata();
    if ($exit) {
        exit();
    }
}
