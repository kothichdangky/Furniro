import { fileURLToPath, URL } from 'node:url'
// vite.config.ts
import VueRouter from 'unplugin-vue-router/vite'
import Components from 'unplugin-vue-components/vite'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import AutoImport from 'unplugin-auto-import/vite'
import { BootstrapVueNextResolver } from 'bootstrap-vue-next'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
    VueRouter(),
    Components({
      dirs: ['src/components', 'src/pages'],
      resolvers: [
        BootstrapVueNextResolver({
          aliases: {
            BInput: 'BFormInput',
          },
        }),
      ],
    }),
    AutoImport({
      imports: [
        'vue', // tự động import ref, reactive, watch,...
        'vue-router', // tự động import useRouter, useRoute
        'pinia', // nếu dùng pinia
      ],
      dts: 'src/auto-imports.d.ts',
    }),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },

  

})
