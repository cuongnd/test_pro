UPDATE "#__menu" SET "component_id" = (SELECT "id" FROM "#__extensions" WHERE "element" = 'com_joomlaupdate') WHERE "link" = 'index.php?option=com_joomlaupdate';
