
var actives = document.querySelectorAll(".active") //lấy thẻ cần đúc class open vào
var openActive = document.querySelector(".open")

actives.forEach((A, index) => {
    if (A.pathname.split("/").join('') === location.pathname.split("/").join('').split("/").join('')) { //nếu pathname == nhau thì 
        // kiểm tra thẻ có open
        var openActive = document.querySelector(".open")
        if (openActive)
            openActive.classList.remove("open") //nếu có remove open 
        A.classList.add("open"); //add open vào
    }
})

console.log(window.location.href)

const dropdownItems = document.querySelectorAll(".dropdown-item.dm")
var names;
dropdownItems.forEach((A, index) => {
    if (A.href === window.location.href) { //nếu pathname == nhau thì 
        names = A.innerHTML
        var openActive = document.querySelector(".open2") // kiểm tra thẻ có open
        if (openActive) openActive.classList.remove("open2") //nếu có remove open
        A.classList.add("open2"); //add open vào
    }
})
