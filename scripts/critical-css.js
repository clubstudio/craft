import { generate } from 'critical'
import { loadEnv } from 'vite'
import { resolve, dirname } from 'path'
import { mkdirSync, readFileSync, writeFileSync, globSync, rmSync } from 'fs'
import { fileURLToPath } from 'url'
import config from '../critical.config.js'

const __dirname = dirname(fileURLToPath(import.meta.url))
const root = resolve(__dirname, '..')
const env = loadEnv('production', root, '')
const siteUrl = env.PRIMARY_SITE_URL

if (!siteUrl) {
    console.error('PRIMARY_SITE_URL is not set in .env')
    process.exit(1)
}

const outputDir = resolve(root, 'templates/_critical-css')
mkdirSync(outputDir, { recursive: true })

const cssFiles = globSync(resolve(root, 'web/dist/assets/*.css'))

if (!cssFiles.length) {
    console.error('No CSS files found in web/dist/assets/. Run "vite build" first.')
    process.exit(1)
}

/**
 * Unwrap @layer blocks from CSS so that Penthouse's css-tree parser
 * can process the rules inside. Tailwind CSS 4 wraps all output in
 * @layer directives which css-tree doesn't support.
 */
function unwrapLayers(css) {
    let result = ''
    let i = 0

    while (i < css.length) {
        if (css.startsWith('@layer ', i) || css.startsWith('@layer\t', i)) {
            // Skip past "@layer"
            i += 7

            // Skip the layer name
            while (i < css.length && css[i] !== '{' && css[i] !== ';') i++

            if (css[i] === ';') {
                // Empty layer declaration (e.g. "@layer components;") — skip it
                i++
                continue
            }

            if (css[i] === '{') {
                // Skip the opening brace, keep the contents
                i++

                // Find the matching closing brace
                let depth = 1
                const start = i

                while (i < css.length && depth > 0) {
                    if (css[i] === '{') depth++
                    else if (css[i] === '}') depth--
                    if (depth > 0) i++
                }

                // Append the contents without the layer wrapper
                result += css.slice(start, i)
                i++ // skip closing brace
            }
        } else {
            result += css[i]
            i++
        }
    }

    return result
}

// Preprocess CSS: unwrap @layer blocks and write to a temp file
const rawCss = cssFiles.map(f => readFileSync(f, 'utf-8')).join('\n')
const processedCss = unwrapLayers(rawCss)
const tempCssPath = resolve(root, 'web/dist/assets/_critical-tmp.css')
writeFileSync(tempCssPath, processedCss)

console.log(`CSS: ${rawCss.length} bytes → ${processedCss.length} bytes (after unwrapping @layer)`)
console.log(`Generating critical CSS from ${siteUrl}...\n`)

try {
    for (const { url, template } of config.urls) {
        const fullUrl = `${siteUrl}${url}`
        const outputFile = `${template}_critical.min.css`

        console.log(`  ${fullUrl} → _critical-css/${outputFile}`)

        const { css } = await generate({
            base: resolve(root, 'web/dist'),
            src: fullUrl,
            css: [tempCssPath],
            width: 1300,
            height: 900,
            target: {
                css: resolve(outputDir, outputFile),
            },
            penthouse: {
                puppeteer: {
                    args: ['--no-sandbox', '--disable-setuid-sandbox', '--ignore-certificate-errors'],
                },
            },
        })

        console.log(`    Generated ${css.length} bytes of critical CSS`)
    }
} finally {
    rmSync(tempCssPath, { force: true })
}

console.log('\nCritical CSS generation complete.')
