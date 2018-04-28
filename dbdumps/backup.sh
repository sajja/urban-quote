#!/bin/bash
dumpFile="dump_`date +"%m_%d_%Y"`"
echo "Beging dumping the db to $dumpFile"
mysqldump -u root -p  urbanste_master > $dumpFile
echo "Done.."
