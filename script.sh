#!/bin/bash
#Fichero de log para dominio www.sepd.es

#contador=0
fecha=`date +%d-%m-%Y_%H:%M:%S`
touch ./log/"$fecha.log"

echo  "********************************************************************************************************" >> ./log/"$fecha.log"
echo "                                           `date` " >> ./log/"$fecha.log"
echo  "********************************************************************************************************" >> ./log/"$fecha.log"
echo " " >> ./log/"$fecha.log"

if /opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/composer.phar install --optimize-autoloader
then 
echo "          El comando *composer install --optimize-autoloader* ha sido ejecutado correctamente" >> ./log/"$fecha.log"
else
echo "          ERROR en el comando *composer install --optimize-autoloader*" >> ./log/"$fecha.log"
/opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/composer.phar install --optimize-autoloader &>> ./log/"$fecha.log"
#contador=$(( $contador + 1 )) 
fi

if /opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan migrate 
then
echo "          El comando *php artisan migrate* ha sido ejecutado correctamente" >> ./log/"$fecha.log"
else
echo "          ERROR en el comando *php artisan migrate*" >> ./log/"$fecha.log"
/opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan migrate &>> ./log/"$fecha.log"
#contador=$(( $contador + 1 ))
fi

if /opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan db:seed
then
echo "          El comando *php artisan db:seed* ha sido ejecutado correctamente" >> ./log/"$fecha.log"
else
echo "          ERROR en el comando *php artisan db:seed*" >> ./log/"$fecha.log"
/opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan db:seed &>> ./log/"$fecha.log"
#contador=$(( $contador + 1 ))
fi

if /opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan clear-compiled
then 
echo "          El comando *php artisan clear-compiled* ha sido ejecutado correctamente" >> ./log/"$fecha.log"
else
echo "          ERROR en el comando *php artisan clear-compiled*" >> ./log/"$fecha.log"
/opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan clear-compiled &>> ./log/"$fecha.log"
#contador=$(( $contador + 1 ))
fi

if /opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan config:clear
then 
echo "          El comando *php artisan config:clear* ha sido ejecutado correctamente" >> ./log/"$fecha.log"
else
echo "          ERROR en el comando *php config:clear*" >> ./log/"$fecha.log"
/opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan config:clear &&>> ./log/"$fecha.log"
#contador=$(( $contador + 1 ))
fi

if /opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan route:clear
then 
echo "          El comando *php artisan route:clear* ha sido ejecutado correctamente" >> ./log/"$fecha.log"
else
echo "          ERROR en el comando *php artisan route:clear*" >> ./log/"$fecha.log"
/opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan route:clear &>> ./log/"$fecha.log"
#contador=$(( $contador + 1 ))
fi

if /opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan config:cache 
then
echo "          El comando *php artisan config:cache* ha sido ejecutado correctamente" >> ./log/"$fecha.log"
else
echo "          ERROR en el comando *php artisan config:cache*" >> ./log/"$fecha.log"
/opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan config:cache &>> ./log/"$fecha.log"
#contador=$(( $contador + 1 ))
fi

if /opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan route:cache 
then
echo "          El comando *php artisan route:cache* ha sido ejecutado correctamente" >> ./log/"$fecha.log"
else
echo "          ERROR en el comando *php artisan route:cache*" >> ./log/"$fecha.log"
/opt/plesk/php/7.2/bin/php /var/www/vhosts/sepd.es/prod.sepd.es/prod_SEPD/artisan route:cache &>> ./log/"$fecha.log"
#contador=$(( $contador + 1 ))
fi


echo " " >> ./log/"$fecha.log"
echo "---------------------------------------------------------------------------------------------------------------------------" >> ./log/"$fecha.log"
echo "##########################################################################" >> ./log/"$fecha.log"
echo "---------------------------------------------------------------------------------------------------------------------------" >> ./log/"$fecha.log"
echo " " >> ./log/"$fecha.log"

# Para el envio de email con informe
#if [ "$contador" -eq "0" ]
#then
#echo "Sin errores"
#else
cat "./log/"$fecha.log"" | mail -s "FRONT_END - Informe de ejecución del dia $fecha en prod.sepd.es"  "rafa.rivas@s3w.es"
#Javier Rosado
#cat "./log/fichero.log" | mail -s "Informe de ejecución de fichero.log"  "bpallin@sepd.es" #Belen Pallin
#cat "./log/fichero.log" | mail -s "Informe de ejecución de fichero.log"  "xavier.baz@s3w.es" #Xavier Baz
#fi
#
 
