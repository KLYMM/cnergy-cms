let choices = document.querySelectorAll('.choices');
let initChoice;
for (let i = 0; i < choices.length; i++) {
    if (choices[i].classList.contains("multiple-remove")) {
        initChoice = new Choices(choices[i], {
            delimiter: ',',
            editItems: true,
            maxItemCount: -1,
            removeItemButton: true,
        });
    } else {
        initChoice = new Choices(choices[i]);
    }
}

var add_page_button = document.getElementById("add_page_button");
var other_page = document.getElementById("other_page");
var content_box = document.getElementById("content_box");
var card_content = document.getElementById("card_content");
let index = 1;
add_page_button.addEventListener('click', function () {
    let child = document.createElement("textarea");
    child.name = "content[]"
    child.rows = 10
    child.cols = 30
    child.classList.add("my-editor", "form-control", 'my-3', `editors${index}`)
    child.id = `#editor-${index}`
    const new_add_content = card_content.cloneNode(true);
    new_add_content.querySelector("#synopsis").previousElementSibling.remove();
    new_add_content.querySelector("#synopsis").remove();
    new_add_content.querySelector(".my-editor").remove();
    new_add_content.querySelector(".mce-tinymce").remove();
    new_add_content.querySelector(".card-header-text").innerHTML = `Page ${index + 1}`
    new_add_content.querySelector("#content_box").append(child);
    other_page.append(new_add_content)
    tinymce.EditorManager.execCommand('mceAddEditor', true, `editor-${index}`);
    tinymce.init({
        selector: `textarea.editors${index}`,
        themes: 'modern',
        height: 200,
        path_absolute: "/",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
        relative_urls: false,
        file_browser_callback: function (field_name, url, type, win) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                'body')[0].clientWidth;
            var y = window.innerHeight || document.documentElement.clientHeight || document
                .getElementsByTagName('body')[0].clientHeight;

            var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
            if (type == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.open({
                file: cmsURL,
                title: 'Filemanager',
                width: x * 0.8,
                height: y * 0.8,
                resizable: "yes",
                close_previous: "no"
            });
        }
    });
    index++;
})


if(document.body.contains(document.getElementById("news_paginations"))){
    const news_paginations = document.getElementById("news_paginations").value;
    console.log(JSON.parse(news_paginations));
}
