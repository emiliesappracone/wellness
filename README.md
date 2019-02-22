### 1- Git clone

````
git clone https://github.com/emiliesappracone/wellness
````

### 2- Composer configuration

##### Install or update dependencies

````
composer install 
````

### 3- Database configuration

##### Edit .env with your credentials

````
DATABASE_URL=mysql://root:root@127.0.0.1:3306/isl_wellness
````

##### Create database and load fixtures

````
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load 
````

> Warning : fixtures loaded is entities : Locality, City, ZipCode, User (admin), Service

### 4- I invite you to create one surfer and one provider

### 5- In this website you can : 

* See Services and service details (slug)
* See Providers and provider details (slug)
* See Providers of Service
* See Internships of Provider
* Let comment on provider details if you are a surfer
* Search provider(s) with criteria : Locality, Service, and directly the Provider
* Sign in as Surfer or Provider
* Login after subscription as Surfer and Provider
* Edit your profile (depends on user type you are)
* Edit your password
* Subscribe and unsubscribe to newsletter if you are a Surfer
* Add Internship if you are a provider
* Add / Remove services related to you if you are a provider
* Contact the website on page Contact
* Learn about website on page About us
* Access to an administration interface, where you can manage : 
    * To access this part, please use credentials (AppFixtures) : admin + 123456
    * Providers (CRUD)
    * Surfers (CRUD)
    * Comments (RUD)
    * Services (CRUD and highlighted service)
    * Newsletter (CRD and send to users sub to the website newsletter)
    * Newsletter : you can also update user to unsub to the newsletter, from this panel
    * Contact (Readonly)
    * Manage pages (Update only)

