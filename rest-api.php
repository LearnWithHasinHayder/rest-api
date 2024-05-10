<?php
/**
 * Plugin Name: Rest API Examples
 * Description: Examples of how to use Rest API
 * Version: 1.0
 * F3jFmurJikYSY1%^
 */

class Rest_API {
    public function __construct() {
        // add_action('init',[$this,'init']);
        add_action('rest_api_init', [$this, 'register_api']);
    }

    public function register_api() {
        register_rest_route('wedevs-academy/v1', '/test', [
            'methods' => 'GET',
            'callback' => [$this, 'test_get']
        ]);

        register_rest_route('wedevs-academy/v1', '/test', [
            'methods' => 'POST',
            'callback' => [$this, 'test_post']
        ]);

        register_rest_route('wedevs-academy/v1', '/test', [
            'methods' => 'PUT',
            'callback' => [$this, 'test_put']
        ]);
        //register http://academy.local/wp-json/wedevs-academy/v1/author/3
        register_rest_route('wedevs-academy/v1', '/author/(?P<author_id>\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'get_author']
        ]);

        //register http://academy.local/wp-json/wedevs-academy/v1/author/3/field/field_name
        register_rest_route('wedevs-academy/v1', '/author/(?P<author_id>\d+)/field/(?P<field_name>\w+)', [
            'methods' => 'GET',
            'callback' => [$this, 'get_author_field']
        ]);

        //register http://academy.local/wp-json/wedevs-academy/v1/author/3/?field=field_name
        // register_rest_route('wedevs-academy/v1', '/author/(?P<author_id>\d+)', [
        //     'methods' => 'GET',
        //     'callback' => [$this, 'get_author']
        // ]);

        //register a new endpoint to check currently authenticated user
        register_rest_route('wedevs-academy/v1', '/me', [
            'methods' => 'GET',
            'callback' => [$this, 'get_current_user'],
            // 'permission_callback' => function($data) {
            //     // return is_user_logged_in();
            //     // return current_user_can('edit_posts');
            // }
            //public api
            'permission_callback' => '__return_true'
        ]);

    }

    function get_current_user($data) {
        //check if user is logged in
        // if(!is_user_logged_in()){
        //     return rest_ensure_response([
        //         'success' => false,
        //         'message' => 'You are not logged in'
        //     ]);
        // }

        $user = wp_get_current_user();
        return rest_ensure_response([
            'success' => true,
            'message' => 'Hello World GET',
            'data' => [
                'id' => $user->ID,
                'name' => $user->display_name,
                'email' => $user->user_email
            ]
        ]);
    }

    function get_author_field($data) {
        $author_id = $data->get_param('author_id');
        $field_name = $data->get_param('field_name');
        $author = get_user_by('id', $author_id);
        return rest_ensure_response([
            'success' => true,
            'message' => 'Hello World GET',
            'data' => [
                'id' => $author->ID,
                'field' => $field_name,
                'value'=>$author->$field_name
            ]
        ]);
    }

    function get_author($data) {
        $author_id = $data->get_param('author_id');
        $author = get_user_by('id', $author_id);

        //check if there is any field like ?field=field_name
        if($data->get_param('field')){
            $field_name = $data->get_param('field');
            return rest_ensure_response([
                'success' => true,
                'message' => 'Hello World GET',
                'data' => [
                    'id' => $author->ID,
                    'name' => $author->display_name,
                    'email' => $author->user_email,
                    $field_name => $author->$field_name
                ]
            ]);
        }
        // return rest_ensure_response([
        //     'success' => true,
        //     'message' => 'Hello World GET',
        //     'data' => [
        //         'id' => $author->ID,
        //         'name' => $author->display_name,
        //         'email' => $author->user_email
        //     ]
        // ]);
    }

    function test_get($data) {
        return rest_ensure_response([
            'success' => true,
            'message' => 'Hello World GET'
        ]);
    }

    function test_post($data) {
        $params = $data->get_params();
        return rest_ensure_response([
            'success' => true,
            'message' => 'Hello World POST',
            'params' => $params
        ]);
    }

    function test_put($data) {
        $params = $data->get_params();
        return rest_ensure_response([
            'success' => true,
            'message' => 'Hello World PUT',
            'params' => $params
        ]);
    }
}

new Rest_API();