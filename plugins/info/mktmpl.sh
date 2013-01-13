#!/bin/sh
templates="wow_styleV default defaultV WoWMaevahEmpire WoWMaevahEmpireV WoWMoonclaw01 WoWMoonclaw01V wowV m9wow3eq"
files="admin/manage admin/settings info"
folders="admin"
for template in $templates 
do
	echo "Creating template files for $template"
	for file in $files
	do
		mkdir templates/$template
		touch templates/$template/index.html
		svn add templates/$template
		svn add templates/$template/index.html

		for folder in $folders
		do
			mkdir templates/$template/$folder
			touch templates/$template/$folder/index.html
			svn add template/$template/$folder/index.html
		done

		sed s:wow_style:$template:g templates/wow_style/$file.html > templates/$template/$file.html
		svn add templates/$template/$file.html
	done
done
