export default function navigation() {
    document.querySelectorAll('[data-nav-toggle]').forEach((button) => {
        const setOpen = (open) => button.setAttribute('aria-expanded', String(open))

        button.addEventListener('click', () => setOpen(button.getAttribute('aria-expanded') !== 'true'))

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') {
                setOpen(false)
                button.focus()
            }
        })
    })
}
