#!/bin/bash

# Exit on any error non-zero
set -e

# You need to be root, sorry.
if [[ $EUID -ne 0 ]]; then
echo "This script requires elevated privileges to run. Are you root?"
exit
fi

MYSQLINSTALLED=true
command -v mysql >/dev/null 2>&1 || {echo "Mysql not installed" && MYSQLINSTALLED=false}

echo 'Create MySQL root password:'
read -s MYSQLROOTPASSWD

DATE=$(date +"%Y%m%d%H%M")

# Pre-populate the root password for mysql-server before install
debconf-set-selections <<< "mysql-server mysql-server/root_password password $MYSQLROOTPASSWD"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $MYSQLROOTPASSWD"

# Get mysql-server core

apt-get -y install mysql-server

MAINDB="donation"
RESULT=`mysqlshow --user=root --password=${MYSQLROOTPASSWD} $MAINDB | grep -v Wildcard | grep -o $MAINDB` || true;
if [ "$RESULT" == "$MAINDB" ]; then
echo "Database $MAINDB already exists"
else
echo "Database not found, creating..."
mysql -uroot -p${MYSQLROOTPASSWD} -e "CREATE DATABASE ${MAINDB} /*\!40100 DEFAULT CHARACTER SET utf8 */;"
mysql -uroot -p${MYSQLROOTPASSWD} -e "CREATE USER ${MAINDB}@localhost IDENTIFIED BY '${PASSWDDB}';"
mysql -uroot -p${MYSQLROOTPASSWD} -e "GRANT ALL PRIVILEGES ON ${MAINDB}.* TO '${MAINDB}'@'localhost';"
mysql -uroot -p${MYSQLROOTPASSWD} -e "FLUSH PRIVILEGES;"
fi

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

	echo "Creating IncDonationTable..."
	mysql -uroot -p${MYSQLROOTPASSWD} $MAINDB -e "
CREATE TABLE IncDonationTable (
		RefNum INT NOT NULL AUTO_INCREMENT,
		DonorID INT NOT NULL,
		Item VARCHAR(30) NOT NULL,
		Amount INT NOT NULL,
		PRIMARY KEY ( RefNum )
		);"

	echo "Creating OutDonationTable..."
	mysql -uroot -p${MYSQLROOTPASSWD} $MAINDB -e "
CREATE TABLE OutDonationTable (
		RefNum INT NOT NULL,
		DoneeID INT NOT NULL,
		Item VARCHAR(30) NOT NULL,
		Amount INT NOT NULL,
		PRIMARY KEY ( RefNum )
		);"
