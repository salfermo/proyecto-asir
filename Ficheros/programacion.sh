#!/bin/bash

# Tomamos la hora del sistema y, en programacion.txt, buscamos los usuarios correspondientes a esa hora.
HORA=$(date +"%H%M")
USUARIOS=$(grep $HORA programacion.txt | cut -d ":" -f2 | sed -e "s/\./ /g" | sed -e "s/\s$//")

# Creamos una captura con la cámara.
curl http://proyecto-asir.ddns.net:8080/0/action/snapshot > /dev/null

FECHA=$(date +"%Y%m%d%H%M%S")

for ID in $USUARIOS; do
        # Copiamos la captura realizada para cada usuario que la solicitó en la hora programada.
                cp /var/www/html/eventos/lastsnap.jpg /var/www/html/usuario$ID/privadas/$ID-$FECHA.jpg
done
