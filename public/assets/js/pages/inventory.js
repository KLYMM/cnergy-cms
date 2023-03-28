var txtarea = document.querySelectorAll('.code-inventory');
var nav = document.querySelectorAll('.nav-link-inventory');

var mixedMode = {
    name: "htmlmixed",
};

txtarea.forEach((el, i) => {
    CodeMirror.fromTextArea(el, {
        lineNumbers: true,
        mode: mixedMode,
    });
})

nav.forEach((el, i) => {
    el.addEventListener('click', function () {
        $('.CodeMirror').each(function (i, el) {
            el.CodeMirror.refresh();
        });
    })
})
