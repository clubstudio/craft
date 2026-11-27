import '../css/site.css'

/*
 * Modules in ./modules/ load only on pages that use them. Give an element data-module="name" (several names separated
 * by spaces) and the module's default export is called once with that element. Vite builds each module into its own
 * file, so a page downloads only the modules its markup names.
 */
const modules = import.meta.glob('./modules/*.js')

const elementsByModule = new Map()

document.querySelectorAll('[data-module]').forEach((element) => {
    element.dataset.module.split(/\s+/).filter(Boolean).forEach((name) => {
        elementsByModule.set(name, [...(elementsByModule.get(name) ?? []), element])
    })
})

elementsByModule.forEach(async (elements, name) => {
    const load = modules[`./modules/${name}.js`]

    if (!load) {
        console.warn(`data-module="${name}" has no module at src/js/modules/${name}.js`)
        return
    }

    const { default: start } = await load()
    elements.forEach((element) => start(element))
})
