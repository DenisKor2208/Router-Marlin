# Документация:
Располагаете файл Router.php с классом Router в нужном вам месте вашего проекта.

Подключаете файл Router.php. Например:
```php
include_once __DIR__ . '/../Components/Router/Router.php';
```

Далее создаете экземпляр класса Router и запускаете метод run() предварительно передав в конструктор класса Router массив с маршрутами и параметрами. Например:
```php
$router = new Router([
    'pageError' => ['method' => 'GET', 'file' => '/views/404.php'],
    'about' => ['method' => 'GET', 'file' => '/views/about.php'],
    'page/contact' => ['method' => 'GET', 'file' => '/views/contact.php'],
    '' => ['method' => 'GET', 'file' => '/views/homepage.php'],
    'homepage' => ['method' => 'GET', 'file' => '/views/homepage.php'],
    'show/:id' => ['method' => 'GET', 'file' => '/show.php'],
    'edit/:id' => ['method' => 'GET', 'file' => '/edit.php'],
    'update/:id' => ['method' => 'GET', 'file' => '/update.php'],
    'delete/:id' => ['method' => 'GET', 'file' => '/delete.php'],
    'create' => ['method' => 'GET', 'file' => '/create.php'],
    'store' => ['method' => 'POST', 'file' => '/store.php']

]);
$router->run();
```

Более подробно про передачу маршрутов:
```php
['update/:id' => ['method' => 'GET', 'file' => '/update.php']] 
```
Если указываем :id(название в шаблоне может быть любым) в шаблоне, то в файле update.php  в глобальном массиве GET будет доступен ключ id с переданным нами в адресной строке значением(можно передавать как число так и строку).
  + 'update/:id' – шаблон роута, по которому будут проверяться все маршруты введенные пользователем в адресную строку.
  + 'method' => 'GET' – метод передачи параметров на нужную страницу. Указываем GET либо POST
  + 'file' => '/update.php' – запускаемый по этому маршруту файл. В нем же и получаем передаваемые нами через адресную строку параметры.

```php
['create' => ['method' => 'POST', 'file' => '/create.php']]
```
+ Все то-же самое, как и в примере выше, только метод POST и параметров мы никаких не передаем и соответственно в файле create.php из массива POST не получаем.

В массиве с маршрутами и параметрами обязательно должен быть маршрут к файлу 404.php:
```php
'pageError' => ['method' => 'GET', 'file' => '/views/404.php'
```
+ pageError – название шаблона обязательно именно это.
Соответственно путь к файлу 404.php указываете такой, какой нужен вам.

store/:name/:id/:title – кол–во передаваемых параметров может быть любое. Значения числовые или строковые и название парамеров в шаблоне можете устанавливать любое нужное вам.
Все они будут доступны в нужном файле, в указанном глобальном массиве под теми ключами которые вы указали в шаблоне со знаком ‘:’.

# Ход мыслей:
```
   С роутингом более подробнее разобрался, проходя этот модуль, а ранее не использовал его в работе.
За основу брал те наработки, которые вы показывали на уроках, а так же работы других разработчиков в интернете по роутингу.
Так что я написал этот компонент не совсем на 100% сам, но все функции в роутере именовал, компоновал и тестировал на
работоспособность полностью сам. Да и думаю это несколько глупо в наше время с нуля придумывать велосипед, когда в магазине
есть готовый, сделанный профессионалами. Лучше конечно понял этот подход, проходя вашу школу. Конечно, я согласен с тем, что
какие-то принципы работы тех или иных компонентов нужно знать.
   Так как я не профессионал в программировании, то подумал, что роутер должен быть самым обычным, без специфических наворотов,
чтобы работали только самые обычные проекты, а так же  и мои учебные.

  Понял что “.htaccess” перенаправляет  все запросы в “index.php” или туда, куда мы укажем в настройках.
Думал, что нужно будет создать класс роутера, подключить его в index.php и в “index.php” создать экземпляр этого класса и
запустить его на выполнение.
  Необходимо будет передать в конструктор класса роутера массив с имеющимися маршрутами. Ну и далее в самом классе происходит
работа, связанная с переводом пользователя по маршруту на нужный интерфейс нашего приложения.
  Даже незнаю как лучше описать ход моих мыслей.
Сначала я копался в интернете, смотрел информацию по работе роутера и примеры у других разработчиков.
Потом, когда у меня сложилась более менее понятная картинка в голове, каким должен быть роутер, я начал  компоновать методы в
классе, используя в том числе и готовые куски кода.
В моем проекте, в конфигурации с маршрутами используется только сам маршрут, метод GET или POST и путь к самому файлу, который
будет открываться, и принимать в себе указанные через GET или POST переданные параметры. Ну а если параметры не передаются, то будет
открываться только указанный в конфигурации файл. 
  В классе роутера используются так же регулярные выражения. Понял что очень удобная штука.
Роутер далек от идеала и некоторые моменты по роутерам я пока не совсем хорошо понимаю, но честно говоря, жду не дождусь, когда
будем изучать и использовать готовые компоненты.
  
Я очень отвык от того, чтобы создавать для себя инструменты для работы. Очень удобно использовать готовые компоненты и не париться
за то, как именно они работают. Спасибо конечно большое за то, что вложили в мою голову такое мышление.
```
