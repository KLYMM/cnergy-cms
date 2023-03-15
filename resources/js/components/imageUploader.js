var image_preview_result = document.getElementById("image_preview_result")
var image_preview_modal = document.getElementById("image_preview_modal")
var upload_image_button = document.getElementById("upload_image_button")
var image_list_parent = document.getElementById("image_list_parent")

var image_bank_modal = document.getElementById("image_bank_modal")

let path = document.getElementById('path_image').value;
var search_image_bank_input = document.getElementById("search_image_bank_input")
var search_image_bank_button = document.getElementById("search_image_bank_button")
let list = []
var upload_image_selected = document.getElementById("upload_image_selected")
var save_uploaded_image = document.getElementById("save_uploaded_image")

const upload_image_bank_button = document.getElementById("upload_image_bank_button");
const img_uploader_modal = document.getElementById("image-bank");
let imageURLTinyMCE = '';

const insertIntoTinyMCEditor = ({
    metaImage,
    imageSrc
}) => {
    var add_meta_image_checkbox = document.getElementById("add_meta_image_checkbox")
    const targetTinyMCE = img_uploader_modal.getAttribute("target-mce");
    var ed = tinymce.get(targetTinyMCE); // get editor instance
    var range = ed.selection.getRng(); // get range
    var newNode = ed.getDoc().createElement("p");
    const {
        copyright,
        caption,
        photographer
    } = metaImage;

    const textContent = `<p style="text-align: center;">
        <img src="${imageSrc}" alt="${caption}" width="400" height="auto" data-mce-src="${imageSrc}">
        ${add_meta_image_checkbox.checked ? 
            `<span class="content-image-caption" style="text-align: center;display: block; color: #525252 ">
        ${caption}<br />&copy; 2022 ${copyright}/${photographer}</span>
        </span>`
            : ''}
    </p>`

    newNode.innerHTML = textContent;

    range.insertNode(newNode);
}

function selectImage() {
    const imageSrc = this.parentElement.children[0].getAttribute("src");

    var tiny_mce_image_bank = img_uploader_modal.getAttribute("tinymce-image-bank");
    let pattern = new RegExp('/200xauto-');
    if (tiny_mce_image_bank === 'false') {
        image_preview_result.src = imageSrc.replace(pattern, '');
        upload_image_selected.value = imageSrc.replace(pattern, '');
        upload_image_button.value = null;
        return
    }

    insertIntoTinyMCEditor({
        metaImage: JSON.parse(this.parentNode.querySelector("[data-key='data_image']").value),
        imageSrc: imageSrc.replace(pattern, '')
    });
    $('#image-bank').removeClass("show").css("display", "none")
}

$('#keyword').on('select2:select', function (e) {
    var data = e.params.data;
    console.log(data);
});

save_uploaded_image.addEventListener('click', async function () {
    var form = document.querySelectorAll("#form-upload-image input, #form-upload-image textarea");
    var fd = new FormData();
    form.forEach((el, i) => {
        const name = el.name;
        let value = el.value;
        if (name === 'image_input') {
            const file = el.files
            value = file[0]
        }
        if (name === 'image_input') {
            const file = el.files
            value = file[0]
        }
        if (name === 'title_image' || name == 'description_image') {
            var name_form = name === 'title_image' ? 'title' : 'description'
            fd.append(name_form, value);
        } else {
            fd.append(name, value);
        }
    })
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fd.append('_token', token);
    fd.append('unique_id', Math.random(10));
    const button = this;
    $(this).html(`
           <span class="spinner-border spinner-border-sm" role="status"
           aria-hidden="true"></span>
       Loading...
    `);
    $(this).attr("disabled", true)
    $.ajax({
        url: "/image-bank/api/create",
        type: "POST",
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function ({
            message,
            data
        }) {
            var tiny_mce_image_bank = img_uploader_modal.getAttribute("tinymce-image-bank");
            const {
                slug
            } = data;
            if (tiny_mce_image_bank === 'false') {
                image_preview_result.src = `${path}/${slug}`;
                $(button).html(` <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-sm-block"><i class="bi bi-save"></i>&nbsp;&nbsp;Save
            Image</span>`);
            } else {
                insertIntoTinyMCEditor({
                    metaImage: data,
                    imageSrc: `${path}/${data.slug}`,
                })
                $(button).html(` <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-sm-block"><i class="bi bi-save"></i>&nbsp;&nbsp;Save
            Image</span>`);
                $('#image-bank').removeClass("show").css("display", "none")
            }

            new Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "bottom",
                position: "right",
                backgroundColor: "#4fbe87",
            }).showToast()
            $(button).attr("disabled", false)
            $("#image-bank").modal("hide");
            form.forEach((el, i) => {
                el.value = "";
                el.classList.remove('is-invalid')
                const name = el.name;
                if (name === 'keywords') {
                    el.previousElementSibling.classList.remove('border-danger');
                }
            })
            $(button).parent().parent().children().find('#keywords').tagsinput('removeAll');
            $(button).parent().parent().children().find('#keywords').val(null).trigger('change');
            $(button).parent().parent().children().find(".bootstrap-tagsinput").children('span').remove();
            console.log('clear');
            upload_image_selected.value = `${path}/${slug}`;
            image_preview_modal.src = `${path.split('/storage').slice(0, -1)}/assets/images/preview-image.jpg`
        },
        error: function (err) {
            $(button).html(` <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-sm-block"><i class="bi bi-save"></i>&nbsp;&nbsp;Save
                Image</span>`);

            $(button).attr("disabled", false)

            const {
                responseText,
                responseJSON,
                status
            } = err;


            if (status === 422) {
                const {
                    errors
                } = responseJSON;
                Object.keys(errors).forEach(el => {
                    new Toastify({
                        text: errors[el],
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "right",
                        backgroundColor: "#ff0000",
                    }).showToast()
                })
                form.forEach((el, i) => {
                    el.classList.add('is-invalid')
                    const name = el.name;
                    if (name === 'keywords') {
                        el.previousElementSibling.classList.add('border-danger');
                    }
                })
                return;
            }

            if (status === 500) {
                const {
                    message
                } = responseJSON;
                new Toastify({
                    text: message,
                    duration: 3000,
                    close: true,
                    gravity: "bottom",
                    position: "right",
                    backgroundColor: "#ff0000",
                }).showToast()
                return;
            }

            form.forEach((el, i) => {
                el.value = "";
                el.classList.remove('is-invalid')
            })
            image_preview_modal.src = `${path.split('/storage').slice(0, -1)}/assets/images/preview-image.jpg`
            $(button).parent().parent().children().find('#keywords').tagsinput('removeAll');
            $(button).parent().parent().children().find('#keywords').val(null).trigger('change');
            $(button).parent().parent().children().find(".bootstrap-tagsinput").children('span').remove();
            new Toastify({
                text: responseText,
                duration: 3000,
                close: true,
                gravity: "bottom",
                position: "right",
                backgroundColor: "#ff0000",
            }).showToast()
        }
    });
})


upload_image_button.onchange = evt => {
    const [file] = upload_image_button.files
    if (file) {
        upload_image_selected.value = null;
        image_preview_modal.src = URL.createObjectURL(file)
    }
}


upload_image_bank_button.addEventListener('click', function () {
    img_uploader_modal.setAttribute("tinymce-image-bank", false);
})


async function search() {
    const query = '?title=' + search_image_bank_input.value;
    image_list_parent.innerHTML = `<div class="spinner-border text-primary mt-3" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>`;
    const data = await fetchData(query);
    list = makeList(data);
    image_list_parent.innerHTML = list
    var button_image_bank_modal_arr = document.querySelectorAll(".button_image_bank_modal")
    button_image_bank_modal_arr.forEach(item => {
        item.addEventListener('click', selectImage);
    })
}

async function fetchData(query = '') {
    const url = `/image-bank/api/list/${query}`;
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const data = await fetch(url, {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json, text-plain, */*",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": token
            },
            method: 'get',
            credentials: "same-origin",
        })
        .then((data) => {
            return data.json()
            // window.location.href = redirect;
        })
        .catch(function (error) {
            console.log(error);
        });

    return data;
}


async function addEventListeners() {
    image_list_parent.innerHTML = `<div class="spinner-border text-primary mt-3" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>`;
    const data = await fetchData();
    list = makeList(data);
    image_list_parent.innerHTML = list

    var button_image_bank_modal_arr = document.querySelectorAll(".button_image_bank_modal")
    button_image_bank_modal_arr.forEach(item => {
        item.addEventListener('click', selectImage);
    })
    search_image_bank_button.addEventListener('click', search);
}

function makeList(data) {
    let imageList = '';
    data.map((el, i) => {
        const {
            title,
            slug,
            caption,
            copyright,
            photographer
        } = el;

        const meta_data = JSON.stringify({
            copyright: copyright,
            caption: caption,
            photographer: photographer,
        })
        const welcome = slug;
        const arr = welcome.split('/');
        const currImage = arr[arr.length - 1];
        const image = '200xauto-' + currImage;
        arr[arr.length - 1] = image;
        const realPath = arr.join('/');



        let str = `
           <div class="image-card border p-0 image-card border p-0 d-flex flex-column align-items-center">
               <img src="${path}/${realPath}"
                   alt="" class="w-100 image_bank_modal">
                   <input type="hidden" data-key="data_image" value='${meta_data}' />
               <p class="mx-2 font-14 mt-3 mb-1">${title}</p>
               <span class="mx-2 btn-warning font-14 w-100 button-action button_image_bank_modal" data-bs-dismiss="modal"><i
                       class="bi bi-plus-circle"></i>&nbsp;&nbsp;Select</span>

           </div>`

        imageList += str;
    })

    return imageList;
}

addEventListeners();

//  button_image_bank_modal.addEventListener('click', function () {
//      const parent = this.parentNode;
//      console.log(parent);
//  })
