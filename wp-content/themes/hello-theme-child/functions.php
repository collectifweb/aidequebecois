<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );
 
/* CHANGER LOGO DASHBOARD */
function my_login_logo_one() { 
?> 
<style type="text/css"> 
body.login div#login h1 a {
 background-image: url(/wp-content/themes/generatepress_child/dashboard-logo.png);  
background-size: 100px;
} 
</style>
 <?php 
} add_action( 'login_enqueue_scripts', 'my_login_logo_one' );


/* FORM COMMERCANT : champ catégorie */
// Debug de la soumission initiale
add_action('forminator_custom_form_submit_before_set_fields', 'debug_form_submission', 10, 3);

function debug_form_submission($entry, $form_id, $field_data) {
    error_log('=== Form Submission Debug ===');
    error_log('Form ID: ' . $form_id);
    error_log('Field Data: ' . print_r($field_data, true));
    error_log('Entry: ' . print_r($entry, true));
    error_log('========================');
}

// Debug du rendu du formulaire
add_action('forminator_before_form_render', 'debug_form_render', 10, 2);

function debug_form_render($id, $form_object) {
    error_log('=== Form Render Debug ===');
    error_log('Form ID: ' . $id);
    error_log('Form Object: ' . print_r($form_object, true));
    error_log('========================');
}

/* Ajout filtres au dropdown front-end */
add_filter(
    'forminator_cform_render_fields',
    function($wrappers, $model_id) {
        // Vérifie si c'est le bon formulaire (ID 161)
        if ($model_id != 161) {
            return $wrappers;
        }

        // Définir quels champs select doivent être peuplés
        $select_fields_data = array(
            'select-1' => 'categories-entreprise' 
        );

        foreach ($wrappers as $wrapper_key => $wrapper) {
            if (!isset($wrapper['fields'])) {
                continue;
            }

            if (
                isset($select_fields_data[$wrapper['fields'][0]['element_id']]) &&
                !empty($select_fields_data[$wrapper['fields'][0]['element_id']])
            ) {
                // Récupérer les termes de la taxonomie
                $terms = get_terms(array(
                    'taxonomy' => $select_fields_data[$wrapper['fields'][0]['element_id']],
                    'hide_empty' => false
                ));

                if (!empty($terms) && !is_wp_error($terms)) {
                    $new_options = array();
                    
                    // Option par défaut
                    $new_options[] = array(
                        'label' => 'Sélectionnez une catégorie',
                        'value' => '',
                        'limit' => '',
                        'key' => forminator_unique_key(),
                    );

                    // Ajouter chaque terme comme option
                    foreach ($terms as $term) {
                        $new_options[] = array(
                            'label' => $term->name,
                            'value' => $term->term_id,
                            'limit' => '',
                            'key' => forminator_unique_key(),
                        );
                    }

                    $opt_data['options'] = $new_options;

                    // Mettre à jour le champ si nécessaire
                    $select_field = Forminator_API::get_form_field($model_id, $wrapper['fields'][0]['element_id'], true);
                    if ($select_field) {
                        if ($select_field['options'][0]['label'] != $opt_data['options'][0]['label']) {
                            Forminator_API::update_form_field($model_id, $wrapper['fields'][0]['element_id'], $opt_data);
                            $wrappers[$wrapper_key]['fields'][0]['options'] = $new_options;
                        }
                    }
                }
            }
        }

        return $wrappers;
    },
    10,
    2
);

// Gérer l'assignation de la catégorie et checkbox après la soumission
add_action('forminator_custom_form_submit_before_set_fields', 'assigner_categorie_direct', 10, 3);

function assigner_categorie_direct($entry, $form_id, $field_data) {
    if ($form_id != 161) {
        return;
    }

    error_log('=== Debug Assignation Catégorie et Visibilité ===');

    $post_id = null;
    $categorie_id = null;
    $visibilite = false;

    // Parcourir les données du formulaire
    foreach ($field_data as $field) {
        if ($field['name'] === 'postdata-1' && isset($field['value']['postdata'])) {
            $post_id = intval($field['value']['postdata']);
            error_log("Post ID trouvé: {$post_id}");
        }
        if ($field['name'] === 'select-1') {
            $categorie_id = intval($field['value']);
            error_log("Catégorie ID trouvée: {$categorie_id}");
        }
        if ($field['name'] === 'checkbox-1' && !empty($field['value']) && in_array('one', $field['value'])) {
            $visibilite = true;
            error_log("Visibilité cochée: oui");
        }
    }

    if ($post_id) {
        // Assigner la catégorie
        if ($categorie_id) {
            $result = wp_set_object_terms($post_id, array($categorie_id), 'categories-entreprise', false);
            if (!is_wp_error($result)) {
                clean_post_cache($post_id);
                clean_object_term_cache($post_id, 'categories-entreprise');
            }
        }

        // Mettre à jour la visibilité
        if ($visibilite) {
            // Si vous utilisez ACF
            if(function_exists('update_field')) {
                update_field('visibilite_carte_bleue', true, $post_id);
            }
            
            // Mettre à jour aussi le meta standard
            update_post_meta($post_id, '_visibilite_carte_bleue', 'yes');
            
            error_log("Visibilité mise à jour avec 'yes'");
        } else {
            // Si vous utilisez ACF
            if(function_exists('update_field')) {
                update_field('visibilite_carte_bleue', false, $post_id);
            }
            
            // Mettre à jour aussi le meta standard
            update_post_meta($post_id, '_visibilite_carte_bleue', 'no');
            
            error_log("Visibilité mise à jour avec 'no'");
        }
    }

    error_log('=== Fin Debug Assignation Catégorie et Visibilité ===');
}
add_filter('acf/format_value/name=visibilite_carte_bleue', 'formater_visibilite', 10, 3);

function formater_visibilite($value, $post_id, $field) {
    if(get_post_meta($post_id, '_visibilite_carte_bleue', true) === 'yes') {
        return true;
    }
    return false;
}
/* Compteur de résultat Facet WP shortcode */
add_filter( 'facetwp_result_count', function( $output, $params ) {
    $output = $params['total'] . ' results';
    return $output;
}, 10, 2 );

/**
 * Fonction de débogage personnalisée qui affiche dans la console du navigateur
 */
function cw_debug_log($message) {
    // Enregistrer dans le fichier de log
    error_log($message);
    
    // Afficher dans la console du navigateur
    echo "<script>console.log('Debug: " . addslashes($message) . "');</script>";
}

/**
 * Collectif WEB - Tri des événements par date via la requête SQL directe
 * Cette fonction modifie la requête SQL pour trier correctement les dates au format d/m/Y
 */
add_filter('posts_orderby', 'cw_custom_event_orderby', 10, 2);
function cw_custom_event_orderby($orderby, $query) {
    // Vérifier si nous sommes dans le contexte FacetWP
    if (!isset($query->query_vars['facetwp'])) {
        return $orderby;
    }
    
    // Vérifier si nous traitons les événements
    if (isset($query->query_vars['post_type']) && 
        (is_array($query->query_vars['post_type']) && in_array('evenement', $query->query_vars['post_type']) || 
         'evenement' === $query->query_vars['post_type'])) {
        
        global $wpdb;
        
        // Déterminer l'ordre (ASC ou DESC)
// $order_direction = 'ASC'; // Pour trier du plus ancien au plus récent
         $order_direction = 'DESC'; // Pour trier du plus récent au plus ancien
        
        // Créer une clause ORDER BY personnalisée pour les dates au format d/m/Y
        $orderby = "
        (
            SELECT STR_TO_DATE(meta_value, '%d/%m/%Y')
            FROM {$wpdb->postmeta}
            WHERE post_id = {$wpdb->posts}.ID
            AND meta_key = 'date_debut_evenement'
            LIMIT 1
        ) $order_direction";
        
        // Ajouter un message dans la console pour confirmer que la fonction est appelée
        add_action('wp_footer', function() use ($order_direction) {
            echo "<script>console.log('Tri personnalisé des événements appliqué. Direction: $order_direction');</script>";
        });
    }
    
    return $orderby;
}
