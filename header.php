<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package csomaster
 */

$site_logo = get_field('logo_full_white', 'option') ? get_field('logo_full_white', 'option') : '';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
	
	<link rel="stylesheet" href="https://use.typekit.net/fgb8qlz.css">
	<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@40,400,0,0" />


</head>

<body <?php body_class('xy-labels'); ?>> 
<?php wp_body_open(); ?>

<div id="scrollPercentage"></div>
<main id="page" class="site">
	<?php get_template_part('template-parts/headers/header', ''); ?>