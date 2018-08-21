<?php


/** blog filter **/
function jkreativ_lite_get_blog_filter()
{
	defined( 'JEG_PAGE_ID' ) 	or define('JEG_PAGE_ID', $_REQUEST['blogid']);
	get_template_part('template/ajax-content');
	exit;
}

add_action('wp_ajax_get_blog_filter'			, 'jkreativ_lite_get_blog_filter');
add_action('wp_ajax_nopriv_get_blog_filter'		, 'jkreativ_lite_get_blog_filter');

/** gallery page more **/
function jkreativ_lite_get_gallery_pagemore()
{
	defined( 'JEG_PAGE_ID' ) 	or define('JEG_PAGE_ID', $_REQUEST['pageid']);
	defined( 'JEG_GALLERY_PAGE' ) 	or define('JEG_GALLERY_PAGE', $_REQUEST['page']);
	get_template_part('template/media-gallery');
	exit;
}


add_action('wp_ajax_get_gallery_pagemore'			, 'jkreativ_lite_get_gallery_pagemore');
add_action('wp_ajax_nopriv_get_gallery_pagemore'	, 'jkreativ_lite_get_gallery_pagemore');

/** gallery page more portfolio**/
function jkreativ_lite_gallery_pagemore_portfolio()
{
	defined( 'JEG_PAGE_ID' ) or define('JEG_PAGE_ID', $_REQUEST['pageid']);
	defined( 'JEG_GALLERY_PAGE' ) or define('JEG_GALLERY_PAGE', $_REQUEST['page']);
	get_template_part('template/portfolio/portfolio-gallery');
	exit;
}

add_action('wp_ajax_get_gallery_pagemore_portfolio'			, 'jkreativ_lite_gallery_pagemore_portfolio');
add_action('wp_ajax_nopriv_get_gallery_pagemore_portfolio'	, 'jkreativ_lite_gallery_pagemore_portfolio');


/** portfolio  **/
function jkreativ_lite_get_portfolio_filter()
{
	defined( 'JEG_PAGE_ID' ) or define('JEG_PAGE_ID', $_REQUEST['portfolioid']);
	defined( 'JEG_PORTFOLIO_PAGE' ) or define('JEG_PORTFOLIO_PAGE', $_REQUEST['page']);
	defined( 'JEG_CATEGORY' ) or define('JEG_CATEGORY', $_REQUEST['category']);
	get_template_part('template/portfolio/portfolio-filter');
	exit;
}

add_action('wp_ajax_get_portfolio_filter'				, 'jkreativ_lite_get_portfolio_filter');
add_action('wp_ajax_nopriv_get_portfolio_filter'		, 'jkreativ_lite_get_portfolio_filter');


/** portfolio single **/
function jkreativ_lite_get_single_portfolio_page()
{
	defined( 'JEG_PAGE_ID' ) or define('JEG_PAGE_ID', $_REQUEST['pageid']);
	defined( 'JEG_PORTFOLIO_ID' ) or define('JEG_PORTFOLIO_ID', $_REQUEST['portfolioid']);
	defined( 'JEG_CATEGORY' ) or define('JEG_CATEGORY', $_REQUEST['category']);
	get_template_part('template/portfolio/portfolio-single');
	exit;
}

add_action('wp_ajax_get_single_portfolio_page'				, 'jkreativ_lite_get_single_portfolio_page');
add_action('wp_ajax_nopriv_get_single_portfolio_page'		, 'jkreativ_lite_get_single_portfolio_page');


