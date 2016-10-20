<?php

add_action('init', 'of_options');
if (!function_exists('of_options')) {

    function of_options() {
        // VARIABLES
        $themename = 'VideoCraft Pro Theme';
        $shortname = "of";
        // Populate OptionsFramework option in array for use in theme
        global $of_options;
        $of_options = inkthemes_get_option('of_options');
        //Front page on/off
        $file_rename = array("on" => "On", "off" => "Off");
        // Background Defaults
        $background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat', 'position' => 'top center', 'attachment' => 'scroll');
        //Stylesheet Reader
        $alt_stylesheets = array("default" => "default", "black" => "black");
        // Pull all the categories into an array
        $options_categories = array();
        $options_categories_obj = get_categories();
        foreach ($options_categories_obj as $category) {
            $options_categories[$category->cat_ID] = $category->cat_name;
        }

        // Populate OptionsFramework option in array for use in theme
        $contact_option = array("on" => "On", "off" => "Off");
		 //Listing publish mode
        $post_mode = array('pending' => 'Pending', 'publish' => 'Publish');

        // Pull all the pages into an array
        $options_pages = array();
        $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
        $options_pages[''] = 'Select a page:';
        foreach ($options_pages_obj as $page) {
            $options_pages[$page->ID] = $page->post_title;
        }
		
        // If using image radio buttons, define a directory path
        $imagepath = get_template_directory_uri() . '/images/';

        $options = array();
        $options[] = array("name" => "General Settings",
            "type" => "heading");

        $options[] = array("name" => "Custom Logo",
            "desc" => "Choose your own logo. Optimal Size: 300px Wide by 90px Height.",
            "id" => "inkthemes_logo",
            "type" => "upload");

        $options[] = array("name" => "Custom Favicon",
            "desc" => "Specify a 16px x 16px image that will represent your website's favicon.",
            "id" => "inkthemes_favicon",
            "type" => "upload");

        $options[] = array("name" => "Tracking Code",
            "desc" => "Paste your Google Analytics (or other) tracking code here.",
            "id" => "inkthemes_analytics",
            "std" => "",
            "type" => "textarea");
			
		
		$options[] = array("name" => "Enable Terms & Conditions Block on Registration page.",
                "desc" => "Check on for enabling terms & conditions on registration page",
                "id" => "reg_terms",
                "std" => "on",
                "type" => "radio",
                "options" => $file_rename);
				
       $options[] = array("name" => "Terms &amp; Conditions Url",
                "desc" => "Enter url for terms and conditions.",
                "id" => "vc_terms",
                "std" => '',
                "type" => "text");
		
					
	   $options[] = array("name" => "Front Page On/Off",
            "desc" => "Check on for enabling front page or check off for enabling blog page in front page",
            "id" => "re_nm",
            "std" => "on",
            "type" => "radio",
            "options" => $file_rename);
			
	
		
		
	
				
      	
//****=============================================================================****//
//****-----------This code is used for creating color styleshteet options----------****//							
//****=============================================================================****//				
$options[] = array("name" => "Video Listing Setting",
            "type" => "heading");
			
$options[] =array("name" => "Video Submission Status",
                "desc" => "Set whether you want to put the uploded video by user to Instantly publish or pending mode. By default video submission would be in Pending Mode",
                "id" => "video_post_mode",
                "std" => "pending",
                "type" => "select",
                "options" => $post_mode);	

						
//****=============================================================================****//
//****-----------This code is used for creating color styleshteet options----------****//							
//****=============================================================================****//		
$options[] = array("name" => "Advertising Banner Setting",
            "type" => "heading");
			
$options[] = array("name" => "Header Banner",
            "desc" => "Enter your code for header banner if you want",
            "id" => "inkthemes_header_banner",
            "std" => "",
            "type" => "textarea");   

$options[] = array("name" => "Video page Banner",
            "desc" => "Enter your code for video page banner if you want",
            "id" => "inkthemes_page_banner",
            "std" => "",
            "type" => "textarea"); 
			
			
			
      		
//****=============================================================================****//
//****-----------This code is used for creating color styleshteet options----------****//							
//****=============================================================================****//				
        $options[] = array("name" => "Styling Options",
            "type" => "heading");
        $options[] = array("name" => "Theme Stylesheet",
            "desc" => "Select your themes alternative color scheme.",
            "id" => "inkthemes_altstylesheet",
            "std" => "default",
            "type" => "select",
            "options" => $alt_stylesheets);
        $options[] = array("name" => "Custom CSS",
            "desc" => "Quickly add some CSS to your theme by adding it to this block.",
            "id" => "inkthemes_customcss",
            "std" => "",
            "type" => "textarea");

//****=============================================================================****//
//****-------------This code is used for creating social logos options-------------****//					
//****=============================================================================****//

        $options[] = array("name" => "Social Logos",
            "type" => "heading");
			
		$options[] = array("name" => "Facebook URL",
            "desc" => "Enter your Facebook URL if you have one",
            "id" => "inkthemes_facebook",
            "std" => "#",
            "type" => "text");
			
        $options[] = array("name" => "Twitter URL",
            "desc" => "Enter your Twitter URL if you have one",
            "id" => "inkthemes_twitter",
            "std" => "#",
            "type" => "text");      
			

        $options[] = array("name" => "Rss URL",
            "desc" => "Enter your Rss URL if you have one",
            "id" => "inkthemes_rss",
            "std" => "#",
            "type" => "text");

//------------------------------------------------------------------//
//-------------This code is used for creating SEO description-------//							
//------------------------------------------------------------------//						
        $options[] = array("name" => "SEO Options",
            "type" => "heading");
        $options[] = array("name" => "Meta Keywords (comma separated)",
            "desc" => "Meta keywords provide search engines with additional information about topics that appear on your site. This only applies to your home page. Keyword Limit Maximum 8",
            "id" => "inkthemes_keyword",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Meta Description",
            "desc" => "You should use meta descriptions to provide search engines with additional information about topics that appear on your site. This only applies to your home page.Optimal Length for Search Engines, Roughly 155 Characters",
            "id" => "inkthemes_description",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Meta Author Name",
            "desc" => "You should write the full name of the author here. This only applies to your home page.",
            "id" => "inkthemes_author",
            "std" => "",
            "type" => "textarea");
			
		//****=============================================================================****//
//****-------------This code is used for creating Bottom Footer Setting options-------------****//					
//****=============================================================================****//			
        $options[] = array("name" => "Footer Settings",
            "type" => "heading");
        $options[] = array("name" => "Footer Text",
            "desc" => "Enter text you want to be displayed on Footer",
            "id" => "inkthemes_footertext",
            "std" => "",
            "type" => "textarea");
        

        inkthemes_update_option('of_template', $options);
        inkthemes_update_option('of_themename', $themename);
        inkthemes_update_option('of_shortname', $shortname);
    }

}
?>
