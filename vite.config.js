import { defineConfig, loadEnv } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd(), '')

    const port = 5173
    const origin = `${env.PRIMARY_SITE_URL}:${port}`

    return {
        base: command === 'serve' ? '/' : '/dist/',
        build: {
            manifest: true,
            outDir: './web/dist',
            rollupOptions: {
                input: {
                    index: './src/js/site.js',
                }
            }
        },
        server: {
            host: '0.0.0.0',
            port: port,
            strictPort: true,
            origin: origin,
            cors: true,
        },
        plugins: [
            tailwindcss(),
        ],
    }
})