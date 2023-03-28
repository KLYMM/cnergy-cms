var txtarea = document.querySelectorAll('.code-inventory');
var nav = document.querySelectorAll('.nav-link-inventory');

var mixedMode = {
    name: "htmlmixed",
};


txtarea.forEach((el, i) => {
    CodeMirror.fromTextArea(el, {
        mode: mixedMode,
        theme: 'material',
        lineWrapping: true,
		lineNumbers: true,
		styleActiveLine: true,
		matchBrackets: true,    
        tabSize: 4,
        extraKeys: {"Ctrl-Space": "autocomplete"},
    });
    
    CodeMirror.commands.autocomplete = function(cm) {
        CodeMirror.showHint(cm, CodeMirror.hint.html);
    } 
})

nav.forEach((el, i) => {
    el.addEventListener('click', function () {
        $('.CodeMirror').each(function (i, el) {
            el.CodeMirror.refresh();
        });
    })
})
