- DEV
  - Корневая папка dist/
- PROD
  - Перенести директорию dist/calculator на сервер
  - На рабочей странице создать <div id="calculator-app"></div>
  - На рабочей странице прописать пути к стилям и js calculator/public/assets

### Project Setup

```sh
npm install
```

### Compile and Hot-Reload for Development

```sh
npm run serve
```

### Compile and Minify for Production

```sh
npm run build
```

### Vendor PHP

```sh
composer install
```

### Принцип работы

- Начинается все с данных по калькуляторам `dist\calculator\calculators\calculate-data.php` - В нем хранятся данные для расчетов по всем калькуляторам.

- Заполнить конфиг `dist\calculator\config\config.php`

- При загрузке страницы с фронтенда приходит запрос на получение данных вышеупоммянутуго конфига, для отприсовки полей.

- При расчете запрос отправляется в `dist\calculator\api\calculate.php` который служит чем то вроде контроллера и решает какой расччет выполнять.

- Далее выполняется сам расчет. Расчеты храняться в `dist\calculator\calculators`
