var image_preview_result = document.getElementById("image_preview_result")
var image_preview_modal = document.getElementById("image_preview_modal")
var upload_image_button = document.getElementById("upload_image_button")
var image_list_parent = document.getElementById("image_list_parent")

var image_bank_modal = document.getElementById("image_bank_modal")
var image_bank_type = $("#image-bank").attr("aria-type");
var search_image_bank_input = document.getElementById("search_image_bank_input")
var search_image_bank_button = document.getElementById("search_image_bank_button")
let list = []
var upload_image_selected = document.getElementById("upload_image_selected")
var save_uploaded_image = document.getElementById("save_uploaded_image")

const upload_image_bank_button = document.getElementById("upload_image_bank_button");
const upload_image_bank_button_photonews = document.getElementById("upload_image_bank_button_photonews");
const img_uploader_modal = document.getElementById("image-bank");
let imageURLTinyMCE = '';

let selectedPhotoNews = [];
let index_photonews = 1;

var buttonsOldPhotoNews2 = document.querySelectorAll('.bi-trash');
buttonsOldPhotoNews2.forEach((el, i) => {
    el.addEventListener('click', function () {
        if (confirm('Delete Current Page ?')) {
            const id = $(el).siblings("#photonews_old_id").val();
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const data = {
                id: id,
                _token: token
            }
            if (id) {
                $.ajax({
                    url: "/update/news/photo/api/delete",
                    type: 'POST',
                    data: data,
                    success: function ({
                        message
                    }) {
                        document.querySelector(`#imagephotonews-${id}`).remove();
                        new Toastify({
                            text: message,
                            duration: 3000,
                            close: true,
                            gravity: "bottom",
                            position: "right",
                            backgroundColor: "#4fbe87",
                        }).showToast()
                    },
                    error: function (err) {
                        new Toastify({
                            text: err,
                            duration: 3000,
                            close: true,
                            gravity: "bottom",
                            position: "right",
                            backgroundColor: "#ff0000",
                        }).showToast()
                    }
                });
            }
        }
    })
})

function clearButtonSelectImageModal() {
    var buttonSelect = document.querySelectorAll(".button_image_bank_modal")
    var button_image_bank_modal_arr = document.querySelectorAll(".button_image_bank_modal")
    button_image_bank_modal_arr.forEach(item => {
        item.innerHTML = `<i class="bi bi-plus-circle"></i>&nbsp;&nbsp;Select`
        item.setAttribute('status-selected', 'false')
        item.classList.add('btn-warning')
        item.classList.remove('btn-danger')
    })
}
upload_image_bank_button.addEventListener('click', function () {
    const img_uploader_modal = document.getElementById("image-bank");
    img_uploader_modal.setAttribute("tinymce-image-bank", false);
    clearButtonSelectImageModal();
})

upload_image_bank_button_photonews.addEventListener('click', function () {
    const img_uploader_modal = document.getElementById("image-bank");
    var buttonSelect = img_uploader_modal.setAttribute("tinymce-image-bank", false);
    clearButtonSelectImageModal();
})

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
    const img_uploader_modal = document.getElementById("image-bank");
    var tiny_mce_image_bank = img_uploader_modal.getAttribute("tinymce-image-bank");
    if (tiny_mce_image_bank === 'false') {
        const image = JSON.parse($(this).siblings('input').val());
        if (this.getAttribute("status-selected") === 'true') {
            this.innerHTML = `<i class="bi bi-plus-circle"></i>&nbsp;&nbsp;Select`
            this.setAttribute('status-selected', 'false')
            selectedPhotoNews = selectedPhotoNews.filter((el) => el.slug !== image.slug);
        } else {
            this.setAttribute('status-selected', 'true')
            selectedPhotoNews.push(image);
            this.innerHTML = `<i class="bi bi-dash-circle"></i>&nbsp;&nbsp;Deselect`
        }
        const status_selected = $("[status-selected=true]").length;
        if (status_selected <= 0) {
            $("#save_photo_news").attr("disabled", true)
        } else {
            $("#save_photo_news").removeAttr("disabled")
        }
        this.classList.toggle('btn-warning')
        this.classList.toggle('btn-danger')

    } else {
        let pattern = new RegExp('/200xauto-');
        insertIntoTinyMCEditor({
            metaImage: JSON.parse(this.parentNode.querySelector("[data-key='data_image']").value),
            imageSrc: imageSrc.replace(pattern, '')
        });
        imageURLTinyMCE = imageSrc;
        $('#image-bank').removeClass("show").css("display", "none")
    }
}



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
    $.ajax({
        url: "/image-bank/api/create",
        type: 'POST',
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
                $("#other_page").append(cardPhotoNews(data, index_photonews));
                index_photonews++;
            } else {
                insertIntoTinyMCEditor({
                    metaImage: data,
                    imageSrc: `${path}/${data.slug}`,
                })
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
            $(button).html(` <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-sm-block"><i class="bi bi-save"></i>&nbsp;&nbsp;Save
                Image</span>`);
             $(button).parent().parent().children().find('#keywords').tagsinput('removeAll');
            $(button).parent().parent().children().find('#keywords').val(null).trigger('change');
            $(button).parent().parent().children().find(".bootstrap-tagsinput").children('span').remove();
            upload_image_selected.value = `${path}/${slug}`;
            image_preview_modal.src = `${path.split('/storage').slice(0, -1)}/assets/images/preview-image.jpg`
        },
        error: function (err) {

            $(button).html(` <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-sm-block"><i class="bi bi-save"></i>&nbsp;&nbsp;Save
                Image</span>`);


            const {
                responseText,
                responseJSON,
                status
            } = err;

            
            if (status === 422) {
                const { errors } = responseJSON; 
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
                    el.classList.add('is-invalid');
                    const name = el.name;
                    if (name === 'keywords') {
                        el.previousElementSibling.classList.add('border-danger');
                    }
                })
                return;
            }

            if(status === 500){
                const { message } = responseJSON; 
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
                const name = el.name;
                if (name === 'keywords') {
                    el.previousElementSibling.classList.remove('border-danger');
                }
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


let path = document.getElementById('path_image').value;

upload_image_button.onchange = evt => {
    const [file] = upload_image_button.files
    if (file) {
        upload_image_selected.value = null;
        image_preview_modal.src = URL.createObjectURL(file)
    }
}

if ($(`.button-old-photo-news`).length > 0) {
    var buttonsOldPhotoNews = document.querySelectorAll('.button-old-photo-news');
    buttonsOldPhotoNews.forEach((el, i) => {
        el.addEventListener('click', function () {
            const url = $(el).attr("url-data")
            image_preview_result.src = url;
            upload_image_selected.value = url;
        })
    })
}

if (image_bank_type == 'photonews') {
    $("#save_photo_news").click(function () {
        selectedPhotoNews.map((el, i) => {
            $("#other_page").append(cardPhotoNews(el, index_photonews))
            $(`.bi-trash`).click(function () {
                document.querySelector(`#imagephotonews-${index_photonews-1}`).remove();
            })
            var url = '';
            $(`#button-photonews-selected-${index_photonews}`).click(function () {
                url += $(this).attr("url-data")
                image_preview_result.src = url;
                image_preview_result.setAttribute("selectedImage", true);
                upload_image_selected.value = url;
            })
            if (i == 0) {
                if (image_preview_result.getAttribute("selectedImage") === 'false') {
                    image_preview_result.src = path + el.slug;
                    upload_image_selected.value = path + el.slug;
                    image_preview_result.setAttribute("selectedImage", true);
                }
            }

            index_photonews++;
        })
        selectedPhotoNews = [];
    })
}


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
        } = el;

        const meta_data = JSON.stringify(el)

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
               <p class="mx-2 font-14 mt-3 mb-1">${title}</p>
               <input type="hidden" data-key="data_image" value='${meta_data}' />
               <span class="mx-2 btn-warning font-14 w-100 button-action button_image_bank_modal" status-selected="false" ${image_bank_type !== "photonews" ? `data-bs-dismiss="modal"` : null}><i
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

function tinyMCEConfig(index_pages) {
    tinymce.EditorManager.execCommand('mceAddEditor', true, `editor-${index_pages}`);
    tinymce.init({
        selector: `textarea.editors${index_pages}`,
        themes: 'modern',
        height: 400,
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
}

const cardPhotoNews = (image, index) => {
    const {
        id,
        title,
        slug,
        caption,
        keywords,
        copyright,
        description
    } = image;
    return `<div class="card" id="imagephotonews-${+index}">
    <div class="card-header d-flex justify-content-between">
    <a data-bs-toggle="collapse" class="d-flex justify-content-between w-100" href="#photonews-${+index}" aria-expanded="false"
    aria-controls="collapseExample">
        <span class="h4 text-capitalize m-0">Image</span>
        <i class="bi bi-chevron-up pull-right fs-6 me-3"></i>
        <i class="bi bi-chevron-down pull-right fs-6 me-3"></i>
        </a>
        <i class="bi bi-trash pull-right text-danger fw-bold"></i> 
    </div>
    <div class="collapse show fade" id="photonews-${+index}">
        <div class="card-body d-flex flex-column gap-2">
            <div class="row">
                <div class="col-md-5 col-12">
                    <div class="form-group">
                            <input type="hidden" name="photonews[${+index}][id]" value="${id}" />
                        <div class="image-file-preview mt-3">
                            <img src="${path}/${slug}" alt="" srcset="">
                            <input type="hidden" name="photonews[${+index}][url]" value="${slug}" />
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-end gap-3 mt-3 flex-column">
                                <span url-data="${path}/${slug}" id="button-photonews-selected-${+index}" class="btn btn-light-secondary me-1 mb-1">Set as Main
                                    Image</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-12">
                    <div class="form-group">
                        <label for="caption" class="mb-2">Caption</label>
                        <input type="text" class="form-control" id="caption" name="photonews[${+index}][caption]"
                            placeholder="Enter Caption " required value="${caption}" />
                    </div>
                    <div class="form-group">
                        <label for="copyright" class="mb-2">Copyright</label>
                        <input type="text" class="form-control" id="copyright" name="photonews[${+index}][copyright]"
                            placeholder="Enter Copyright " required value="${copyright}"/>
                    </div>
                    <div class="form-group">
                        <label class="mb-2">Keyword</label><br>
                        <input name="photonews[${+index}][keywords]" id="image_keywords" type="text" required
                            class="w-100 form-control" data-role="tagsinput" placeholder="Enter Keywords " value="${keywords}" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="image_description" class="form-label mb-2">Description</label>
                <textarea name="photonews[${+index}][description]" class="form-control" id="image_description" cols="30" rows="3" required
                    placeholder="Enter Description">${description}</textarea>
            </div>
        </div>
    </div>
</div>
`
}
