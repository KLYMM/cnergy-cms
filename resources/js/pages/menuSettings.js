var site_logo_preview = document.getElementById("site_logo_preview")
var site_logo_input = document.getElementById("site_logo_input")
var favicon_preview = document.getElementById("favicon_preview")
var favicon_input = document.getElementById("favicon_input")
var accent_color_str = document.getElementById("accent_color_str").value
var accent_color_input = document.getElementById("accent_color_input")

accent_color_input.value = accent_color_str.split(" ")[1]

site_logo_input.onchange = evt => {
    const [file] = site_logo_input.files
    if (file) {
        site_logo_preview.src = URL.createObjectURL(file)
    }
}

favicon_input.onchange = evt => {
    const [file] = favicon_input.files
    if (file) {
        favicon_preview.src = URL.createObjectURL(file)
    }
}

