/*
 * A video with a custom play button: data-module="video" on an element holding a <video> and a [data-video-play]
 * button (see _components/video). Without JavaScript the native controls show instead.
 */
export default function video(element) {
    const player = element.querySelector('video')
    const button = element.querySelector('[data-video-play]')

    if (!player || !button) {
        return
    }

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
}
