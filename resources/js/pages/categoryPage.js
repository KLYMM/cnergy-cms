import Toastify from 'toastify-js';
import Sortable from 'sortablejs';
import $ from 'jquery'; 

var button = document.querySelector("#button-save-order");
var type_data = document.querySelector("#type_data").value;
let orderedMenu = {
    [type_data]: []
};
button.addEventListener('click', function () {
    var nestedSortableSaved = document.querySelectorAll('.accordion-item');
    nestedSortableSaved.forEach((el, index) => {
        const id = el.getAttribute("data-id");
        const parent_id = el.parentNode.parentNode.getAttribute("data-id");
        const name = el.getAttribute("data-name");
        if(type_data == 'menu'){
            orderedMenu[type_data].push({
                'id': +id,
                'menu_name': name,
                'slug': name.toLocaleLowerCase().split(' ').join('-'),
                'parent_id': parent_id ?? null,
                'order': index
            })
        }

        if(type_data == 'front-end-menu'){
            orderedMenu[type_data].push({
                'id': +id,
                'title': name,
                'slug': name.toLocaleLowerCase().split(' ').join('-'),
                'parent_id': parent_id ?? null,
                'order': index
            })
        }
        if(type_data == 'category'){
            orderedMenu[type_data].push({
                'id': +id,
                'category': name,
                'common': name,
                'slug': name.toLocaleLowerCase().split(' ').join('-'),
                'types': ["news","video","photonews"],
                'parent_id': parent_id ?? null,
                'order': index
            })
        }
    })
    $(this).html(`
           <span class="spinner-border spinner-border-sm" role="status"
           aria-hidden="true"></span>
       Loading...
    `);
    saveData(orderedMenu);
})
var nestedSortables = document.querySelectorAll('.box-sortable');
for (var i = 0; i < nestedSortables.length; i++) {
    new Sortable(nestedSortables[i], {
        group: 'shared',
        animation: 150,
        fallbackOnBody: true,
        swapThreshold: 0.65,
        onEnd: function (evt) {
            button.classList.remove("d-none");
        },
    });
}

function saveData(sortedData) {
    // let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const input = {
        sortedData: sortedData[type_data]
    }
    console.log(input);
    const url = `/master/${type_data}/api/change`

    $.ajax({
        url: url,
        type: "POST",
        data: input,
        success: function (data) {
            const { message } = data;
            orderedMenu = [];
            new Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "bottom",
                position: "right",
                backgroundColor: "#4fbe87",
            }).showToast()
            $('#button-save-order').toggleClass("d-none")
        },
        error: function (err) {
            orderedMenu = [];
            const {
                responseJSON: {
                    message
                }
            } = err
            new Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "bottom",
                position: "right",
                backgroundColor: "#ff0000",
            }).showToast()
            $('#button-save-order').toggleClass("d-none")
        }
    })
}
