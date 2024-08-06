var theme; // to store page mode in

function changeMode(){
    var icon = document.getElementById('thame_icon');

    // if it was dark mode
    if(document.body.classList.contains("dark-thame")){
        document.body.classList.toggle("dark-thame"); // change mode
        icon.src = '../moon.png'
        theme = 'Light';
    }
    // if it was light mode
    else {
        document.body.classList.toggle("dark-thame"); // change mode
        icon.src = '../sun.png';
        theme = 'Dark';
    }

    // save theme to local storage
    localStorage.setItem('PageTheme', theme);
}

// when page loads
function pageMode(){
    // get page theme from local storage
    let GetTheme = localStorage.getItem('PageTheme');
    var icon = document.getElementById('thame_icon');

    // keep it dark mode if it was dark in local storage
    if(GetTheme == 'Dark'){
        document.body.classList.toggle("dark-thame");
        icon.src = '../sun.png';
    }
}