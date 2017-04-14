#!/bin/bash



APACHEWEBROOT="/var/www"
DNSNAME="donation.nichnologist.net"
MAINDBUSER="donation"
MAINDB="donation"
DATABASEADDR="127.0.0.1"

EMAILRELAYUSER="kueecs.team10@gmail.com"
EMAILRELAYPASSWD="passwordGoesHere"
CONTACTEMAILADDR="kueecs.team10@gmail.com"
COMPANYNAME='KU team 10'

TZ="America/Chicago"


WEBROOT="$APACHEWEBROOT/donation/"
WORKINGDIR=`pwd`

WEBADDR="https://$DNSNAME/"
CAPTCHAS="false"
EMAILVER="true"


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
# Check if mysql-server installed, continue on non-0 and we handle the failure
PKG_OK=$(dpkg-query -W --showformat='${Status}\n' mysql-server|grep "install ok installed") || true
echo Checking for mysql-server: $PKG_OK
# Pre-populate the root password for mysql-server before install, but only if not installed.
if [ "" == "$PKG_OK" ]; then
debconf-set-selections <<< "mysql-server mysql-server/root_password password $MYSQLROOTPASSWD"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $MYSQLROOTPASSWD"

# Get mysql-server core
echo ""
apt-get -y install mysql-server
echo ""
fi

# Check if database exists
RESULT=`mysqlshow --user=root --password=${MYSQLROOTPASSWD} $MAINDB | grep -v Wildcard | grep -o $MAINDB` || true;
# Create database if not exists
if [ "$RESULT" == "$MAINDB" ]; then
  echo "Database $MAINDB already exists"
else
  echo "Database not found, creating..."
  mysql -uroot -p${MYSQLROOTPASSWD} -e "CREATE DATABASE ${MAINDB} /*\!40100 DEFAULT CHARACTER SET utf8 */;"
fi
# Create database user if not exists
if [ $(mysql -N -s -u root -p${MYSQLROOTPASSWD} -e "SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user = '$MAINDBUSER');") -eq 1 ]; then
  echo "Creating privileged database user $MAINDBUSER..."
  mysql -uroot -p${MYSQLROOTPASSWD} -e "DROP USER '${MAINDBUSER}'@'localhost';"
  mysql -uroot -p${MYSQLROOTPASSWD} -e "FLUSH PRIVILEGES;"
  mysql -uroot -p${MYSQLROOTPASSWD} -e "CREATE USER '${MAINDBUSER}'@'localhost' IDENTIFIED BY '${MYSQLROOTPASSWD}';"
fi
# Grant privileges to database user
echo "Granting required privileges..."
mysql -uroot -p${MYSQLROOTPASSWD} -e "GRANT ALL PRIVILEGES ON ${MAINDB}.* TO '${MAINDBUSER}'@'localhost';"
echo "Flushing..."
mysql -uroot -p${MYSQLROOTPASSWD} -e "FLUSH PRIVILEGES;"



###### START CREATE DATABASE TABLES ######

if [ $(mysql -N -s -u root -p${MYSQLROOTPASSWD} -e "select count(*) from information_schema.tables where table_schema='$MAINDB' and table_name='IncDonationTable';") -eq 1 ]; then
	echo "IncDonationTable already exists."
else
	echo "Creating IncDonationTable..."
	mysql -uroot -p${MYSQLROOTPASSWD} $MAINDB -e "
    CREATE TABLE IncDonationTable (
    RefNum INT NOT NULL AUTO_INCREMENT,
    DonorID INT NOT NULL,
    ItemID VARCHAR(30) NOT NULL,
    Amount INT NOT NULL,
    ActualAmount INT NOT NULL,
    Value INT NOT NULL,
    PledgeDate TIMESTAMP NOT NULL,
    ReceiveDate TIMESTAMP,
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
    ItemID VARCHAR(30) NOT NULL,
    Amount INT,
    FulfillDate TIMESTAMP,
    RequestDate TIMESTAMP,
    PRIMARY KEY ( RefNum )
    );"
fi

if [ $(mysql -N -s -u root -p${MYSQLROOTPASSWD} -e "select count(*) from information_schema.tables where table_schema='$MAINDB' and table_name='UserTable';") -eq 1 ]; then
	echo "UserTable already exists."
else
	echo "Creating UserTable..."
	mysql -uroot -p${MYSQLROOTPASSWD} $MAINDB -e "
    CREATE TABLE UserTable (
    UserID INT NOT NULL AUTO_INCREMENT,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    State VARCHAR(50),
    City VARCHAR(50),
    Zip INT,
    AddressLine1 VARCHAR(100),
    AddressLine2 VARCHAR(100),
    CumulativeRecValue INT,
    Telephone VARCHAR(40),
    Email VARCHAR(255) NOT NULL,
    PassHash VARCHAR(100) NOT NULL,
    PassSalt VARCHAR(50) NOT NULL,
    FlagAdmin INT NOT NULL,
    FlagUser INT NOT NULL,
    FlagDonor INT NOT NULL,
    FlagDonee INT NOT NULL,
    Age INT,
    HouseholdSize INT,
    Ethnicity INT,
    Gender VARCHAR(1),
    Income INT,
    Active INT,
    lastTaxGenDate TIMESTAMP NOT NULL,
    PRIMARY KEY ( UserID )
    );"
fi

if [ $(mysql -N -s -u root -p${MYSQLROOTPASSWD} -e "select count(*) from information_schema.tables where table_schema='$MAINDB' and table_name='InventoryTable';") -eq 1 ]; then
	echo "InventoryTable already exists."
else
	echo "Creating InventoryTable..."
	mysql -uroot -p${MYSQLROOTPASSWD} $MAINDB -e "
    CREATE TABLE InventoryTable (
    ItemID INT NOT NULL AUTO_INCREMENT,
    Category INT,
    Name VARCHAR(30) NOT NULL,
    Amount INT,
    Threshold INT,
    PRIMARY KEY ( ItemID )
    );"
fi

if [ $(mysql -N -s -u root -p${MYSQLROOTPASSWD} -e "select count(*) from information_schema.tables where table_schema='$MAINDB' and table_name='CategoriesTable';") -eq 1 ]; then
	echo "CategoriesTable already exists."
else
	echo "Creating CategoriesTable..."
	mysql -uroot -p${MYSQLROOTPASSWD} $MAINDB -e "
    CREATE TABLE CategoriesTable (
    CategoryNum INT NOT NULL AUTO_INCREMENT,
    Name VARCHAR(30) NOT NULL,
    PRIMARY KEY ( CategoryNum )
    );"
fi

echo -e "Mysql-server config complete.\n"

# Check whether UserTable is empty

ADMINEXISTS=$(mysql -uroot -ppassword -s -e "use donation; select count(*) from UserTable where FlagAdmin='1';")

if [[ $ADMINEXISTS > 0 ]]; then
  echo "An admin user already exist, skipping admin creation."
else
  echo "Creating root admin..."

  # Create random 32 char password
  ADMINPASS=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
  # Create random 16 char salt
  ADMINSALT=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 16 | head -n 1)
  ADMINHASH=$(echo -n $ADMINSALT$ADMINPASS | openssl dgst -sha256 | sed 's/^.* //')
  ADMINEMAIL="admin@example.com"

  mysql -uroot -p${MYSQLROOTPASSWD} -e "
  use donation;
  INSERT INTO UserTable
    (FirstName, LastName, Email, PassHash, PassSalt, FlagAdmin, FlagUser, FlagDonor, FlagDonee, Active, lastTaxGenDate)
    VALUES
	('admin', 'admin', '$ADMINEMAIL', '$ADMINHASH', '$ADMINSALT', 1, 1, 0, 0, 1, now());";
fi

###### END CREATE DATABASE TABLES ######




# Check if apache2 is installed
PKG_OK=$(dpkg-query -W --showformat='${Status}\n' apache2|grep "install ok installed") || true
echo Checking for apache2: $PKG_OK
# Get apache2 if absent
if [ "" == "$PKG_OK" ]; then
  echo ""
  apt-get -y install apache2 php5 php-pear php5-mysql > /dev/null 2&>1
  a2enmod ssl php5
fi
# Configure virtualhost
echo "Inserting new apache virtualhost..."
cp ${WORKINGDIR}/apachetemplate.conf /etc/apache2/sites-available/${DNSNAME}.conf
cd /etc/apache2/sites-available/
# Inject values into virtualhost file
sed -i "s#SITENAMETARGET#$DNSNAME#" ${DNSNAME}.conf
sed -i "s#WEBROOTTARGET#$WEBROOT#" ${DNSNAME}.conf
# Enable new site
echo "Enabling apache host..."
a2ensite ${DNSNAME}.conf
service apache2 reload
echo ""



# Create webroot if not exists
if [ ! -d $WEBROOT ]; then
  mkdir -p $WEBROOT;
fi

# Move web application to default webroot.
# TODO: ask for webroot in script
cd $WEBROOT
echo "Packing up old webroot..."
#TODO check if this czf is actually excluding, I'm suspicious it's not
tar czf oldwebroot-$DATE.tar.gz * --exclude="oldwebroot[.]*" || true
rm -r fpdf || true
rm -r swiftmailer* || true
rm -r html || true
rm config.* || true


echo "Installing new web components..."
cp $WORKINGDIR/html $WEBROOT/ -r

# Place config files
cp $WORKINGDIR/config* $WEBROOT/

# Git swiftmailer current version
cd $WEBROOT
echo "Getting latest version of Swiftmailer..."
git clone https://github.com/swiftmailer/swiftmailer.git
mv swiftmailer swiftmailer-5.x
# Git FPDF
echo "Getting latest version of FPDF..."
git clone https://github.com/Setasign/FPDF.git fpdf


# Substitute script values into config file
# Caution: some fields have forward slashes
cd $WEBROOT
sed -i "s#\(mysql_addr=\).*#\1\"$DATABASEADDR\"#" config.ini
sed -i "s/\(mysql_user=\).*/\1\"$MAINDBUSER\"/" config.ini
sed -i "s/\(mysql_pass=\).*/\1\"$MYSQLROOTPASSWD\"/" config.ini
sed -i "s/\(mysql_db=\).*/\1\"$MAINDB\"/" config.ini
sed -i "s#\(path_web=\).*#\1\"$WEBADDR\"#" config.ini
sed -i "s/\(use_captchas=\).*/\1$CAPTCHAS/" config.ini
sed -i "s/\(use_email_verification=\).*/\1$EMAILVER/" config.ini
sed -i "s#\(time_zone=\).*#\1\"$TZ\"#" config.ini
sed -i "s/\(No_Reply_email_address=\).*/\1\"$EMAILRELAYUSER\"/" config.ini
sed -i "s/\(No_Reply_email_password=\).*/\1\"$EMAILRELAYPASSWD\"/" config.ini
sed -i "s/\(contact_us_email=\).*/\1\"$CONTACTEMAILADDR\"/" config.ini
sed -i "s/\(nonprofit_name=\).*/\1\"$COMPANYNAME\"/" config.ini

if [[ $ADMINEXISTS == 0 ]]; then
  echo "Admin user $ADMINEMAIL created with password $ADMINPASS. You may wish to change this through the user profile page after logging in."
fi

echo -e "\nDone\n"


