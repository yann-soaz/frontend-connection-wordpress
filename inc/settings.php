<?php
class YS_Login_Settings {
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page() {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Options de connection', 
            'manage_options', 
            'connection-options', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
        // Set class property
        $this->options = get_option( 'ys_connection' );
        ?>
        <div class="wrap">
            <h1>Options de connections</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'ys_connection_settings' );
                do_settings_sections( 'ys-connection-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {        
        register_setting(
            'ys_connection_settings', // Option group
            'ys_connection', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'ys-connection-settings-main', // ID
            'Paramètres de base', // Title
            array( $this, 'print_section_info' ), // Callback
            'ys-connection-admin' // Page
        );  

        add_settings_field(
            'hide_admin', // ID
            'Cacher l\'administration', // Title 
            array( $this, 'hide_admin_callback' ), // Callback
            'ys-connection-admin', // Page
            'ys-connection-settings-main' // Section           
        );

        add_settings_field(
            'allow_register', 
            'Inscription permises', 
            array( $this, 'allow_register_callback' ), 
            'ys-connection-admin', 
            'ys-connection-settings-main'
        );

        add_settings_field(
            'redirect_url', 
            'Url de redirection', 
            array( $this, 'redirect_url_callback' ), 
            'ys-connection-admin', 
            'ys-connection-settings-main'
        );

        add_settings_field(
            'connection_url', 
            'Page de connection', 
            array( $this, 'connection_url_callback' ), 
            'ys-connection-admin', 
            'ys-connection-settings-main'
        );

        add_settings_section(
            'ys-connection-advanced', // ID
            'Paramètres avancés', // Title
            array( $this, 'print_advanced_info' ), // Callback
            'ys-connection-admin' // Page
        );  

        add_settings_field(
            'allow_admin', 
            'Autoriser l\'accès à l\'administration :', 
            array( $this, 'allow_admin_for_callback' ), 
            'ys-connection-admin', 
            'ys-connection-advanced'
        );

        add_settings_field(
            'role_created', 
            'Role des utilisateurs à l\'inscription :', 
            array( $this, 'role_created_callback' ), 
            'ys-connection-admin', 
            'ys-connection-advanced'
        );

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ) {
        $new_input = array();
        if( isset( $input['redirect_url'] ) )
            $new_input['redirect_url'] = esc_attr( $input['redirect_url'] );
        if( isset( $input['connection_url'] ) )
            $new_input['connection_url'] = esc_attr( $input['connection_url'] );
        if( isset( $input['role_created'] ) )
            $new_input['role_created'] = esc_attr( $input['role_created'] );

        if( isset( $input['hide_admin'] ) )
            $new_input['hide_admin'] = absint( $input['hide_admin'] );
        if( isset( $input['allow_register'] ) )
            $new_input['allow_register'] = absint( $input['allow_register'] );
        if( isset( $input['allow_admin'] ) )
            foreach($input['allow_admin'] as $allowed) {
                $new_input['allow_admin'][] = esc_attr( $allowed );
            }

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info() {
        print 'définissez les options du module de connection :';
    }

    /** 
     * Print the Section text
     */
    public function print_advanced_info() {
        print 'Selectionnez les options avancés :';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function hide_admin_callback() {
        printf(
            '<input type="checkbox" id="hide_admin" value="1" name="ys_connection[hide_admin]" %s /><p class="description">Cache l\'administration a ceux qui n\'ont pas le rôle administrateur ou l\'un des rôles selectionnés dans les options avancées.</p>',
            ( $this->options['hide_admin'] == 1 ) ? 'checked' : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function allow_register_callback() {
        printf(
            '<input type="checkbox" id="allow_register" value="1" name="ys_connection[allow_register]" %s /><p class="description">Autorise la création de comptes utilisateurs sur le formulaire</p>',
            ( $this->options['allow_register'] == 1 ) ? 'checked' : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function connection_url_callback() {
        $pages = get_pages();
        $home = get_option('siteurl');
        print '<select name="ys_connection[connection_url]" id="connection_url">';
        print '<option '.((isset($this->options['connection_url']) && $this->options['connection_url'] == $home) ? 'selected' : '').' value="'.$home.'">Accueil</option>';
        foreach ( $pages as $page ) {
            $link = get_page_link( $page->ID );
            $option = '<option '.((isset($this->options['connection_url']) && $this->options['connection_url'] == $link) ? 'selected' : '').' value="' . $link . '">';
            $option .= $page->post_title;
            $option .= '</option>';
            print $option;
          }
        print '</select>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function redirect_url_callback() {
        $pages = get_pages();
        $home = get_option('siteurl');
        print '<select name="ys_connection[redirect_url]" id="redirect_url">';
        print '<option '.((isset($this->options['redirect_url']) && $this->options['redirect_url'] == $home) ? 'selected' : '').' value="'.$home.'">Accueil</option>';
        foreach ( $pages as $page ) {
            $link = get_page_link( $page->ID );
            $option = '<option '.((isset($this->options['redirect_url']) && $this->options['redirect_url'] == $link) ? 'selected' : '').' value="' . $link . '">';
            $option .= $page->post_title;
            $option .= '</option>';
            print $option;
          }
        print '</select>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function allow_admin_for_callback() {
        foreach ($this->get_roles() as $name => $role) {
            print '<label><input type="checkbox" id="allow_'.$role.'" name="ys_connection[allow_admin][]" value="'.$name.'" '.((!empty($this->options['allow_admin']) && in_array($name, $this->options['allow_admin'])) ? 'checked' : '').' />'.$role.'</label><br/>';
        }
        print '<p class="description" id="tagline-description">Liste des rôles autorisés à accéder a l\'administration (si la case limiter l\'accès à l\'administration est cochée)</p>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function role_created_callback() {
        foreach ($this->get_roles() as $name => $role) {
            print '<label><input type="radio" id="create_'.$role.'" name="ys_connection[role_created]" value="'.$name.'" '.((!empty($this->options['role_created']) && $this->options['role_created'] == $name) ? 'checked' : '').' />'.$role.'</label><br/>';
        }
        print '<p class="description" id="tagline-description">Role donné aux utilisateurs lros de leur insciption en front-end (si autorisée)</p>';
    }

    /** 
     * Get the roles list
     */
    public function get_roles() {
        $wp_roles = new WP_Roles();
        $roles = $wp_roles->get_names();
        $roles = array_map( 'translate_user_role', $roles );
    
        return $roles;
    }
}

if( is_admin() )
    $my_settings_page = new YS_Login_Settings();
