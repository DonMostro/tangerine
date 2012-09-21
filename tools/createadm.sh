#!/bin/bash
ZWC_ADMPORTAL_PATH=$ZWC_ADMPORTAL
DEST_PATH=$PWD

read -p "Ingrese adaptador de DB (por defecto Pdo_Mysql):" DB_ADAPTER  
#DB_ADAPTER=Pdo_Mysql
DB_ADAPTER=${DB_ADAPTER:-Pdo_Mysql}

echo " "
echo Ingrese nombre de su DB NAME:
read DB_NAME

echo " "
echo Ingrese nombre de su DB USER:
read DB_USER

echo " "
echo Ingrese su DB PASSWORD:
read DB_PASS


echo Se seleccionaron los sig parámetros: DB_ADAPTER:$DB_ADAPTER, DB:$DB_NAME, USER:$DB_USER, PASSWORD:$DB_PASS
read -p "¿Desea crear y poblar tablas básicas MySQL de AdmPortal? (S/N) (por defecto S):" CREATE_TABLES    
#CREATE_TABLES=S
CREATE_TABLES=${CREATE_TABLES:-S}
#CREATE_TABLES=$( echo "$CREATE_TABLES:" | tr -s  '[:lower:]'  '[:upper:]' )

if [ "$CREATE_TABLES" = "S" ]; then
	read -p "Ingrese Usuario con privilegios para importal datos (por defecto $DB_USER):" DB_ROOT_USER  
	DB_ROOT_USER=${DB_ROOT_USER:-$DB_USER}
	read -p "Ingrese Password Usuario con privilegios para importal datos (por defecto $DB_PASS):" DB_ROOT_PASS 
	DB_ROOT_PASS_USER=${DB_ROOT_PASS_USER:-$DB_USER}

	echo mysql -u$DB_ROOT_USER -p$DB_ROOT_PASS $DB_NAME \< $ZWC_ADMPORTAL_PATH/docs/db/admportal.sql
	mysql -u$DB_ROOT_USER -p$DB_ROOT_PASS $DB_NAME < $ZWC_ADMPORTAL_PATH/docs/db/admportal.sql
	echo mysql -u$DB_ROOT_USER -p$DB_ROOT_PASS $DB_NAME \< $ZWC_ADMPORTAL_PATH/docs/db/basicdata.sql
	mysql -u$DB_ROOT_USER -p$DB_ROOT_PASS $DB_NAME < $ZWC_ADMPORTAL_PATH/docs/db/basicdata.sql	
fi


echo " "
echo Creando definiciones proyecto Zend Framework en $DEST_PATH/web/ 
zf create project web
cd web/

echo Borrando prefijo \'Application_\' por defecto.
sed -i 's/Application_//g' $DEST_PATH/web/.zfproject.xml

echo " "
echo Creando Controladores.

#zf create controller Index  
zf create action components -c Index 
zf create action tabs -c Index 
zf create action tabsDojo -c Index 
zf create action modules -c Index
zf create action login -c Index 
zf create action logout -c Index 

zf create controller Ajax index-action-included=1
zf create action loading -c Ajax 

zf create controller Functions index-action-included=1

zf create controller Cache index-action-included=1

zf create controller Uploads index-action-included=1

zf create controller Objects index-action-included=1
zf create action multiUpdate -c Objects 



echo " "
echo Creando Modelos.

zf create model AclModulesModel
zf create model AclPermissionsModel
zf create model AclRolesModel
zf create model AclUsersModel
zf create model LogBookModel
zf create model PermissionsModel
zf create model PersonalInfoModel
zf create model SettingsModel

echo " "
zf create module components


echo " "
echo Creando proyecto AdmPortal en $DEST_PATH/web

mkdir -p $DEST_PATH/web/application/
mkdir -p $DEST_PATH/web/public/

cp -r $ZWC_ADMPORTAL_PATH/application/* $DEST_PATH/web/application/ 
cp -r $ZWC_ADMPORTAL_PATH/public/* $DEST_PATH/web/public/ 

#echo Borrando definiciones por default obsoletas.
#sed resources/d $DEST_PATH/web/application/configs/application.ini
#sed dbTable/d $DEST_PATH/web/.zfproject.xml

echo Guardando configuracion de base de datos en $DEST_PATH/web/application/configs/application.ini
zf configure dbadapter "adapter=$DB_ADAPTER&username=$DB_USER&password=$DB_PASS&dbname=$DB_NAME"

echo Creando definiciones de tablas ZF en $DEST_PATH/web/application/models/DbTable ...
zf create dbtable.from-database

echo Adaptando Tablas ZF a Tablas de AdmPortal
echo " "
find $DEST_PATH/web/application/models/DbTable/ -type f -exec sed -i 's/Model_//' {} \;
find $DEST_PATH/web/application/models/DbTable/ -type f -exec sed -i 's/Zend_Db_Table_Abstract/Zwei_Db_Table/' {} \;


echo \*\*\* Listo, para configurar el servidor con VHOST revise ejemplo en $DEST_PATH/web/docs/README.txt. \*\*\*
echo \*\*\* Si no desea usar VHOST considere el siguiente script para configurar un ALIAS de Apache. \*\*\*
echo " " 
cd ../
echo Alias /${PWD##*/} \"$DEST_PATH/web/public\"
echo \<Directory \"$DEST_PATH/web/public\"\>
echo    Options Indexes MultiViews FollowSymLinks
echo    AllowOverride All
echo    DirectoryIndex index.html index.php
echo \</Directory\>
echo " "
echo \*\*\* Si usa este ALIAS Apache deberá añadir la siguiente linea al comienzo de su archivo $DEST_PATH/web/public/.htaccess \*\*\*
echo " "
mkdir -p $DEST_PATH/web/log/
mkdir -p $DEST_PATH/web/cache/
echo RewriteBase /${PWD##*/}/
echo " "  
echo \*\*\* Deberá darles permisos de escritura a las carpetas $DEST_PATH/web/log/ y  $DEST_PATH/web/cache/  \*\*\*
echo Happy AdmPortal\'ing.








 
