#!/bin/sh
cd sql
echo "BUILDING DB..."
for i in drop_all.sql sequences.sql tables.sql functions.sql functions_user.sql functions_tornei.sql functions_tornei_gen.sql; do
	echo "### Working on $i..."
	psql aronne aronne < $i
done 
echo "POPULATING..."
for i in populate.sql populate_Italiano1.sql populate_Libero1.sql populate_Eliminazione1.sql populate_Misto1.sql; do
	echo "### Working on $i..."
	psql aronne aronne < $i
done
for i in populate_Italiano2.sql populate_Libero2.sql populate_Eliminazione2.sql populate_Misto2.sql; do
	echo "### Working on $i..."
	psql aronne aronne < $i
done
cd ..
echo "Done!"
exit 0