import "./assets/sass/main.sass";

import { cors } from "cors";
import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";

const app = createApp(App);

app.use(createPinia());
app.use(cors);
app.mount("#calculator-app");
