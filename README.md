#### &nbsp; Musement App Developed By Vahid Mahdiun from Jagaad


 
## 💻 &nbsp;Features


List of all the features : 
<img width="50%" align="right" alt="Github Image" src="https://raw.githubusercontent.com/onimur/.github/master/.resources/git-header.svg" />

- 📫 PHP 8 
- 📫 Symfony framework
- 📫 Docker environment
- 📫 Unit tests with %100 coverage
- 💬 Code analyzer (php cs fixer)
- 💬 Standard coding style
- 💬 Beautiful README file
- 💬 Caching mechanism



 
## 👨‍💻 &nbsp;How to install 



#### &nbsp;-  Clone the project


```bash
git clone git@github.com:nerdial/musement.git
cd ./musement
```

#### &nbsp;- Run the following to setup docker containers

```bash
docker-compose up -d
```

You need to wait for a while until docker does its thing, since we need to run test cases with coverage, xdebug also included

![Dino](https://github.com/sourabmaity/sourabmaity/blob/main/dino.gif)

#### &nbsp;- Run migrations first

```bash
docker-compose exec php bash -c "php bin/console doctrine:migration:migrate --no-interaction"
```

#### &nbsp;- Now you should be able to access the app with port :9000

[http://localhost:9000](http://localhost:9000)


 ## 💻 &nbsp; TASK 1
 
For the first task I implemented a console app which prints all cities with weathers 


#### &nbsp;- Run console app 

```bash
docker-compose exec php bash -c 'php bin/console app:crawl'
```

#### &nbsp;- If you want the same result inside a html table 

[http://localhost:9000/city](http://localhost:9000/city)


 ## 💻 &nbsp; TASK 2

For the second task I used api platform with custom actions 



You will see a bunch of almost working apis created for that purpose

[http://localhost:9000/api/v3](http://localhost:9000/api/v3)



<hr>

### &nbsp;- Running unit tests 

```bash
docker-compose exec php bash -c 'php ./vendor/bin/phpunit'
```

### &nbsp;- Unit test with coverage

```bash
docker-compose exec php bash -c 'php ./vendor/bin/phpunit --coverage-text'
```

### &nbsp;- Run the php cs fixer 

```bash
docker-compose exec php bash -c 'php ./vendor/bin/php-cs-fixer fix'
```

