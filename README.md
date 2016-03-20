README
======

Requerimientos Básicos

- PHP Version >= 5.4

- MYSQL Version >= 5


INSTALACION DE DEPENDENCIAS
===========================

1. Composer
	Automatiza la instalación y configuración las dependencias del lado servidor
	```
	curl -sS https://getcomposer.org/installer | php -- 
	mv composer.phar /usr/local/bin/composer
	```
    Ahora tenemos disponible el comando 'composer'

2. Bower
    Bower es un paquete NPM, por lo que instalaremos Node.js y NPM
	Automatiza la instalación y configuración las dependencias del lado cliente
	```
	sudo apt-get install nodejs npm nodejs-legacy
	sudo npm install bower -g
	```
	Ahora tenemos disponible el comando 'bower'
3. Ant 
	Se usa ant como herramienta de automatizacion de tareas tales como construcción y análisis estático del código.
	```
	sudo apt-get install ant
	sudo apt-get install nodejs npm nodejs-legacy
	```
 
    Para hacer un full build del proyecto ejecutar, en la raiz del proyecto
	```	
	ant install
	```
    
    Para la creacion de los Alias de Apache necesarios, ejecutar como super usuario.
        ```
        ant alias
        ```

    Para ver el detalle de las tareas disponibles.
	```
	cat build.xml
	``` 

Para evitar problemas de charset en los caracteres especiales en el despliegue web, se debe agregar 
al archivo php.ini cargado por apache.
    ```
	default_charset = "UTF-8" 
    ```
CREACION DE MANTENEDORES
========================
4. Moverse a una nueva carpeta y ejecutar 
```
	tangerine-create 
```
Se pedirá ingresar: 
-	Tipo de DB (MySQL por omisión), 
-	nombre de DB, Usuario DB, Password DB,  estos parámetros son de una instancia de DB ya existente o para crear una nueva DB.

Después se podrán escoger las opciones:

 - Creación automática de Base de Datos y Usuario (S/N) (N por omisión)
   escoger S en caso de no usar una DB ya existente, sólo se crea la DB
   si no existe.

	

 - Generación automática de tablas y datos base (S/N) (S por omisión)
   solo se (re)crean los datos básicos para el funcionamiento de
   Tangerine.

	

 - Copiar modelos, módulos y controladores de AdmPortal (N por omisión)
   escoger S sólo en caso de necesitar modificar modelos, módulos,
   controladores/vistas por defecto, tener en cuenta que en este caso de
   escoger S, se crea una copia del MVC, lo cual permite personalizar
   estos componentes pero las actualizaciones y mejoras que se hagan en
   Tangerine en estos elementos no serán reflejados.




TIP: para agregar nuevos módulos ZF ejecutar dentro del proyecto
$ zf create module {módulo} 

Los componentes deben ser creados con el comando "zf" para la generación automatica de tests
ver

 - zf create module  ?
 - zf create controller ?
 - zf create model ?
 - zf create db-table ?
 - zf create form ?


TESTING
=======
Se requieren las librerías 
- PHPUnit
- PHP_CodeSniffer
- PHPLOC
- PHP_Depend
- PHPMD
- PHPCPD
- phpDox
Las cuales también son usadas por Jenkins para integración continua y pueden ser instaladas con ant
```
ant global-testing-deps
```

GENERACIÓN DE PLANTILLAS DE TESTS UNITARIOS
-------------------------------------------
Los Controllers y Actions deben ser creados con la herramienta
```
zf
```
Debe incluirse al archivo php.ini cli (en Debian/Ubuntu '/etc/php5/cli/php.ini', En RHEL/Centos '/etc/php.ini')
```
INCLUDE_PATH {paths ya existentes}:{tangerine path}/vendor/phpunit/phpunit
```
En este punto zf intentará crear los Tests Unitarios, pero Zend Framework 1 por defecto no es compatible con PHPUnit 4 por lo que arrojará el siguiente error
```
zf create controller Pepito
Creating a controller {...blah}
Creating a controller test {...bleh}
PHP Fatal error: Class 'PHPUnit_Framework_TestCase' not found in {..blih}/vendor/zendframework/zendframework1/library/Zend/Test/PHPUnit/ControllerTestCase.php
PHP Stack trace:
{...bloh}
``` 
Para solucionar este problema se debe aplicar un parche a Zend_Test_PHPUnit
```
ant zend-test-patch
```

SELENIUM
--------
Para ejecutar los tests basados en browser se debe instalar Selenium server (Las pruebas con Selenium no están "Jenkinizadas").
Instalación de Selenium
- Descargar el .jar Selenium SERVER desde http://www.seleniumhq.org/download/
- Dejarlo el archivo en /usr/local/bin 
- Ejecutarlo como .jar
```
java -jar /usr/local/bin/selenium-server-standalone-2.42.2.jar
```


Para usarlo con phpunit debe estar instalada la extension phpunit-selenium la cual es instalada con composer.
Por convención los tests de selenium están escritos en web/tests/selenium/

Es necesario crear alias para selenium en ~/.bashrc (o equivalente).
```
alias selenium="java -jar /usr/local/bin/selenium-server-standalone-2.42.2.jar"
```
Ejecutar selenium antes de iniciar los tests unitarios


Se debe especificar la URL BASE en el archivo application.ini, reemplazando {BASE_URL} por la URL BASE del administrador web.
```
 zwei.uTesting.httpHost = "http://{BASE_URL}"
```

Los Tests se escriben en la carpeta web/tests

Ejemplo de configuración de suite de pruebas

```
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
    xsi:noNamespaceSchemaLocation="http://schema.phpunit.de/3.7/phpunit.xsd"
    bootstrap="./bootstrap.php" colors="true">
    <testsuite name="Application Test Suite">
        <directory>./application</directory>
        <directory>./library</directory>
        <directory>./selenium</directory>
    </testsuite>
    <php>
        <const name="PHPUNIT_USERNAME" value="gamelena"/>
        <const name="PHPUNIT_PASSWORD" value="gamelena"/>
        <const name="PHPUNIT_BROWSER" value="opera"/>
        <const name="PHPUNIT_WAITSECONDS" value="5"/>
    </php>
</phpunit>
```

---

FAQ
===

Bower puede dar problemas al intentar resolver dependencias por protocolo git, dando un mensaje similar a éste:
```
ECMDERR Failed to execute "git ls-remote --tags --heads git://github.com/dojo/dojo.git", exit code of #128 fatal: unable to connect to github.com: github.com[0: 192.30.252.129]: errno=Expiró el tiempo de conexión
```

La solución es usar https:// el lugar de git://

```
git config --global url."https://".insteadOf git://
```

