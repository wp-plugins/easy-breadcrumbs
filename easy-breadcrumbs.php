<?php
/*
Plugin Name: Easy Breadcrumbs
Plugin URI:  http://coffeecupweb.com/easy-breadcrumbs/
Description: A handy plugin which allows you to quickly create breadcrumb navigation.
Author: Harshal Limaye
Version: 1.0
Author URI: http://coffeecupweb.com/
*/
function ccw_breadcrumb() {
	global $post;
	
	$separator = " &gt; ";
	$separator = apply_filters('ccw_separator',$separator);
	
	$text = "You Are Here : ";
	$text = apply_filters('ccw_you_are_here',$text);
	
	$home = "Home";
	$home = apply_filters('ccw_home',$home);
	
	$link = '<a href="%1$s">%2$s</a>';
	
	$pagenotfound = "404! Page Not Found!";
	
	if($post)
		$parent = $post->post_parent;
	
	$output = '<div>';
	
	$output .= $text;

	if(is_front_page() || is_home()){

		$output .= $home;
		
		} else {
				
			$output .= '<a href="'.home_url().'">';
				
				$output .= $home;
			
			$output .= '</a>';
			
			$output .= $separator;
			
			if(is_category() || is_single()) {
				
				$output .= get_the_category_list(', ');
				
				if(is_single()) {
				
					$output .= $separator;
					
					$output .= get_the_title();
				
				}
			
			} elseif(is_year() || is_month() || is_day()) {
				
				$output .= '<a href="'.get_year_link(get_the_time('Y')).'">';
					
					$output .= get_the_time('Y');
				
				$output .= '</a>';
				
				$output .= $separator;
				
				if(is_month() || is_day()) {
					
					$output .= '<a href="'.get_month_link(get_the_time('Y'), get_the_time('F')).'">';
						
						$output .= get_the_time('F');
					
					$output .= '</a>';
					
					$output .= $separator;
					
					if(is_day()) {
						
						$output .= '<a href="'.get_month_link(get_the_time('Y'), get_the_time('F'), get_the_time('d')).'">';
							
							$output .= get_the_time('d');
						
						$output .= '</a>';
					
					}
				}
			} elseif(is_search()) {
				
				$output .= 'Search results for term : <strong>'.get_search_query().'</strong>';
			
			} elseif(is_page() && !$parent) {
				
				$output .= get_the_title();
			
			} elseif(is_page() && $parent && $parent != get_option('page_on_front')) {
				
				$title = get_the_title();
				
				$pages = array();  

                while ($parent) {  
                    
					$post = get_post($parent);  
                    
					$pages[] = sprintf($link, get_permalink($post->ID), get_the_title($post->ID));  
                    
					$parent = $post->post_parent;
                
				}
				
				$pages = array_reverse($pages);
				
				for ($i = 0; $i < count($pages); $i++) {  
                    
					$output .=  $pages[$i];  
                    
					if ($i != count($pages)-1) $output .=  $separator;
                
				}
				
				$output .=  $separator;
				
				$output .= $title;
			
			} elseif(is_tag()) {
				
				$output .= single_tag_title('', false);
			
			} elseif(is_author()) {
				
				$output .= get_the_author();
			
			} elseif(is_404()) {
				
				$output .= $pagenotfound;
			
			}
		}

	$output .= '</div>';
	
	echo $output;
}