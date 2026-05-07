/**
 * Critical CSS Configuration
 *
 * Define URL-to-template mappings for critical CSS generation.
 * Each entry maps a site URL to a template name that will be used
 * as the output filename in templates/_critical-css/.
 *
 * The `url` is appended to the SITE_URL environment variable.
 * The `template` determines the output filename: {template}_critical.min.css
 */
export default {
    urls: [
        { url: '/', template: 'index' },
    ],
}
