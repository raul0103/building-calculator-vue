- DEV
  - Корневая папка dist/
- PROD
  - Перенести директорию dist/calculator на сервер
  - На рабочей странице создать <div id="calculator-app"></div>
  - Прописать пути к стилям и js calculator/public/assets

### Project Setup

```sh
npm install
```

### Compile and Hot-Reload for Development

```sh
npm run dev
```

### Compile and Minify for Production

```sh
npm run build
```

Начинается все с конфиг файла dist\calculator\api\config\CalculatorConfig.php
В нем хранятся данные для расчетов по всем калькулятора
