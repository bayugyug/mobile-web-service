#!/bin/bash





fdir="/var/www/html/backup"
fname=$fdir/src-backup-rccl-mobile-webservice-$(date '+%Y-%m-%d_%H%M%S')-$$-$RANDOM.tar.gz

[[ ! -d "${fdir}" ]] && {
        mkdir -p ${fdir} 2>/dev/null
}

sdir="${fdir}/mobile-webservice"
cd ${sdir:-/var/www/html/mobile-webservice}

echo $fname

rm -f log/* 2>/dev/null

tar cvfz $fname ../mobile-webservice/

[[ "root" == "$LOGNAME" ]] && {
	chown -R apache.apache ${fdir}
}


echo $fname
exit
