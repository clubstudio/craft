/*
 * The mobile menu's toggle button: data-module="navigation" on a <button> with aria-controls naming the menu.
 * The menu shows and hides through CSS (peer-aria-expanded), so this only flips aria-expanded, and Escape closes it.
 */
export default function navigation(button) {
    const setOpen = (open) => button.setAttribute('aria-expanded', String(open))

    button.addEventListener('click', () => setOpen(button.getAttribute('aria-expanded') !== 'true'))

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') {
            setOpen(false)
            button.focus()
        }
    })
}
