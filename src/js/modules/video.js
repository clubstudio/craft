export default function video() {
    document.querySelectorAll('[data-video]').forEach((figure) => {
        const player = figure.querySelector('video')
        const button = figure.querySelector('[data-video-play]')

        player.controls = false
        button.hidden = false

        const start = () => {
            button.hidden = true
            player.controls = true
        }

        button.addEventListener('click', () => {
            start()
            player.play()
            player.focus()
        })

        player.addEventListener('play', start)
    })
}
