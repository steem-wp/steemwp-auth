<?php

    /**
     * Plugin Name: Steem WP Remote Auth Manager
     * Plugin URI: https://github.com/steem-wp/steemwp-auth
     * Description: Steem WP remote auth manager
     * Version: 0.0.1
     * Author: Steem WP
     * Author URI: http://www.steemwp.com
     */
    
    add_filter( 'the_posts', 'GENERATE_VIRTUAL_STEEMWP_REMOVE_AUTH_MANAGER_PAGES', -10 );
    
    function GENERATE_VIRTUAL_STEEMWP_REMOVE_AUTH_MANAGER_PAGES ( $posts ) {
        global $wp;
        
        //sample incoming url
        //https://steemwp.com/steemwp/remote-auth-in?scope=login,vote&state=http%3A%2F%2Flocalhost
        
        //sample outgoing url
        //https://app.steemconnect.com/oauth2/authorize?client_id=steemwp.com&redirect_uri=https%3A%2F%2Fsteemwp.com%2Fsteemwp%2Fremote-auth-out&scope=vote,login
        
        $sc2_base = 'https://app.steemconnect.com/oauth2/authorize?client_id=steemwp.com';
        $sc2_redirect = '&redirect_uri=https%3A%2F%2Fsteemwp.com%2Fsteemwp%2Fremote-auth-out';
        $sc2_login_base = $sc2_base . $sc2_redirect;
        
        $in_url_slug = 'steemwp/remote-auth-in';
        $out_url_slug = 'steemwp/remote-auth-out';
        
        if ( ! defined( 'VIRTUAL_STEEMWP_REMOTE_AUTH_MANAGER_PAGES' ) ) {
            if ( strtolower( $wp->request ) == $in_url_slug )  {
                if (isset($_GET['state']) and isset($_GET['scope'])) {
                    $sc2_state = "&state=" . $_GET['state'];
                    $sc2_scope = "&scope=" . $_GET['scope'];
                    $sc2_login_url = $sc2_login_base . $sc2_state . $sc2_scope;
                    exit ( wp_redirect( $sc2_login_url ) );
                }
            } else if ( strtolower( $wp->request ) == $out_url_slug ) {
                if (isset($_GET['access_token']) and isset($_GET['expires_in'])) {
                    
                    $client_url = add_query_arg(
                        array(
                        'username' => $_GET['username'],
                        'access_token' => $_GET['access_token'],
                        'scope' => $_GET['scope'],
                        'expires_in' => $_GET['expires_in'],
                        ),
                        urldecode($_GET['state'])
                    )
                    
                    exit ( wp_redirect( $client_url ) );
                    
                }
            }
        }    
    }

?>