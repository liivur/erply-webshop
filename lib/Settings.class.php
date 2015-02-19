<?php
class EWSettings
{
	public $settingsSlug;

	public function __construct() 
	{
        // Register menu item
        add_action('admin_menu', array($this, 'register_menu_item'));
        add_action('admin_init', array($this, 'register_settings'));

        $this->settingsSlug = 'erply-settings';
    }

    public function register_menu_item() 
    {
        add_submenu_page('erply-webshop', 'Settings', 'Settings', 'manage_options', $this->settingsSlug, array($this, 'view_index'));
    }

    public function view_index() 
    {
    	// var_dump(get_option('ew_connection_settings'));
    	echo '<form method="POST" action="options.php">';
    	settings_fields( $this->settingsSlug );
        do_settings_sections($this->settingsSlug);
        submit_button();
        echo '</form>';
    }

    public function register_settings()
    {
    	$connectorSectionFields = array(
            array(
                'id' => 'token',
                'label' => 'Token',
            ),
            array(
            	'id' => 'api_url',
            	'label' => 'Api url'
            )
        );

        $pagesSectionFields = array(
        	array(
	        	'id' => 'cartPage',
	        	'label' => 'Cart page'
        	)
        );

        $this->register_section(
        	'ew_connection_section',
        	'Connection settings',
        	$this->settingsSlug,
        	'ew_connection_settings',
        	'ew_connection_section',
        	$connectorSectionFields,
        	'Siia pane ühenduse teema (token)'
        );

        $this->register_section(
        	'ew_pages_section',
        	'Pages settings',
        	$this->settingsSlug,
        	'ew_pages_settings',
        	'ew_pages_section',
        	$pagesSectionFields,
        	'Siia pane lehtede id-d'
        );
    }

    public function register_section($name, $title, $page, $settingName, $parentSection, $fields, $sectionDescription)
    {
    	// add_settings_section('ew-connection-settings', 'Connection Settings', array($this, 'section_info'), $page);
    	add_settings_section(
    		$name,
    		$title,
    		array($this, 'section_info'),
    		$page
    	);
    	$this->$name = $sectionDescription;

    	if(!empty($settingName)) {
            if(!empty($fields))
                $this->populate_fields($fields, $page, $name, $settingName);

            register_setting( $page, $settingName);    
        }
    }

    public function populate_fields($fields, $page, $section, $setting)
    {
    	foreach ($fields as $field)
    	{
    		add_settings_field(
    			$field['id'],
    			$field['label'],
    			array($this, 'create_form_field'),
    			$page,
    			$section,
    			array(
    				'name' => $field['id'],
    				'setting' => $setting,
                    'type' => isset($f['type']) ? $f['type'] : null,
                    'default' => isset($f['default']) ? $f['default'] : '',
                    'options' => isset($f['options']) ?$f['options'] : '',
                    'description' => isset($f['description']) ?$f['description'] : ''
    			)
    		);
    	}
    }

    public function section_info($sectionID)
    {
    	echo $this->$sectionID['id'];
    }

    public function create_form_field($args){
        $option = get_option($args['setting']);
        $option = isset($option[$args['name']]) ? $option[$args['name']] : null;

        if($args['type'] == 'checkbox') : ?>
            <input type="checkbox" 
                name="<?=$args['setting'] ?>[<?= $args['name'] ?>]" 
                id="ecs_setting[<?= $args['name'] ?>]" 
                value="1" 
                <?php checked( 1, $option, true ) ?> />
        <?php elseif($args['type'] == 'select'): ?>
            <select name="<?=$args['setting'] ?>[<?= $args['name'] ?>]" id="ecs_setting[<?= $args['name'] ?>]">
                <?php foreach($args['options'] as $k => $v): ?>
                    <option value="<?php echo $k ?>" <?php selected( $option, $k); ?>><?php echo $v ?></option>
                <?php endforeach; ?>
            </select>
        <?php elseif($args['type'] == 'textarea'): ?>
            <textarea 
            style="height: 200px; width: 450px;"
            name="<?=$args['setting'] ?>[<?= $args['name'] ?>]" id="ecs_setting[<?= $args['name'] ?>]"><?=$option;?></textarea>
        <?php else: ?>
            <input style="width: 300px;" type="text"
                id="ecs_setting[<?= $args['name'] ?>]"
                name="<?=$args['setting'] ?>[<?= $args['name'] ?>]" 
                value="<?=$option;?>" />
        <?php
        endif;

        echo !empty($args['description']) ? '<br><small>'. $args['description'].'</small>' : '';
    }
}