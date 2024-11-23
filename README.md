# Wordpress - Example Plugin URLs

* Author - Joseph Francis
* License: MIT

## What the plugin does:
This plugin is designed as an example of how to create custom urls
for a plugin.  In this example, if you load and activate the plugin, 
it will have two new urls for plugin access.

- YOURSITE/forexample/dashboard
- YOURSITE/forexample/welcome

## How to use:
- Copy the class, rename it to be specific to your plugin.  
- Change the custom post type name
- Add your own template parts in /tpl
- Update the pagerouter.php to route to new template parts based on post name being used

## How it works:
This code adds a custom post type and hides it from the admin side, 
making it a "hidden post type". 

It then overrides the post to point to a custom php page in the tpl directory. 
