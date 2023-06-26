import { fileURLToPath, URL } from "node:url";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
  build: {
    outDir: "dist/calculator/public",
    rollupOptions: {
      output: {
        entryFileNames: "assets/[name].js",

        assetFileNames: ({ name }) => {
          if (/\.(gif|jpe?g|png|svg)$/.test(name ?? "")) {
            return "assets/images/[name].[ext]";
          }

          if (/\.sass$/.test(name ?? "")) {
            return "assets/[name].[ext]";
          }

          return "assets/[name].[ext]";
        },
      },
    },
  },
});
