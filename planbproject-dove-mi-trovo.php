<?php

/**
 * Plugin Name: "Dove mi trovo?" shortcodes
 * Description: Aggiunge i campi opzione personalizzati "dove mi trovo", "Aggiornato al" e "Distanza da casa" usabili con shortcode
 * Version: 0.2
 * Author: Giacomo Lanzi
 * Plugin URI: https://github.com/giacomolanzi/planbproject-dove-mi-trovo
 * GitHub Plugin URI: giacomolanzi/planbproject-dove-mi-trovo
 * GitHub Branch: main
 */

// Registra i nuovi endpoint
add_action('rest_api_init', function () {
    register_rest_route('wp/v2/custom', '/update-dove_mi_trovo', array(
        'methods' => 'POST',
        'callback' => 'update_dove_mi_trovo',
        'permission_callback' => 'is_user_administrator',
    ));

    register_rest_route('wp/v2/custom', '/update-aggiornato_al', array(
        'methods' => 'POST',
        'callback' => 'update_aggiornato_al',
        'permission_callback' => 'is_user_administrator',
    ));

    register_rest_route('wp/v2/custom', '/update-distanza_casa', array(
        'methods' => 'POST',
        'callback' => 'update_distanza_casa',
        'permission_callback' => 'is_user_administrator',
    ));

    register_rest_route('wp/v2/custom', '/batch-update', array(
        'methods' => 'POST',
        'callback' => 'handle_batch_update',
        'permission_callback' => 'is_user_administrator',
    ));
});

// Funzione per verificare se l'utente è un amministratore
function is_user_administrator()
{
    return current_user_can('manage_options');
}

// Funzione per aggiornare il campo dove_sono
function update_dove_mi_trovo(WP_REST_Request $request)
{
    $value = $request->get_param('value');
    if ($value) {
        update_option('options_dove_mi_trovo', $value);
        return new WP_REST_Response(array('success' => true, 'message' => 'Campo "dove_mi_trovo" aggiornato con successo!'), 200);
    } else {
        return new WP_Error('invalid_value', 'Valore non fornito o non valido', array('status' => 400));
    }
}

// Funzione per aggiornare il campo aggiornato_al
function update_aggiornato_al(WP_REST_Request $request)
{
    $value = $request->get_param('value');
    if ($value) {
        update_option('options_aggiornato_al', $value);
        return new WP_REST_Response(array('success' => true, 'message' => 'Campo "aggiornato_al" aggiornato con successo!'), 200);
    } else {
        return new WP_Error('invalid_value', 'Valore non fornito o non valido', array('status' => 400));
    }
}

// Funzione per aggiornare il campo distanza_casa
function update_distanza_casa(WP_REST_Request $request)
{
    $value = $request->get_param('value');
    if (isset($value)) {
        update_option('options_distanza_casa', $value);
        return new WP_REST_Response(array('success' => true, 'message' => 'Campo "update_distanza_casa" aggiornato con successo!'), 200);
    } else {
        return new WP_Error('invalid_value', 'Valore non fornito o non valido', array('status' => 400));
    }
}

// Funzione per gestire il batch update
function handle_batch_update($request)
{
    $parameters = $request->get_params();
    $results = [];

    if (isset($parameters['aggiornato_al'])) {
        $result = update_option('options_aggiornato_al', sanitize_text_field($parameters['aggiornato_al']));
        $results['aggiornato_al'] = $result ? 'success' : 'failure';
    }

    if (isset($parameters['dove_mi_trovo'])) {
        $result = update_option('options_dove_mi_trovo', sanitize_text_field($parameters['dove_mi_trovo']));
        $results['dove_mi_trovo'] = $result ? 'success' : 'failure';
    }

    if (isset($parameters['distanza_casa'])) {
        $result = update_option('options_distanza_casa', sanitize_text_field($parameters['distanza_casa']));
        $results['distanza_casa'] = $result ? 'success' : 'failure';
    }

    return new WP_REST_Response($results, 200);
}

// Funzione per lo shortcode [dove_mi_trovo]
function shortcode_dove_mi_trovo()
{
    // Ottieni il valore dell'opzione 'dove_mi_trovo'
    $value = get_option('options_dove_mi_trovo', ''); // Il secondo parametro è un valore predefinito nel caso in cui l'opzione non esista
    return esc_html($value); // Utilizza esc_html per garantire la sicurezza
}
add_shortcode('dove_mi_trovo', 'shortcode_dove_mi_trovo');

// Funzione per lo shortcode [aggiornato_al]
function shortcode_aggiornato_al()
{
    // Ottieni il valore dell'opzione 'aggiornato_al'
    $value = get_option('options_aggiornato_al', ''); // Il secondo parametro è un valore predefinito nel caso in cui l'opzione non esista
    return esc_html($value); // Utilizza esc_html per garantire la sicurezza
}
add_shortcode('aggiornato_al', 'shortcode_aggiornato_al');

// Funzione per lo shortcode [distanza_casa]
function shortcode_distanza_casa()
{
    // Ottieni il valore dell'opzione 'distanza_casa'
    $value = get_option('options_distanza_casa', ''); // Il secondo parametro è un valore predefinito nel caso in cui l'opzione non esista
    return esc_html($value); // Utilizza esc_html per garantire la sicurezza
}
add_shortcode('distanza_casa', 'shortcode_distanza_casa');
