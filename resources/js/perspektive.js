import PerspektiveSelect from "./components/Menu/PerspektiveSelect";
import PerspectiveDisclosure from "./components/Menu/PerspectiveDisclosure";

function setPerspectiveClass() {
    let pConfig = Nova.config('perspectives')
    let htmlTag = document.querySelector('html')
    let slug = pConfig && pConfig.current && pConfig.current.slug ? pConfig.current.slug : null
    let prefix = pConfig.prefix ? pConfig.prefix : 'perspective-'
    htmlTag.classList.forEach(name => {
        if (name.trim().startsWith(prefix)) {
            htmlTag.classList.remove(name)
        }
    })
    if (slug) {
        slug = prefix + slug
        htmlTag.classList.add(slug)
        if (pConfig.beKind) {
            console.log('[Norman Huth]', 'The class `' + slug + '` was added to the HTML tag. You can find more packages and ways to say `thank you` at https://huth.it')
        }
    }
}

Nova.booting((app, store) => {
    app.component('perspektive-select', PerspektiveSelect)
    app.component('perspektive-disclosure', PerspectiveDisclosure)

    window.addEventListener('DOMContentLoaded', () => {
        setPerspectiveClass()
    })
})
