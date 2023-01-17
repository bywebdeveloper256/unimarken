<?php
if ( !function_exists( 'unimarken_version' ) )
{
    function unimarken_version()
    {
        $data = get_plugins();

        return $data["unimarken/unimarken.php"]["Version"];
    }
}

if ( !function_exists( 'unimarken_textDomain' ) )
{
    function unimarken_textDomain()
    {
        $data = get_plugins();

        return $data["unimarken/unimarken.php"]["TextDomain"];
    }
}

if ( !function_exists( 'unimarken_get_template_part_plugin' ) )
{
    function unimarken_get_template_part_plugin( $slug, $name = null )
    {
        do_action( "unimarken_get_template_part_plugin_{$slug}", $slug, $name );
        
        $templates = array();

        if ( isset( $name ) ) $templates[] = "{$slug}-{$name}.php";
        
        $templates[] = "{$slug}.php";
        
        unimarken_locate_template_part_plugin( $templates, true, false );
    }
}

if ( !function_exists( 'unimarken_locate_template_part_plugin' ) )
{
    function unimarken_locate_template_part_plugin( $template_names, $load = false, $require_once = true ) 
    { 
        $located = '';

        foreach ( (array) $template_names as $template_name )
        { 
            if ( !$template_name ) continue; 
        
            /* search file within the PLUGIN_DIR_PATH only */ 
            if ( file_exists( unimarken_name_dir . '/templates/' . $template_name ) )
            { 
                $located = unimarken_name_dir . '/templates/' . $template_name; 
                break; 
            } 
        }
        
        if ( $load && '' != $located ) load_template( $located, $require_once );
        
        return $located;
    }
}

if ( !function_exists( 'unimarken_insert_logo' ) )
{
    function unimarken_insert_logo( int $id_post, $file )
    {
        $file_data = [
            'name'		=> $file['name'], 
            'tmp_name'	=> $file['tmp_name'],
        ];
        
        if( $file['type'] === "image/jpg" || $file['type'] === "image/jpeg" || $file['type'] === "image/png" )
        {
            $thumbid = media_handle_sideload( $file_data );
            
            if ( !is_wp_error( $thumbid ) )
            {
                return set_post_thumbnail( $id_post, $thumbid );
            }
        }

        return false;
    }
}

if ( !function_exists( 'unimarken_get_company_id' ) )
{
    function unimarken_get_company_id()
    {
        $query = array(
            'author'    => get_current_user_id(),
            'post_type' => 'business',
        );
         
        $result = new WP_Query( $query );
        
        if( !empty( $result->posts ) )
        {
            foreach( $result->posts as $post )
            {
                $post_id = $post->ID;
                break;
            }
            return $post_id;
        }
        return false;
    }
}

if ( !function_exists( 'unimarken_get_id_terms' ) )
{
    function unimarken_get_id_terms( $taxonomy, bool $true = false )
    {
        $terms = get_the_terms( unimarken_get_company_id(), $taxonomy );

        if( !empty( $terms ) )
        {
            if( $true )
            {
                $term_id = [];

                foreach ( $terms as $term )
                {
                    $term_id[] = $term->slug;
                }

                return $term_id;

            }else{
                foreach ( $terms as $term )
                {
                    $term_id = $term->slug;
                    break;
                }
                return $term_id;
            }
        }
        return '';
    }
}

if ( !function_exists( 'unimaken_selected_multiple' ) )
{
    function unimaken_selected_multiple( $taxonomy, $term )
    {
        $terms = unimarken_get_id_terms( $taxonomy, true );

        foreach( $terms as $v )
        { 
            $html = selected( $v, $term ); 

            if( !empty( $html ) )
            {
                break;
            }
        }

        return $html;
    }
}

if ( !function_exists( 'unimaken_get_role_user' ) )
{
    function unimaken_get_role_user()
    {
        if( is_user_logged_in() ):
        $user = wp_get_current_user();
        $role = ( array ) $user->roles;
        return $role[0];
        else:
        return false;
        endif;
    }
}

if ( !function_exists( 'unimaken_get_users_with_customer_user_role' ) )
{
    function unimaken_get_users_subscriber_administrator()
    {
        $ids = [];

        $subscribers = get_users( array( 'role__in' => array( 'subscriber', 'administrator' ) ) );

        foreach( $subscribers as $subscriber ):

            $ids[] = $subscriber->ID;

        endforeach;

        return $ids;
    }
}