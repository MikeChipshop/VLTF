<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title><?php wp_title( ' | ', true, 'right' ); bloginfo("name"); ?></title>
<?php 
wp_head(); 
if(!is_user_logged_in()){
	include "analytics.php";
}
?>
</head>
<body <?php body_class(); ?>>
<div id="page-wrapper">
<header>
	<div class="container thin flex">     
    	<div id="menu-toggle" class="flex">
        	<div class="bar-container">
                <b></b><b></b><b></b>
            </div>
        </div>
      
        <div id="main-logo" class="flex relative">
        	<a href="<?php echo home_url(); ?>">Vault</a>
        </div>
        
        <div id="search-tickets">
        	<a id="search-toggle">Search</a>
            <a class="cta" href="<?php echo get_the_permalink(66); ?>"><i>Get </i>tickets</a>
            <a id="basket" href="<?php echo home_url(); ?>/basket/"><span>Basket</span></a>
        </div>
    </div>
</header>
<div id="search-form">
	<form action="<?php echo home_url(); ?>/search/" autocomplete="off">
    	<input type="search" placeholder="What are you looking for?" name="term">
    </form>
</div>
<nav id="main-menu" class="flex centre">
	<div class="flex">
    	<div class="wrapper">
            <a class="closer"></a>
            <menu>
                <?php
                wp_nav_menu(array(
                    "menu" => "main-menu",
                ));
                ?>
            </menu>
        </div>
        <div class="wrapper">
        	<div>
    	    	<a class="cta white" href="<?php echo get_the_permalink(66); ?>">Get Tickets</a>
	            <a class="cta white" href="<?php echo get_the_permalink(1152); ?>">My Bookings</a>
            </div>
            <?php
			vault_social();
			?>
        </div>
    </div>
</nav>