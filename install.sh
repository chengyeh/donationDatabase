#!/bin/bash

WEBROOT="/var/www/html"
WORKINGDIR=`pwd`

# Exit on any error non-zero
set -e

echo -e ""

# You need to be root, sorry.
if [[ $EUID -ne 0 ]]; then
echo "This script requires elevated privileges to run. Are you root?"
exit
fi

while true
do
	echo "Create/Provide MySQL root password:"
	read -s -p "Password: " MYSQLROOTPASSWD
	echo
	read -s -p "Password (again): " MYSQLROOTPASSWD2
	echo
	[ "$MYSQLROOTPASSWD" = "$MYSQLROOTPASSWD2" ] && break
	echo "Passwords do not match."
done


DATE=$(date +"%Y%m%d%H%M")

echo -e "\nRunning setup...\n"

echo "Configuring mysql-server..."
# Pre-populate the root password for mysql-server before install
PKG_OK=$(dpkg-query -W --showformat='${Status}\n' mysql-server|grep "install ok installed") || true
echo Checking for mysql-server: $PKG_OK
if [ "" == "$PKG_OK" ]; then
debconf-set-selections <<< "mysql-server mysql-server/root_password password $MYSQLROOTPASSWD"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $MYSQLROOTPASSWD"

# Get mysql-server core
echo ""
apt-get -y install mysql-server
echo ""
fi


MAINDB="donation"
RESULT=`mysqlshow --user=root --password=${MYSQLROOTPASSWD} $MAINDB | grep -v Wildcard | grep -o $MAINDB` || true;
if [ "$RESULT" == "$MAINDB" ]; then
echo "Database $MAINDB already exists"
else
echo "Database not found, creating..."
mysql -uroot -p${MYSQLROOTPASSWD} -e "CREATE DATABASE ${MAINDB} /*\!40100 DEFAULT CHARACTER SET utf8 */;"
#mysql -uroot -p${MYSQLROOTPASSWD} -e "CREATE USER ${MAINDB}@localhost IDENTIFIED BY '${PASSWDDB}';"
#mysql -uroot -p${MYSQLROOTPASSWD} -e "GRANT ALL PRIVILEGES ON ${MAINDB}.* TO '${MAINDB}'@'localhost';"
mysql -uroot -p${MYSQLROOTPASSWD} -e "FLUSH PRIVILEGES;"
fi


if [ $(mysql -N -s -u root -p${MYSQLROOTPASSWD} -e "select count(*) from information_schema.tables where table_schema='$MAINDB' and table_name='DonorTable';") -eq 1 ]; then
	echo "DonorTable already exists."
else
echo "Creating DonorTable..."
mysql -uroot -p${MYSQLROOTPASSWD} $MAINDB -e "
CREATE TABLE DonorTable(
		DonorID INT NOT NULL AUTO_INCREMENT,
		FirstName VARCHAR(24) NOT NULL,
		LastName VARCHAR(24) NOT NULL,
		Telephone INT(10),
		Email VARCHAR(50) NOT NULL,
		Address VARCHAR(50),
		PRIMARY KEY ( DonorID )
		);"
fi

if [ $(mysql -N -s -u root -p${MYSQLROOTPASSWD} -e "select count(*) from information_schema.tables where table_schema='$MAINDB' and table_name='DoneeTable';") -eq 1 ]; then
	echo "DoneeTable already exists."
else
	echo "Creating DoneeTable..."
	mysql -uroot -p${MYSQLROOTPASSWD} $MAINDB -e "
CREATE TABLE DoneeTable (
		DoneeID INT NOT NULL AUTO_INCREMENT,
		FirstName VARCHAR(24) NOT NULL,
		LastName VARCHAR(24) NOT NULL,
		Telephone INT(10),
		Email VARCHAR(50) NOT NULL,
		Address VARCHAR(50),
		Age INT,
		Gender VARCHAR(6),
		Ethnicity VARCHAR(24),
		PRIMARY KEY ( DoneeID )
		);"
fi

if [ $(mysql -N -s -u root -p${MYSQLROOTPASSWD} -e "select count(*) from information_schema.tables where table_schema='$MAINDB' and table_name='IncDonationTable';") -eq 1 ]; then
	echo "IncDonationTable already exists."
else

	echo "Creating IncDonationTable..."
	mysql -uroot -p${MYSQLROOTPASSWD} $MAINDB -e "
CREATE TABLE IncDonationTable (
		RefNum INT NOT NULL AUTO_INCREMENT,
		DonorID INT NOT NULL,
		Item VARCHAR(30) NOT NULL,
		Amount INT NOT NULL,
		PRIMARY KEY ( RefNum )
		);"
fi

if [ $(mysql -N -s -u root -p${MYSQLROOTPASSWD} -e "select count(*) from information_schema.tables where table_schema='$MAINDB' and table_name='OutDonationTable';") -eq 1 ]; then
	echo "OutDonationTable already exists."
else
	echo "Creating OutDonationTable..."
	mysql -uroot -p${MYSQLROOTPASSWD} $MAINDB -e "
CREATE TABLE OutDonationTable (
		RefNum INT NOT NULL,
		DoneeID INT NOT NULL,
		Item VARCHAR(30) NOT NULL,
		Amount INT NOT NULL,
		PRIMARY KEY ( RefNum )
		);"
fi

echo -e "Mysql-server config complete.\n"

# Pre-populate the root password for apache2 before install
PKG_OK=$(dpkg-query -W --showformat='${Status}\n' apache2|grep "install ok installed") || true
echo Checking for apache2: $PKG_OK
if [ "" == "$PKG_OK" ]; then
# Get apache2
echo ""
apt-get -y install apache2 php5
echo ""
fi

cd $WEBROOT
echo "Packing up old webroot..."
tar czf oldwebroot-$DATE.tar.gz * --exclude="oldwebroot[.]*" || true
echo "Installing new web components..."
cp $WORKINGDIR/html/* $WEBROOT/ -r

echo -e "\nDone\n"
