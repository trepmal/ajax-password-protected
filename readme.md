# Ajax Password Protected

Load password protected content over ajax.

# Installation

## 1. wp-cli

```
wp plugin install https://github.com/trepmal/ajax-password-protected/archive/master.zip --activate
```

## 2. manually
 - Log into WordPress with administrator privileges (ability to upload plugins)
 - Download [https://github.com/trepmal/ajax-password-protected/archive/master.zip](https://github.com/trepmal/ajax-password-protected/archive/master.zip)
 - Plugins -> Add New -> Upload zip from last step
 - Activate


----

# Customization

This plugin may not work with custom themes. When the password is submitted, the post title and content are returned and inserted based on commonly used classes for the title and content elements. You may need to edit the js file and replace `entry-title` and `entry-content` with your theme's classes.

For more complex content. You may prefer to return fully-formed content using `get_template_part` in the ajax callback. Due to the many possible variations, I can't go into details here. If you're not sure how to proceed, please get in touch with your friendly neighborhood developer for assistance.