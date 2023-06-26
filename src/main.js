import "@/assets/sass/main.sass";

import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";

// Опция для обновления изменившихся модулей в реал тайм
if (module.hot) {
  module.hot.accept();
}

const app = createApp(App);

app.use(createPinia());
app.mount("#calculator-app");
