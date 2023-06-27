import "@/assets/sass/main.sass";

import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";

// Опция для обновления изменившихся модулей в реал тайм
if (module.hot) {
  module.hot.accept();
}

if (process.env.NODE_ENV === "development") {
  globalThis.__VUE_OPTIONS_API__ = true;
  globalThis.__VUE_PROD_DEVTOOLS__ = true;
} else {
  globalThis.__VUE_OPTIONS_API__ = false;
  globalThis.__VUE_PROD_DEVTOOLS__ = false;
}

const app = createApp(App);

app.use(createPinia());
app.mount("#calculator-app");
