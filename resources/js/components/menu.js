var selectedMenu = document.querySelector('[data-status-sidebar="true"]')
let arr = []

function checkParent(selected) {
    let menu = selected.getAttribute("data-parent-sidebar");
    var parent = document.querySelector(`[data-id-sidebar="${menu}"]`);
    if (parent) {
        arr.push(menu);
        checkParent(parent);
        parent.classList.add("active");
        parent.querySelector(":scope > .submenu").classList.add("active")
    }

    return menu;
}

checkParent(selectedMenu);
// console.log(arr);